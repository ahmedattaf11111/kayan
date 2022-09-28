<?php

namespace App\Services\Order;

use App\Constants\OrderStatus;
use App\Repositories\Order\CartRepository;

class CartService
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addToCart($userId, $cartInput)
    {
        $order = $this->cartRepository->getCartStatusOrder($userId, false);
        if (!$order) $order = $this->cartRepository->createOrder($this->getOrderInput($userId));
        $this->cartRepository->createCart($this->getCartInput($order->id, $cartInput));
    }

    public function removeCartItem($userId, $productId, $supplierId, $companyId)
    {
        $order = $this->cartRepository->getCartStatusOrder($userId, true);
        if ($order) {
            $this->cartRepository->removeCartItem($order->id, $productId, $supplierId, $companyId);
            if (count($order->products) == 1) {
                $this->cartRepository->removeOrder($order->id);
            }
        }
    }

    public function getCartStatusOrder($userId)
    {
        return $this->cartRepository->getCartStatusOrder($userId, true);
    }
    public function updateCartQuantity($userId, $cartQuantityInput)
    {
        $order = $this->cartRepository->getCartStatusOrder($userId, false);
        $this->cartRepository->updateCartQuantity($order->id, $cartQuantityInput);
    }
    //Commons
    private function getOrderInput($userId)
    {
        return [
            "user_id" => $userId,
            "order_status" => OrderStatus::CART,
        ];
    }

    private function getCartInput($orderId, &$cartInput)
    {
        $cartInput["order_id"] = $orderId;
        return $cartInput;
    }
}
