<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getBiggestClientDiscountProducts(
        $categoryId,
        $categoryLevel,
        $name,
        $effectiveMaterial,
        $pharmacologicalFormId,
        $supplierId,
        $discount,
        $pageSize,
        $userId
    ) {
        $productsPage = $this->productRepository
            ->getBiggestClientDiscountProducts(
                $categoryId,
                $categoryLevel,
                $name,
                $effectiveMaterial,
                $pharmacologicalFormId,
                $supplierId,
                $discount,
                $pageSize,
                $userId
            );
        return $this->mapProductsPage($productsPage);
    }
    public function getMainWithSubCategories()
    {
        return $this->productRepository->getMainWithSubCategories();
    }
    public function getDealProducts($userId, $limit)
    {
        return $this->productRepository->getDealProducts($userId, $limit);
    }
    public function getProductDetails($productId)
    {
        return $this->productRepository->getProductDetails($productId);
    }
    public function getBoughtProducts($userId)
    {
        $boughtProducts = $this->productRepository->getBoughtProducts($userId);
        return $this->mapProductsPage($boughtProducts);
    }

    //Commons
    private function mapProductsPage(&$productsPage)
    {
        $productsPage->transform(function ($product) {
            if ($product->relationLoaded("carts")) {
                $product->cart_info = $product->carts->filter(function ($cart_info) use ($product) {
                    return $cart_info->supplier_id == $product->biggestClientDiscountPrice->supplier_id;
                })->first();
                $product->carts_length = $product->carts->count();
                unset($product->carts);
            }
            return $product;
        });
        return $productsPage;
    }
}
