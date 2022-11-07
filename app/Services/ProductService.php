<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
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
        return $this->mapProducts($productsPage, "biggestClientDiscountPrice");
    }
    public function getMainWithSubCategories()
    {
        return $this->productRepository->getMainWithSubCategories();
    }
    public function getDeals($userId)
    {
        $deal = $this->productRepository->getDealSettings();
        $dealProducts = $this->productRepository->getDealProducts($userId, $deal["limit"]);
        return ["deal_settings" => $deal, "products" => $this->mapProducts($dealProducts, "dealPrice")];
    }
    public function getBestSellers($userId)
    {
        $bestSellerSettings = $this->productRepository->getBestSellerSettings();
        $mergedProducts = [];
        if ($bestSellerSettings) {
            $manualBestSellerProducts = $this->productRepository->getManualBestSellerProducts($userId);
            $actualBestSellerProducts = $this->productRepository->getActualBestSellerProducts($userId);
            $mergedProducts = $actualBestSellerProducts->concat($manualBestSellerProducts)
                ->take($bestSellerSettings->limit);
            $mergedProducts = $this->mapProducts($mergedProducts, "price");
        }
        return ["best_seller_settings" => $bestSellerSettings, "products" => $mergedProducts];
    }
    public function getMostPopulars($userId)
    {
        $mostPopularSettings = $this->productRepository->getMostPopularSettings();
        $mergedProducts = [];
        if ($mostPopularSettings) {
            $manualMostPopularProducts = $this->productRepository->getManualMostPopularProducts($userId);
            $actualMostPopularProducts = $this->productRepository->getActualMostPopularProducts($userId);
            $mergedProducts = $actualMostPopularProducts->concat($manualMostPopularProducts)
                ->take($mostPopularSettings->limit);
            $mergedProducts = $this->mapProducts($mergedProducts, "price");
        }
        return ["most_popular_settings" => $mostPopularSettings, "products" => $mergedProducts];
    }
    public function getProductDetails($productId)
    {
        return $this->productRepository->getProductDetails($productId);
    }

    public function getSubCategories()
    {
        return $this->productRepository->getSubCategories();
    }

    public function getBoughtProducts($userId)
    {
        $alsoBoughtSettings = $this->productRepository->getAlsoBoughtSettings();
        $mergedProducts = [];
        if ($alsoBoughtSettings) {
            $manualAlsoBoughtProducts = $this->productRepository->getManualAlsoBoughtProducts($userId);
            $actualAlsoBoughtProducts = $this->productRepository->getActualAlsoBoughtProducts($userId);
            $mergedProducts = $actualAlsoBoughtProducts->concat($manualAlsoBoughtProducts)
                ->take($alsoBoughtSettings->limit);
            $mergedProducts = $this->mapProducts($mergedProducts, "price");
        }
        return ["also_bought_settings" => $alsoBoughtSettings, "products" => $mergedProducts];
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
