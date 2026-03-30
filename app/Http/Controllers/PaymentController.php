<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Service;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private MpesaService $mpesa) {}

    public function allIndex()
    {
        $payments = Payment::with(['service.vehicle.customer'])
            ->latest()
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    public function create(Service $service)
    {
        $service->load([
            'vehicle.customer',
            'payments',
            'parts',
            'repairs',
            'orderItems',
        ]);

        return view('payments.create', compact('service'));
    }

    // ── CASH PAYMENT ───────────────────────────────────────────────
    public function store(Request $request, Service $service)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'notes'  => 'nullable|string',
        ]);

        Payment::create([
            'service_id'   => $service->id,
            'amount'       => $request->amount,
            'method'       => 'Cash',
            'mpesa_code'   => null,
            'status'       => 'Paid',
            'payment_date' => now(),
            'notes'        => $request->notes ?? 'Cash payment',
        ]);

        $service->refresh();
        if ($service->balance <= 0 && $service->status !== 'completed') {
            $service->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);
        }

        return redirect()->route('services.show', $service)
            ->with('success', 'Cash payment of KSh ' . number_format($request->amount, 2) . ' recorded.');
    }

    // ── STEP 1: SEND STK PUSH — creates a PENDING payment ─────────
    public function mpesaPush(Request $request, Service $service)
    {
        $request->validate([
            'phone'  => 'required|string|min:9',
            'amount' => 'required|numeric|min:1',
        ]);

        $phone     = trim($request->phone);
        $formatted = $this->mpesa->formatPhone($phone);

        Log::info('STK Push Request', [
            'phone_raw'       => $phone,
            'phone_formatted' => $formatted,
            'amount'          => $request->amount,
            'job_card'        => $service->job_card_no,
        ]);

        if (strlen($formatted) !== 12 || !str_starts_with($formatted, '254')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number. Use 07XX, 01XX, +254 or 254 format.',
            ], 422);
        }

        // Send real STK push to Safaricom
        $result = $this->mpesa->stkPush(
            phone:       $formatted,
            amount:      (int) ceil((float) $request->amount),
            accountRef:  $service->job_card_no,
            description: 'Service Payment'
        );

        Log::info('STK Push API Response', $result);

        if (!isset($result['ResponseCode']) || $result['ResponseCode'] !== '0') {
            return response()->json([
                'success' => false,
                'message' => $result['errorMessage']
                    ?? $result['ResultDesc']
                    ?? $result['ResponseDescription']
                    ?? 'STK Push failed. Try again or use manual entry.',
            ], 422);
        }

        $checkoutRequestId = $result['CheckoutRequestID'];

        // ── Create a PENDING payment record ───────────────────────
        // This gets updated to Paid/Failed when callback arrives
        $payment = Payment::create([
            'service_id'          => $service->id,
            'amount'              => $request->amount,
            'method'              => 'M-Pesa',
            'mpesa_code'          => null,             // filled by callback
            'checkout_request_id' => $checkoutRequestId,
            'callback_received'   => false,
            'status'              => 'Pending',        // waiting for PIN
            'payment_date'        => now(),
            'notes'               => 'STK Push sent to ' . $formatted . '. Awaiting PIN.',
        ]);

        Log::info('Pending payment created', [
            'payment_id'          => $payment->id,
            'checkout_request_id' => $checkoutRequestId,
        ]);

        return response()->json([
            'success'           => true,
            'message'           => 'Payment request sent to ' . $formatted . '. Please ask the customer to enter their M-Pesa PIN.',
            'CheckoutRequestID' => $checkoutRequestId,
            'payment_id'        => $payment->id,
        ]);
    }

    // ── STEP 2: POLL — frontend checks if callback has arrived ─────
    public function mpesaQuery(Request $request, Service $service)
    {
        $request->validate([
            'checkout_request_id' => 'required|string',
        ]);

        $checkoutRequestId = $request->checkout_request_id;

        // Find the pending payment by CheckoutRequestID
        $payment = Payment::where('checkout_request_id', $checkoutRequestId)
            ->where('service_id', $service->id)
            ->first();

        if (!$payment) {
            return response()->json([
                'paid'    => false,
                'pending' => true,
                'message' => 'Waiting for payment...',
            ]);
        }

        Log::info('Poll check', [
            'payment_id' => $payment->id,
            'status'     => $payment->status,
            'callback'   => $payment->callback_received,
        ]);

        // ── Callback has arrived — return result ───────────────────
        if ($payment->callback_received) {
            if ($payment->status === 'Paid') {
                return response()->json([
                    'paid'       => true,
                    'pending'    => false,
                    'mpesa_code' => $payment->mpesa_code,
                    'amount'     => $payment->amount,
                    'paid_at'    => $payment->payment_date->format('d M Y, h:i A'),
                    'phone'      => $formatted ?? '',
                ]);
            }

            if ($payment->status === 'Cancelled') {
                return response()->json([
                    'paid'      => false,
                    'pending'   => false,
                    'cancelled' => true,
                    'message'   => 'Payment was cancelled.',
                ]);
            }

            if ($payment->status === 'Failed') {
                return response()->json([
                    'paid'    => false,
                    'pending' => false,
                    'failed'  => true,
                    'message' => $payment->notes ?? 'Payment failed.',
                ]);
            }
        }

        // ── Callback not yet received — still waiting ──────────────
        return response()->json([
            'paid'    => false,
            'pending' => true,
            'message' => 'Waiting for customer to enter PIN...',
        ]);
    }

    // ── MANUAL FALLBACK ────────────────────────────────────────────
    public function mpesaManual(Request $request, Service $service)
    {
        $request->validate([
            'amount'     => 'required|numeric|min:1',
            'mpesa_code' => 'required|string|min:6',
        ]);

        $code = strtoupper(trim($request->mpesa_code));

        if (Payment::where('mpesa_code', $code)->exists()) {
            return back()->withErrors([
                'mpesa_code' => 'This M-Pesa code has already been recorded.',
            ])->withInput();
        }

        Payment::create([
            'service_id'   => $service->id,
            'amount'       => $request->amount,
            'method'       => 'M-Pesa',
            'mpesa_code'   => $code,
            'status'       => 'Paid',
            'payment_date' => now(),
            'notes'        => 'Manual M-Pesa code entry',
        ]);

        $service->refresh();
        if ($service->balance <= 0 && $service->status !== 'completed') {
            $service->update([
                'status'       => 'completed',
                'completed_at' => now(),
            ]);
        }

        return redirect()->route('services.show', $service)
            ->with('success', 'M-Pesa payment recorded. Code: ' . $code);
    }

    public function destroy(Payment $payment)
    {
        $service = $payment->service;
        $payment->delete();

        return redirect()->route('services.show', $service)
            ->with('success', 'Payment removed.');
    }
}