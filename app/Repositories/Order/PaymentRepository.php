<?php

namespace App\Repositories\Order;

use App\Commons\Constants\PaymentMethod;
use App\Commons\Constants\PaymentStatus;
use App\Constants\OrderStatus;
use App\Models\Order;
use App\Utils\Repositories\OrderUtil;

class PaymentRepository
{
    use OrderUtil;
    public function cashPayment($userId)
    {
        $order = $this->getCartStatusOrder($userId, false);
        $this->makeOrdercashedPayment($order);
    }
    public function insertOnlinePayment($paymentInput)
    {
        Order::where("id", $paymentInput["order_id"])->update($paymentInput);
    }
    //Commons
    public function makeOrdercashedPayment(&$order)
    {
        $order->order_status = OrderStatus::PENDING;
        $order->payment_status = PaymentStatus::UNPAID;
        $order->payment_method = PaymentMethod::CASH;
        $order->save();
    }
}
