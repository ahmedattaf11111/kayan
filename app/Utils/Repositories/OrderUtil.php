<?php

namespace App\Utils\Repositories;

use App\Constants\OrderStatus;
use App\Models\Order;

trait OrderUtil
{
    public function getCartStatusOrder($userId, $withProducts)
    {
        return Order::where("user_id", $userId)
            ->when($withProducts, function ($query) {
                $query->with("products");
            })
            ->where("order_status", OrderStatus::CART)
            ->first();
    }
}
