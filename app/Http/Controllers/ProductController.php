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
                request()->category_id,
                request()->category_level,
                request()->name,
                request()->effective_material,
                request()->pharmacological_form_id,
                request()->supplier_id,
                request()->discount,
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

    public function getMostPopulars()
    {
        $user = request()->user();
        return $this->productService->getMostPopulars($user ? $user->id : null);
    }

    public function getProductDetails($productId)
    {
        return $this->productService->getProductDetails($productId);
    }

    public function getBoughtProducts()
    {
        $user = request()->user();
        return $this->productService->getBoughtProducts($user ? $user->id : null);
    }

    public function getMainWithSubCategories()
    {
        return $this->productService->getMainWithSubCategories();
    }
}
