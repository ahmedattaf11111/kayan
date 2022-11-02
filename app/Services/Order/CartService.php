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
        $addedToCart = $this->cartRepository->addedToCart($order->id, $cartInput);
        if (!$addedToCart) {
            $this->cartRepository->createCart($this->getCartInput($order->id, $cartInput));
        }
        return $addedToCart;
    }

    public function removeCartItems($userId, $productId, $supplierId)
    {
        $order = $this->cartRepository->getCartStatusOrder($userId, false);
        if ($order) {
            $this->cartRepository->removeCartItems($order->id, $productId, $supplierId);
            $this->cartRepository->removeOrderIfNoItems($order->id);
        }
    }

    public function getCartStatusOrder($userId)
    {
        return $this->cartRepository->getCartStatusOrder($userId, true);
    }

    public function updateCartQuantity($userId, $cartQuantityInput)
    {
        $this->cartRepository->updateCartQuantity($userId, $cartQuantityInput);
    }

    public function getUserCart($userId)
    {
        return $this->cartRepository->getCartItems($userId);
    }

    public function getCartItemsCount($userId)
    {
        return $this->cartRepository->getCartItemsCount($userId);
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
