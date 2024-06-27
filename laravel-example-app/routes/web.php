<?php

use App\Http\Controllers\PaymentRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaymentRequest::class, 'index']);
Route::post('/payment', [PaymentRequest::class, 'Payment']);
Route::post('/callback', [PaymentRequest::class, 'Callback']);

Route::get('/failed', function () {
    return "Payment Failed!";
});

Route::get('/success', function () {
    return "Payment Success!";
});
