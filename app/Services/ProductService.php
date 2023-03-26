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
        $categoryIds,
        $name,
        $effectiveMaterial,
        $pharmacologicalFormIds,
        $companyIds,
        $pageSize,
        $userId
    ) {
        $productsPage = $this->productRepository
            ->getBiggestClientDiscountProducts(
                $categoryIds,
                $name,
                $effectiveMaterial,
                $pharmacologicalFormIds,
                $companyIds,
                $pageSize,
                $userId
            );
        return $this->mapProducts($productsPage, "biggestClientDiscountPrice");
    }
    public function getCategories()
    {
        return $this->productRepository->getCategories();
    }
    public function getDeals($userId)
    {
        $deal = $this->productRepository->getDealSettings();
        $dealProducts = $this->productRepository->getDealProducts($userId, $deal["limit"]);
        return ["deal_settings" => $deal, "products" => $this->mapProducts($dealProducts, "dealPrice")];
    }
    public function getBestSellers($userId)
    {
        $bestSellerProducts = $this->productRepository->getBestSellerProducts($userId,10);
        return $this->mapProducts($bestSellerProducts, "price");
    }
    public function getProductDetails($productId)
    {
        return $this->productRepository->getProductDetails($productId);
    }
    //Commons
    private function mapProducts(&$products, $priceType)
    {
        $products->transform(function ($product) use ($priceType) {
            if ($product->relationLoaded("carts")) {
                $product->cart_info = $product->carts->filter(function ($cart_info) use ($product, $priceType) {
                    return $cart_info->supplier_id == $product[$priceType]->supplier_id;
                })->first();
                unset($product->carts);
            }
            return $product;
        });
        return $products;
    }
}
