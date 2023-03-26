<?php

namespace App\Repositories\Order;

use App\Constants\OrderStatus;
use App\Constants\PaymentMethod;
use App\Constants\PaymentStatus;
use App\Models\Client;
use App\Models\Order;
use App\Utils\Repositories\OrderUtil;
use Carbon\Carbon;

class PaymentRepository
{
    use OrderUtil;
    public function cashPayment($userId)
    {
        $order = $this->getCartStatusOrder($userId, false);
        $this->makeOrdercashedPayment($order);
    }
    public function onlinePayment($paymentInput)
    {
        Order::where("id", $paymentInput["id"])->update($paymentInput);
    }
    public function saveOrderForm($userId, $orderInput)
    {
        Client::whereRelation("user", "id", $userId)->update($orderInput);
    }
    public function getTotalPrice($userId)
    {
        $totalPrice = 0;
        $cartProducts = $this->getCartItems($userId);
        foreach ($cartProducts as $product) {
            foreach ($product->carts as $cart) {
                $totalPrice += $this->getClientPrice($product->public_price, $this->getPrice($cart)->client_discount)
                    * $cart->quantity;
            }
        }
        return $totalPrice;
    }
    //Commons
    private function getPrice($cart)
    {
        return $cart->dealPrice ?  $cart->dealPrice : $cart->price;
    }
    public function makeOrdercashedPayment(&$order)
    {
        $order->order_status = OrderStatus::PENDING;
        $order->payment_status = PaymentStatus::UNPAID;
        $order->payment_method = PaymentMethod::CASH;
        $order->created_at = Carbon::now();
        $order->save();
    }
    public function getClientPrice($publicPrice, $clientDiscount)
    {
        $discountVal = $publicPrice *
            ($clientDiscount / 100);
        return $publicPrice - $discountVal;
    }
}
