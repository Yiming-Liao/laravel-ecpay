<?php

namespace App\Services\Ecpay;

class EcpayQueryService
{
    protected $queryTradeService;
    protected $queryPaymentInfoService;

    public function __construct(QueryTradeService $queryTradeService, QueryPaymentInfoService $queryPaymentInfoService)
    {
        $this->queryTradeService = $queryTradeService;
        $this->queryPaymentInfoService = $queryPaymentInfoService;
    }

    public function ecpayQuery($merchantTradeNo)
    {
        // ✖️ 沒有訂單編號
        if (!$merchantTradeNo) {
            return ['message' => '訂單編號缺失', 'status' => 400];
        }

        // ♻️ 查詢訂單
        $orderInfo = $this->queryTradeService->queryTrade($merchantTradeNo);

        // <---(1) 訂單成立且付款完成--->
        if (isset($orderInfo['TradeStatus']) && $orderInfo['TradeStatus'] === '1') { // ✔️ 回傳 訂單資訊
            return ['message' => '訂單成立且付款完成', 'data' => ['orderInfo' => $orderInfo], 'status' => 200];
        }

        // <---(2) ✖️ 查詢失敗--->    // code:10200047 => Cant not find the trade data.
        elseif (isset($orderInfo['TradeStatus']) && $orderInfo['TradeStatus'] === '-1' || $orderInfo['TradeStatus'] === '10200047') {
            return ['message' => '訂單查詢失敗', 'status' => 404];
        }

        // <---(3) 訂單成立但尚未付款，再次查詢取得付款資料--->
        else {
            // ♻️ 查詢訂單的付款資料 (ATM | CVS | BARCODE)
            $paymentInfo = $this->queryPaymentInfoService->queryPaymentInfo($merchantTradeNo);

            // <---(3-1) 訂單成立但尚未付款--->
            if (isset($paymentInfo['RtnCode']) && $paymentInfo['RtnCode'] !== '-1') { // <---✔️ 回傳 訂單資訊 + 付款資料--->
                return ['message' => '訂單成立但尚未付款', 'data' => ['orderInfo' => $orderInfo, 'paymentInfo' => $paymentInfo], 'status' => 200];
            }

            // <---(3-2) ✖️ 查詢失敗--->
            else {
                return ['message' => '訂單查詢失敗', 'status' => 404];
            }
        }
    }
}
