<?php

namespace App\Http\Controllers\Ecpay;

use App\Http\Controllers\Controller;
use App\Services\Ecpay\EcpayQueryService;
use Illuminate\Http\Request;

class EcpayQueryController extends Controller
{
    protected $ecpayQueryService;

    public function __construct(EcpayQueryService $ecpayQueryService)
    {
        $this->ecpayQueryService = $ecpayQueryService;
    }

    public function showOrderStatus(Request $request)
    {
        $merchantTradeNo = $request->input('merchant_trade_no', '1726976759'); // 可以從請求中取得或給個預設值

        // ♻️ 調用 EcpayQueryService 的查詢方法
        $result = $this->ecpayQueryService->ecpayQuery($merchantTradeNo);

        // ✖️ 失敗 非 200 OK
        if ($result['status'] !== 200) {
            return response()->json(
                [
                    'message' => $result['message'],
                ],
                $result['status']
            );
        }

        // ✔️ 成功 回傳 JSON response
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data'] ?? null,
            ],
            $result['status']
        );
    }
}
