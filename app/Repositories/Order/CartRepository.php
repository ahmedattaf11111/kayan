<?php

namespace App\Repositories\Order;

use App\Models\CartItem;
use App\Models\Order;
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
    public function removeCartItem($orderId, $productId, $supplierId, $companyId)
    {
        CartItem::where("order_id", $orderId)->where("product_id", $productId)
            ->when($supplierId, function ($query) use ($supplierId) {
                $query->where("supplier_id", $supplierId);
            })
            ->when($companyId, function ($query) use ($companyId) {
                $query->where("company_id", $companyId);
            })
            ->delete();
    }

    public function removeOrder($id)
    {
        $order = Order::find($id);
        $order->delete();
    }

    public function updateCartQuantity($orderId, $cartQuantityInput)
    {
        CartItem::whereRelation("order", "id", $orderId)
            ->where("product_id", $cartQuantityInput["product_id"])
            ->when(isset($cartQuantityInput["company_id"]), function ($query) use ($cartQuantityInput) {
                $query->where("company_id", $cartQuantityInput["company_id"]);
            })
            ->when(isset($cartQuantityInput["supplier_id"]), function ($query) use ($cartQuantityInput) {
                $query->where("supplier_id", $cartQuantityInput["supplier_id"]);
            })->update(["quantity" => $cartQuantityInput["quantity"]]);
    }
}
