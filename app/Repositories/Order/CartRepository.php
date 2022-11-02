<?php

namespace App\Repositories\Order;

use App\Constants\OrderStatus;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Utils\Repositories\OrderUtil;

class CartRepository
{
    use OrderUtil;
    public function createCart($cartInput)
    {
        return CartItem::create($cartInput);
    }

    public function createOrder($orderInput)
    {
        return Order::create($orderInput);
    }

    public function removeCartItems($orderId, $productId, $supplierId)
    {
        CartItem::where("order_id", $orderId)
            ->where("product_id", $productId)
            ->when($supplierId, function ($query) use ($supplierId) {
                $query->where("supplier_id", $supplierId);
            })
            ->delete();
    }
    public function addedToCart($orderId, $cartInput)
    {
        return CartItem::where("order_id", $orderId)
            ->where("supplier_id", $cartInput["supplier_id"])
            ->where("product_id", $cartInput["product_id"])->count() > 0;
    }
    public function removeOrderIfNoItems($id)
    {
        Order::doesntHave("products")->where("id", $id)->delete();
    }
    public function updateCartQuantity($userId, $cartQuantityInput)
    {
        CartItem::whereHas("order", function ($query) use ($userId) {
            $query->where("user_id", $userId)->where("order_status", OrderStatus::CART);
        })
            ->where("product_id", $cartQuantityInput["product_id"])
            ->when(isset($cartQuantityInput["supplier_id"]), function ($query) use ($cartQuantityInput) {
                $query->where("supplier_id", $cartQuantityInput["supplier_id"]);
            })
            ->update(["quantity" => $cartQuantityInput["quantity"]]);
    }

    public function getCartItemsCount($userId)
    {
        return Product::withWhereHascarts($userId)->count();
    }
}
