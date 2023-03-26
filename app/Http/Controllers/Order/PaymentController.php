<?php

namespace App\Http\Controllers\Order;

use App\Constants\OrderStatus;
use App\Constants\PaymentMethod;
use App\Constants\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderFormRequest;
use App\Services\Order\PaymentService;
use App\Utils\Controllers\MyFatoorahUtil;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use MyFatoorahUtil;
    private $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->middleware("auth")->except(["callback_error", "callback_success"]);
    }
    public function cashPayment(OrderFormRequest $request)
    {
        $this->paymentService->cashPayment($request->user()->id, $request->validated());
    }
    public function onlinePayment(OrderFormRequest $request)
    {
        $user = request()->user();
        $this->paymentService->saveOrderForm($user->id, $request->validated());
        $order = $this->paymentService->getCartStatusOrder($user->id, false);
        $totalPrice = $this->paymentService->getTotalPrice($user->id);
        $response = $this->pay(
            $totalPrice,
            $user,
            $order->id,
            "+2",
            env('SUCCESS_CALLBACK_URL'),
            env('ERROR_CALLBACK_URL'),
        );
        if (
            $order
            && isset($response->IsSuccess)
            && $response->IsSuccess == 'true'
        ) {
            return response()->json(['url' => $response->Data->InvoiceURL], 200);
        }
        return response()->json(['message' => $response], 400);
    }
    public function callback_success(Request $request)
    {
        if (isset($request->paymentId)) {
            $data = $this->callAPI("/v2/GetPaymentStatus", $this->getPostFields($request->paymentId));
            if (
                isset($data->Data->CustomerReference)
                && collect($data->Data->InvoiceTransactions)->last()->TransactionStatus == "Succss"
            ) {
                $this->paymentService->onlinePayment($this->getSuccessPaymentInfo($data->Data));
                return redirect(env("UI_URL") . "/order-success");
            }
        }
    }
    public function callback_error(Request $request)
    {
        if (isset($request['paymentId'])) {
            $data = $this->callAPI("/v2/GetPaymentStatus", $this->getPostFields($request->paymentId));
            if (
                isset($data->Data->CustomerReference)
                && collect($data->Data->InvoiceTransactions)
                ->last()->TransactionStatus == "Failed"
            ) {
                $this->paymentService->onlinePayment($this->getFailedPaymentInfo($data->Data));
                return redirect(env("UI_URL") . "/order-error");
            }
        }
    }
    //Commons
    private function getPostFields($paymentId)
    {
        return [
            'Key' => $paymentId,
            'KeyType' => 'PaymentId',
        ];
    }
    private function getSuccessPaymentInfo($data)
    {
        return [
            "id" => $data->CustomerReference,
            "invoice_id" => $data->InvoiceId,
            "transaction_id" =>  0,
            "order_status" => OrderStatus::PENDING,
            "payment_status" => PaymentStatus::PAID,
            "payment_method" => PaymentMethod::ONLINE,
        ];
    }
    private function getFailedPaymentInfo($data)
    {
        return [
            "id" => $data->CustomerReference,
            "invoice_id" => $data->InvoiceId,
            "transaction_id" => $data->InvoiceTransactions[0]->TransactionId,
            "order_status" => OrderStatus::FAILD,
            "payment_status" => PaymentStatus::UNPAID,
            "payment_method" => PaymentMethod::ONLINE,
        ];
    }
}
