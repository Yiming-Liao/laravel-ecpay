<?php

use App\Http\Controllers\Ecpay\EcpayCreateOrderController;
use App\Http\Controllers\Ecpay\EcpayResultController;
use Illuminate\Support\Facades\Route;

Route::view('/', view: 'home')->name('home');

Route::view('/checkout-page', 'checkout')->name('checkout-page');

// 創建訂單
Route::post('/ecpay/checkout', [EcpayCreateOrderController::class, 'checkout'])->name('ecpay.checkout');

// 返回畫面
Route::get('/ecpay/result', [EcpayResultController::class, 'result'])->name('ecpay.result');
