<?php

use App\Http\Controllers\MpesaCallbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| M-Pesa Daraja Callback
|--------------------------------------------------------------------------
| Called automatically by Safaricom after every STK Push.
| Must be PUBLIC — no auth middleware.
| Must be HTTPS — use ngrok for local testing.
|--------------------------------------------------------------------------
*/
Route::post('/mpesa/callback', [MpesaCallbackController::class, 'handle'])
    ->name('mpesa.callback');