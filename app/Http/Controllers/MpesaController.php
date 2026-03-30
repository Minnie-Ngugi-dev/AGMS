<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Service;
use App\Services\MpesaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    public function __construct(private MpesaService $mpesa)
    {
    }

    /**
     * Initiate STK Push
     * POST /mpesa/push
     */
    public function push(Request $request): JsonResponse
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'phone'      => 'required|string|min:9',
            'amount'     => 'required|numeric|min:1',
        ]);

        $phone = trim($request->phone);

        // Validate phone — accepts 07XX, 01XX, +254, 254 formats
        if (!$this->mpesa->isValidPhone($phone)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number. Use 07XX, 01XX, or +254 format.',
                'entered' => $phone,
                'hint'    => 'Examples: 0712345678, 0112345678, +254712345678',
            ], 422);
        }

        $service = Service::findOrFail($request->service_id);

        // Check amount
        if ((float) $request->amount > $service->balance) {
            return response()->json([
                'success' => false,
                'message' => 'Amount KSh ' . number_format($request->amount, 2) .
                             ' exceeds balance due KSh ' . number_format($service->balance, 2),
            ], 422);
        }

        // Show formatted number in response
        $formattedPhone   = $this->mpesa->formatPhone($phone);
        $displayPhone     = $this->mpesa->displayPhone($phone);

        $result = $this->mpesa->stkPush(
            phone:       $phone,
            amount:      (float) $request->amount,
            accountRef:  $service->job_card_no,
            description: 'Service Payment'
        );

        // Success
        if (isset($result['ResponseCode']) && $result['ResponseCode'] === '0') {
            return response()->json([
                'success'           => true,
                'message'           => "STK Push sent to {$displayPhone}. Check phone and enter PIN.",
                'CheckoutRequestID' => $result['CheckoutRequestID'],
                'MerchantRequestID' => $result['MerchantRequestID'] ?? null,
                'phone_display'     => $displayPhone,
            ]);
        }

        // Failure
        $errorMsg = $result['errorMessage']
            ?? $result['ResultDesc']
            ?? $result['ResponseDescription']
            ?? 'Failed to send STK Push. Try manual code entry.';

        return response()->json([
            'success' => false,
            'message' => $errorMsg,
            'raw'     => config('app.debug') ? $result : null,
        ], 422);
    }

    /**
     * Query STK Push status (polled by frontend)
     * POST /mpesa/query
     */
    public function query(Request $request): JsonResponse
    {
        $request->validate([
            'checkout_request_id' => 'required|string',
        ]);

        $result     = $this->mpesa->stkQuery($request->checkout_request_id);
        $resultCode = isset($result['ResultCode']) ? (int) $result['ResultCode'] : null;

        return response()->json([
            'paid'       => $resultCode === 0,
            'pending'    => $resultCode === null,
            'cancelled'  => $resultCode === 1032,
            'insufficient'=> $resultCode === 1,
            'message'    => $result['ResultDesc'] ?? 'Checking...',
            'resultCode' => $resultCode,
        ]);
    }

    /**
     * Daraja Callback — Safaricom hits this after payment
     * POST /api/mpesa/callback
     * Must be publicly accessible (no auth)
     */
    public function callback(Request $request): JsonResponse
    {
        $data = $request->all();
        Log::info('M-Pesa Callback Received', $data);

        try {
            $body       = $data['Body']['stkCallback'] ?? null;
            $resultCode = isset($body['ResultCode']) ? (int) $body['ResultCode'] : null;

            if ($resultCode === 0) {
                // Payment successful
                $items = collect($body['CallbackMetadata']['Item'] ?? []);

                $amount  = $items->firstWhere('Name', 'Amount')['Value']             ?? 0;
                $code    = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;
                $phone   = $items->firstWhere('Name', 'PhoneNumber')['Value']        ?? null;
                $ref     = $body['AccountReference'] ?? null;

                Log::info("M-Pesa Payment Success — KSh {$amount} | Code: {$code} | Ref: {$ref} | Phone: {$phone}");

                $service = Service::where('job_card_no', $ref)->first();

                if ($service) {
                    // Avoid duplicate payments
                    $exists = Payment::where('mpesa_code', $code)->exists();

                    if (!$exists && $code) {
                        $service->payments()->create([
                            'amount'       => $amount,
                            'method'       => 'M-Pesa',
                            'mpesa_code'   => $code,
                            'status'       => 'Paid',
                            'payment_date' => now(),
                            'notes'        => 'Auto via Daraja. Phone: ' . $phone,
                        ]);

                        Log::info("Payment recorded for {$service->job_card_no}");

                        // Auto-complete if fully paid
                        $service->refresh();
                        if ($service->balance <= 0 && $service->status !== 'completed') {
                            $service->update([
                                'status'       => 'completed',
                                'completed_at' => now(),
                            ]);
                            Log::info("Service {$service->job_card_no} auto-completed.");
                        }
                    } else {
                        Log::warning("Duplicate or missing code ignored: {$code}");
                    }
                } else {
                    Log::warning("Service not found for ref: {$ref}");
                }

            } else {
                // Payment failed or cancelled
                Log::info('M-Pesa not successful', [
                    'ResultCode' => $resultCode,
                    'ResultDesc' => $body['ResultDesc'] ?? '',
                ]);
            }

        } catch (\Exception $e) {
            Log::error('M-Pesa callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
        }

        // ALWAYS return success to Safaricom (stops their retries)
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted',
        ]);
    }
}