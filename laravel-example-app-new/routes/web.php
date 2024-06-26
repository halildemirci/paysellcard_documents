<?php

use App\Http\Controllers\PaymentRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', [PaymentRequest::class, 'index']);
Route::post('/payment', [PaymentRequest::class, 'Payment']);
