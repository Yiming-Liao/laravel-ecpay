<?php

namespace App\Services\Ecpay;

use Ecpay\Sdk\Factories\Factory;
use Illuminate\Support\Facades\Log;
use Exception;

class QueryPaymentInfoService
{
    protected $factory;
    protected $postService;

    public function __construct()
    {
        $this->factory = new Factory([  // 建立 Ecpay 的 Factory
            'hashKey' => config('services.ecpay.hash_key'),
            'hashIv'  => config('services.ecpay.hash_iv'),
        ]);

        $this->postService = $this->factory->create('PostWithCmvVerifiedEncodedStrResponseService');
    }


    public function queryPaymentInfo($merchantTradeNo)
    {
        // 📃 設定參數
        $input = [
            'MerchantID' => config('services.ecpay.merchant_id'), // 從設定檔取 MerchantID
            'MerchantTradeNo' => $merchantTradeNo,                // 訂單編號需為字符串$merchantTradeNo
            'TimeStamp' => time(),                                 // 當前時間戳
        ];

        // 🔗 API 端點: 查詢訂單的付款資料 (ATM | CVS | BARCODE)
        $url = config('services.ecpay.url_QueryPaymentInfo');

        try {
            // ♻️ 發送至綠界: 查詢訂單的付款資料 (ATM | CVS | BARCODE)
            $response = $this->postService->post($input, $url);

            return $response; // ✔️
        }

        // 錯誤處理
        catch (Exception $e) {
            Log::error('錯誤-查詢訂單的付款資料 (EcpayQueryPaymentInfoService.php): ', ['error' => $e->getMessage()]);
            return ['RtnCode' => '-1']; // ✖️
        }
    }
}
