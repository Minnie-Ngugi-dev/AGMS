<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'service_id',
        'amount',
        'method',
        'mpesa_code',
        'checkout_request_id',
        'callback_received',
        'status',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'payment_date'      => 'datetime',
        'callback_received' => 'boolean',
        'amount'            => 'decimal:2',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
// ```

// ---

// ## The exact flow now
// ```
// 1. Cashier clicks Send STK Push
//          ↓
// 2. Safaricom sends PIN prompt to customer phone
//          ↓
// 3. Frontend shows "Payment request sent. Please enter PIN."
//    — PENDING payment created in DB with CheckoutRequestID
//          ↓
// 4. Frontend polls /mpesa/query every 5 seconds
//    — checks if callback_received = true on that payment
//          ↓
// 5. Customer enters PIN on phone
//          ↓
// 6. Safaricom hits your callback URL
//    — finds payment by CheckoutRequestID
//    — updates mpesa_code, amount, status = Paid
//    — sets callback_received = true
//          ↓
// 7. Next poll detects callback_received = true + status = Paid
//          ↓
// 8. Success modal shows with REAL M-Pesa code from Safaricom