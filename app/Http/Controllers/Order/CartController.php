<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Http\Requests\UpdateCartQuantity;
use App\Services\Order\CartService;

class CartController extends Controller
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->middleware("auth");
    }

    public function addToCart(CartRequest $request)
    {
        if ($this->cartService->addToCart($request->user()->id, $request->validated())) {
            return response()->json(["error" => "Item added to cart before"], 400);
        };
    }

    public function removeCartItems()
    {
        $this->cartService->removeCartItems(
            request()->user()->id,
            request()->product_id,
            request()->supplier_id,
        );
    }

    public function getCartStatusOrder()
    {
        return $this->cartService->getCartStatusOrder(request()->user()->id);
    }

    public function updateCartQuantity(UpdateCartQuantity $request)
    {
        $this->cartService->updateCartQuantity(request()->user()->id, $request->validated());
    }

    public function getUserCart()
    {
        return $this->cartService->getUserCart(request()->user()->id);
    }

    public function getCartItemsCount()
    {
        return $this->cartService->getCartItemsCount(request()->user()->id);
    }
}
