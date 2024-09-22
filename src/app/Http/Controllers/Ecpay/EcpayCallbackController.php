<?php

namespace App\Http\Controllers\Ecpay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ecpay\Sdk\Services\CheckMacValueService;
use Illuminate\Support\Facades\Log;

class EcpayCallbackController extends Controller
{
    // 接收綠界的回調資料
    public function callback(Request $request)
    {
        $response = $request->all(); // 取得綠界回傳的參數

        $checkMacValueService = new CheckMacValueService( // 創建實例
            config('services.ecpay.hash_key'),
            config('services.ecpay.hash_iv'),
            CheckMacValueService::METHOD_SHA256
        );

        // 確認回傳資料中是否包含商戶交易編號
        if (!isset($response['MerchantTradeNo'])) {
            return "0|缺少商戶交易編號";
        }

        // 🔒 檢查碼相符，回傳 1|OK
        if ($checkMacValueService->verify($response)) {
            // 📝 Log
            Log::info('|');
            Log::info('付款完成收到回傳資料: ', $response);
            Log::info('|');

            // 這裡撰寫資料庫邏輯

            return "1|OK";
        }
        // 🔒 檢查碼不相符，回傳錯誤訊息
        else {
            // 📝 Log
            Log::error('檢查碼不正確', ['response' => $response]);
            return "0|CheckMacValue不正確";
        }
    }
}
