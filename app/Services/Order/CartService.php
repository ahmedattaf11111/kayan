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
        $cartItems = $this->cartRepository->getCartItems($userId);
        return $this->sortCartsOfEachItemByDiscount($cartItems);
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
    public function sortCartsOfEachItemByDiscount($cartItems)
    {
        return $cartItems->map(function ($cartItem) {
            $carts = $cartItem->carts->sortByDesc("price.clientDiscount")->values();
            //Idont know why laravel force me to type those 2 ugly lines
            unset($cartItem->carts);
            $cartItem->carts = $carts;
            //Idont know why laravel force me to type those 2 ugly lines
            return $cartItem;
        });
    }
    private function getCartInput($orderId, &$cartInput)
    {
        $cartInput["order_id"] = $orderId;
        return $cartInput;
    }
}
