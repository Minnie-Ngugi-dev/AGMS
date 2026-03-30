<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MpesaCallbackController extends Controller
{
    public function handle(Request $request): \Illuminate\Http\JsonResponse
    {
        $body = $request->all();

        Log::info('=== M-PESA CALLBACK HIT ===', $body);

        try {
            $stkCallback = $body['Body']['stkCallback'] ?? null;

            if (!$stkCallback) {
                Log::warning('Callback: missing stkCallback');
                return $this->accept();
            }

            $resultCode        = (int) ($stkCallback['ResultCode'] ?? 1);
            $resultDesc        = $stkCallback['ResultDesc'] ?? '';
            $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? null;
            $merchantRequestId = $stkCallback['MerchantRequestID'] ?? null;

            Log::info("Callback ResultCode: {$resultCode} | Desc: {$resultDesc} | CheckoutID: {$checkoutRequestId}");

            // ── Find the pending payment by CheckoutRequestID ──────
            $payment = null;
            if ($checkoutRequestId) {
                $payment = Payment::where('checkout_request_id', $checkoutRequestId)->first();
            }

            // ── FAILED / CANCELLED ─────────────────────────────────
            if ($resultCode !== 0) {
                if ($payment) {
                    $status = $resultCode === 1032 ? 'Cancelled' : 'Failed';

                    $payment->update([
                        'status'            => $status,
                        'callback_received' => true,
                        'notes'             => "Callback received. ResultCode: {$resultCode} — {$resultDesc}",
                    ]);

                    Log::info("Payment {$payment->id} marked as {$status}.");
                } else {
                    Log::warning("Callback: no payment found for CheckoutRequestID {$checkoutRequestId}");
                }

                return $this->accept();
            }

            // ── SUCCESS — extract metadata ─────────────────────────
            $items    = $stkCallback['CallbackMetadata']['Item'] ?? [];
            $metadata = [];

            foreach ($items as $item) {
                $metadata[$item['Name']] = $item['Value'] ?? null;
            }

            Log::info('Callback Metadata', $metadata);

            $amount    = $metadata['Amount']             ?? null;
            $mpesaCode = $metadata['MpesaReceiptNumber'] ?? null;
            $phone     = $metadata['PhoneNumber']        ?? null;
            $accountRef= $metadata['AccountReference']   ?? null;

            if (!$mpesaCode || !$amount) {
                Log::error('Callback: missing Amount or MpesaReceiptNumber', $metadata);
                return $this->accept();
            }

            // ── Prevent duplicate ──────────────────────────────────
            if (Payment::where('mpesa_code', $mpesaCode)
                ->where('status', 'Paid')
                ->exists()) {
                Log::info("Callback: duplicate code {$mpesaCode}, skipping.");
                return $this->accept();
            }

            // ── Update the pending payment to PAID ─────────────────
            if ($payment) {
                $payment->update([
                    'mpesa_code'        => $mpesaCode,
                    'amount'            => $amount,
                    'status'            => 'Paid',
                    'callback_received' => true,
                    'payment_date'      => now(),
                    'notes'             => "Auto-recorded via callback. Phone: {$phone}",
                ]);

                Log::info('✅ Payment UPDATED to Paid', [
                    'payment_id' => $payment->id,
                    'mpesa_code' => $mpesaCode,
                    'amount'     => $amount,
                ]);

                $service = $payment->service;

            } else {
                // Fallback — create new payment if pending record not found
                Log::warning("Callback: no pending payment for CheckoutID {$checkoutRequestId}, creating new.");

                $service = Service::where('job_card_no', $accountRef)->first()
                    ?? Service::whereIn('status', ['pending','in_progress'])->latest()->first();

                if (!$service) {
                    Log::error("Callback: no service found. Payment lost! Code: {$mpesaCode}");
                    return $this->accept();
                }

                $payment = Payment::create([
                    'service_id'          => $service->id,
                    'amount'              => $amount,
                    'method'              => 'M-Pesa',
                    'mpesa_code'          => $mpesaCode,
                    'checkout_request_id' => $checkoutRequestId,
                    'callback_received'   => true,
                    'status'              => 'Paid',
                    'payment_date'        => now(),
                    'notes'               => "Auto-recorded via callback. Phone: {$phone}",
                ]);

                Log::info('✅ Payment CREATED from callback', [
                    'payment_id' => $payment->id,
                    'mpesa_code' => $mpesaCode,
                ]);
            }

            // ── Auto-complete service if fully paid ────────────────
            $service->refresh();
            if ($service->balance <= 0 && $service->status !== 'completed') {
                $service->update([
                    'status'       => 'completed',
                    'completed_at' => now(),
                ]);
                Log::info("✅ Service {$service->job_card_no} auto-completed.");
            }

        } catch (\Throwable $e) {
            Log::error('Callback EXCEPTION: ' . $e->getMessage(), [
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);
        }

        return $this->accept();
    }

    private function accept(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'ResultCode' => 0,
            'ResultDesc' => 'Accepted',
        ]);
    }
}
