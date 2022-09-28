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
    }

    public function addToCart(CartRequest $request)
    {
        $this->cartService->addToCart($request->user()->id, $request->validated());
    }

    public function removeCartItem()
    {
        $this->cartService->removeCartItem(
            request()->user()->id,
            request()->product_id,
            request()->supplier_id,
            request()->company_id
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
}
