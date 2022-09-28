<?php

namespace App\Http\Controllers\Order;

use App\Commons\Constants\PaymentMethod;
use App\Commons\Constants\PaymentStatus;
use App\Constants\OrderStatus;
use App\Http\Controllers\Controller;
use App\Services\Order\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function cashPayment()
    {
        $this->paymentService->cashPayment(request()->user()->id);
    }
    public function onlinePayment()
    {
        $callback_success = route('callback_success');
        $callback_error = route('callback_error');
        $user = request()->user();
        $order = $this->paymentService->getCartStatusOrder(request()->user()->id, false);
        $response = $this->pay(
            $order->total_price,
            $user->first_name,
            $user->phone,
            $user->email,
            $order->id,
            "+2",
            $callback_success,
            $callback_error
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
                redirect(env("UI_URL") . "/order-success");
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
                $this->orderRepository->onlinePayment($this->getFailedPaymentInfo($data->Data));
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
    //Commons
    private function getSuccessPaymentInfo($data)
    {
        return [
            "order_id" => $data->CustomerReference,
            "invoice_id" => $data->InvoiceId,
            "transaction_id" => $data->InvoiceTransactions[0]->TransactionId,
            "order_status" => OrderStatus::PENDING,
            "payment_status" => PaymentStatus::PAID,
            "payment_method" => PaymentMethod::ONLINE,
        ];
    }
    private function getFailedPaymentInfo($data)
    {
        return [
            "order_id" => $data->CustomerReference,
            "invoice_id" => $data->InvoiceId,
            "transaction_id" => $data->InvoiceTransactions[0]->TransactionId,
            "order_status" => OrderStatus::FAILD,
            "payment_status" => PaymentStatus::UNPAID,
            "payment_method" => PaymentMethod::ONLINE,
        ];
    }
}
