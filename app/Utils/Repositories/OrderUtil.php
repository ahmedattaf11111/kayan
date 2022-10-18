<?php

namespace App\Utils\Repositories;

use App\Constants\OrderStatus;
use App\Models\Order;
use App\Models\Product;

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
    public function getCartItems($userId)
    {
        return Product::withWhereHascarts($userId)->get();
    }
}
