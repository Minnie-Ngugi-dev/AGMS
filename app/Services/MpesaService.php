<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaService
{
    private string $consumerKey;
    private string $consumerSecret;
    private string $shortcode;
    private string $passkey;
    private string $baseUrl;

    public function __construct()
    {
        $this->consumerKey    = config('mpesa.consumer_key');
        $this->consumerSecret = config('mpesa.consumer_secret');
        $this->shortcode      = config('mpesa.shortcode');
        $this->passkey        = config('mpesa.passkey');
        $this->baseUrl        = config('mpesa.env') === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    /**
     * Get OAuth access token
     */
    public function getAccessToken(): ?string
    {
        try {
            $response = Http::timeout(30)
                ->withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('M-Pesa token failed', [
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('M-Pesa token exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Initiate STK Push
     */
    public function stkPush(string $phone, float $amount, string $accountRef, string $description): array
    {
        try {
            $token = $this->getAccessToken();

            if (!$token) {
                return [
                    'success'      => false,
                    'errorMessage' => 'Could not authenticate with M-Pesa. Check your credentials in .env',
                ];
            }

            $timestamp = now()->format('YmdHis');
            $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);
            $phone     = $this->formatPhone($phone);

            Log::info('M-Pesa STK Push', [
                'phone'  => $phone,
                'amount' => $amount,
                'ref'    => $accountRef,
            ]);

            $response = Http::timeout(30)
                ->withToken($token)
                ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", [
                    'BusinessShortCode' => $this->shortcode,
                    'Password'          => $password,
                    'Timestamp'         => $timestamp,
                    'TransactionType'   => 'CustomerPayBillOnline',
                    'Amount'            => (int) ceil($amount),
                    'PartyA'            => $phone,
                    'PartyB'            => $this->shortcode,
                    'PhoneNumber'       => $phone,
                    'CallBackURL'       => config('mpesa.callback_url'),
                    'AccountReference'  => substr($accountRef, 0, 12),
                    'TransactionDesc'   => substr($description, 0, 13),
                ]);

            Log::info('M-Pesa STK Response', $response->json() ?? []);

            return $response->json() ?? ['errorMessage' => 'Empty response from Safaricom'];

        } catch (\Exception $e) {
            Log::error('M-Pesa STK exception: ' . $e->getMessage());
            return [
                'success'      => false,
                'errorMessage' => 'Network error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Query STK Push status
     */
    public function stkQuery(string $checkoutRequestId): array
    {
        try {
            $token = $this->getAccessToken();

            if (!$token) {
                return ['ResultCode' => 1, 'ResultDesc' => 'Authentication failed'];
            }

            $timestamp = now()->format('YmdHis');
            $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);

            $response = Http::timeout(30)
                ->withToken($token)
                ->post("{$this->baseUrl}/mpesa/stkpushquery/v1/query", [
                    'BusinessShortCode' => $this->shortcode,
                    'Password'          => $password,
                    'Timestamp'         => $timestamp,
                    'CheckoutRequestID' => $checkoutRequestId,
                ]);

            Log::info('M-Pesa Query Response', $response->json() ?? []);

            return $response->json() ?? ['ResultCode' => 1, 'ResultDesc' => 'No response'];

        } catch (\Exception $e) {
            Log::error('M-Pesa Query exception: ' . $e->getMessage());
            return ['ResultCode' => 1, 'ResultDesc' => $e->getMessage()];
        }
    }

    /**
     * Format ANY phone number to 254XXXXXXXXX
     *
     * Accepts ALL these formats:
     * 0712345678       → 254712345678
     * 0112345678       → 254112345678  (Safaricom 01XX)
     * 712345678        → 254712345678
     * 112345678        → 254112345678
     * +254712345678    → 254712345678
     * +254112345678    → 254112345678
     * 254712345678     → 254712345678  (already correct)
     * 254112345678     → 254112345678
     * 07 1234 5678     → 254712345678  (with spaces)
     * 0712-345-678     → 254712345678  (with dashes)
     */
    public function formatPhone(string $phone): string
    {
        // Remove ALL non-numeric characters (spaces, dashes, brackets, +)
        $phone = preg_replace('/\D/', '', $phone);

        // Remove leading zeros if more than one (e.g. 00254)
        $phone = ltrim($phone, '0');

        // If starts with 254 and is 12 digits → already correct
        if (str_starts_with($phone, '254') && strlen($phone) === 12) {
            return $phone;
        }

        // If 9 digits (e.g. 712345678 or 112345678) → prepend 254
        if (strlen($phone) === 9) {
            return '254' . $phone;
        }

        // If 10 digits starting with 0 was stripped to 9 → handled above
        // If 10 digits (0712345678 stripped to 712345678) → won't happen since we ltrimmed 0
        // Catch: if originally 07XXXXXXXX → after ltrim becomes 7XXXXXXXX (9 digits) ✓
        // Catch: if originally 01XXXXXXXX → after ltrim becomes 1XXXXXXXX (9 digits) ✓

        // Fallback: prepend 254 whatever is left
        return '254' . $phone;
    }

    /**
     * Validate a formatted phone number
     * Accepts both Safaricom 07XX and 01XX series
     */
    public function isValidPhone(string $phone): bool
    {
        $formatted = $this->formatPhone($phone);

        // 254 7XX XXX XXX (Safaricom 07XX series)
        // 254 1XX XXX XXX (Safaricom 01XX series)
        // Both are valid Safaricom M-Pesa numbers
        return preg_match('/^254[71]\d{8}$/', $formatted) === 1;
    }

    /**
     * Get a human-readable version of the formatted number
     */
    public function displayPhone(string $phone): string
    {
        $formatted = $this->formatPhone($phone);
        // Convert 254712345678 → 0712 345 678
        if (strlen($formatted) === 12 && str_starts_with($formatted, '254')) {
            $local = '0' . substr($formatted, 3);
            return substr($local, 0, 4) . ' ' . substr($local, 4, 3) . ' ' . substr($local, 7);
        }
        return $phone;
    }
}