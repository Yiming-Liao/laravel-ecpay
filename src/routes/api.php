<?php

use App\Http\Controllers\Ecpay\EcpayCallbackController;
use App\Http\Controllers\Ecpay\EcpayQueryController;
use Illuminate\Support\Facades\Route;

// 接收綠界Callback
Route::post('/ecpay/callback', [EcpayCallbackController::class, 'callback'])->name('ecpay.callback');

// 綠界 查詢訂單
Route::post('/ecpay/query', [EcpayQueryController::class, 'showOrderStatus'])->name('ecpay.query');
