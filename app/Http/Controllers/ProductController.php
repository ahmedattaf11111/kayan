<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProductService;

class ProductController extends Controller
{
    //This controller responsible for products in the website
    private $productService;
    function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getBiggestClientDiscountProducts()
    {
        $user = request()->user();
        return $this->productService
            ->getBiggestClientDiscountProducts(
                request()->category_ids,
                request()->name,
                request()->effective_material,
                request()->pharmacological_form_ids,
                request()->company_ids,
                request()->page_size,
                $user ? $user->id : null
            );
    }

    public function getDeals()
    {
        $user = request()->user();
        return $this->productService->getDeals($user ? $user->id : null);
    }

    public function getBestSellers()
    {
        $user = request()->user();
        return $this->productService->getBestSellers($user ? $user->id : null);
    }


    public function getProductDetails($productId)
    {
        return $this->productService->getProductDetails($productId);
    }

    public function getCategories()
    {
        return $this->productService->getCategories();
    }
}
