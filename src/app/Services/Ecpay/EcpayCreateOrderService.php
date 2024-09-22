<?php

namespace App\Services\Ecpay;

use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\UrlService;

class EcpayCreateOrderService
{
    protected $factory;
    protected $autoSubmitFormService;

    public function __construct()
    {
        $this->factory = new Factory([  // 建立 Ecpay 的 Factory
            'hashKey' => config('services.ecpay.hash_key'),
            'hashIv'  => config(key: 'services.ecpay.hash_iv'),
        ]);

        $this->autoSubmitFormService = $this->factory->create('AutoSubmitFormWithCmvService');
    }


    public function createOrder($amount, $itemName, $tradeDesc, $returnURL, $clientBackURL)
    {
        // 📃 設定參數
        $input = [
            /*----- 預設 -----*/
            'PaymentType' => 'aio',  // [Default] 綠界 All in one 全方位金流付款
            'EncryptType' => 1,  // [Default] 
            'MerchantTradeDate' => date('Y/m/d H:i:s'),  // [Default] 交易日期
            'MerchantTradeNo' => '' . time(),  // 通常使用時間戳以避免重複

            /*----- 付款方式 -----*/
            'ChoosePayment' => 'ALL',  // [Default] 
            'IgnorePayment' => 'BARCODE', // 隱藏不需要的付款方式
            // 'IgnorePayment' => 'ATM#CVS#BARCODE#WebATM', // 隱藏不需要的付款方式

            /*----- 訂單資料 -----*/
            'MerchantID' => config('services.ecpay.merchant_id'),  // 商戶 ID
            'ItemName' => $itemName, // 商品名稱
            'TotalAmount' => $amount, // 總金額
            'TradeDesc' => UrlService::ecpayUrlEncode($tradeDesc), // 交易描述，必須 URL 編碼

            /*----- 導向路由 -----*/
            'ReturnURL' => $returnURL, // 綠界回傳結果給後端伺服器的 URL
            'ClientBackURL' => $clientBackURL, // 付款完成後，出現的返回按鈕 URL
            // 'OrderResultURL' => $clientBackURL, // 付款完成後，直接跳轉到該 URL  // 會使 ClientBackURL 無用
        ];

        // 🔗 API 端點: 此 URL 為自動提交表單 form 的 action=""
        $action = config('services.ecpay.url_AioCheckOut');

        // 生成自動提交表單 HTML
        $form = $this->autoSubmitFormService->generate($input, $action);

        return [
            'form' => $form,
            'input' => $input,
        ];
    }
}
