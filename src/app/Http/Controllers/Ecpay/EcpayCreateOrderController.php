<?php

namespace App\Http\Controllers\Ecpay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ecpay\EcpayCreateOrderService;
use Illuminate\Support\Facades\Log;

class EcpayCreateOrderController extends Controller
{
    protected $ecpayService;

    public function __construct(EcpayCreateOrderService $ecpayService)
    {
        $this->ecpayService = $ecpayService;
    }

    // [POST] 處理前端的 checkout 請求
    public function checkout(Request $request)
    {
        // 接收前端傳來的資料
        $amount = $request->input('amount'); // 取得金額
        $itemName = $request->input('item_name'); // 取得商品名稱
        $tradeDesc = '交易描述範例'; // 交易描述
        // $returnUrl = route('payment.callback'); // 綠界通知的回調URL
        $returnUrl = env('APP_URL') . "/api/ecpay/callback"; // 設定綠界通知的回調 URL
        $clientBackURL =  route('ecpay.result'); // 設定綠界通知的回調 URL

        // 生成自動提交表單 HTML
        $response = $this->ecpayService->createOrder($amount, $itemName, $tradeDesc, $returnUrl, $clientBackURL);



        // 將查詢結果記錄到日誌中  // 存入資料庫
        Log::info(message: '|===>>>|');
        Log::info('已送出訂單資訊: ', $response['input']);
        Log::info('|===>>>|');



        // 回傳生成的 HTML 表單，讓前端自動提交表單連線至綠界
        return response($response['form']);
    }
}
