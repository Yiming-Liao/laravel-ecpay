<?php

namespace App\Http\Controllers\Ecpay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EcpayResultController extends Controller
{
    // [GET] 處理支付結果的顯示
    public function result()
    {

        // 跳轉至創建的訂單畫面

        return view('payment-result', ['message' => '支付結果處理']);
    }
}
