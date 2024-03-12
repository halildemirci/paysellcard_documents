<?php

use App\Http\Controllers\PaymentRequest;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('payment_page/payment');
// });

Route::get('/', [PaymentRequest::class, 'index']);
Route::post('/payment', [PaymentRequest::class, 'Payment']);
