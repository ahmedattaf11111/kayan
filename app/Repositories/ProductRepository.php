<?php

namespace App\Repositories;

use App\Constants\OrderStatus;
use App\Models\AlsoBought;
use App\Models\BestSeller;
use App\Models\Category;
use App\Models\Deal;
use App\Models\MostPopular;
use App\Models\Product;
use App\Models\SubCategory;

class ProductRepository
{
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
        return Product::with("biggestClientDiscountPrice")
            ->has("biggestClientDiscountPrice")
            ->withCarts($userId)
            ->searchByName($name)
            ->searchByEffectiveMaterial($effectiveMaterial)
            ->searchByPharmacistFormId($pharmacologicalFormId)
            ->searchBySupplierId($supplierId)
            ->searchByDiscount($discount)
            ->searchByCategory($categoryId, $categoryLevel)
            ->paginate($pageSize);
    }

    public function getMainWithSubCategories()
    {
        return Category::with(["subCategories" => function ($query) {
            $query->where("status", 1);
        }, "media"])->where("status", 1)->get();
    }

    public function getDealProducts($userId, $limit)
    {
        return Product::has("dealPrice")->with("dealPrice")->withCarts($userId)->take($limit)->get();
    }
    public function getDealSettings()
    {
        return Deal::first();
    }
    public function getManualBestSellerProducts($userId)
    {
        return Product::has("bestSellerProducts")->withCarts($userId)->get()->each->append("price");
    }
    public function getActualBestSellerProducts($userId)
    {
        return Product::bestSeller()->withCarts($userId)->get()->each->append("price");
    }
    public function getActualMostPopularProducts($userId)
    {
        return Product::mostPopular()->withCarts($userId)->get()->each->append("price");
    }
    public function getManualMostPopularProducts($userId)
    {
        return Product::has("mostPopularProducts")->withCarts($userId)->get()->each->append("price");
    }
    public function getBestSellerSettings()
    {
        return BestSeller::first();
    }
    public function getActualAlsoBoughtProducts($userId)
    {
        return $this->getActualMostPopularProducts($userId);
    }
    public function getManualAlsoBoughtProducts($userId)
    {
        return Product::has("alsoBougtProducts")->withCarts($userId)->get()->each->append("price");
    }
    public function getAlsoBoughtSettings()
    {
        return AlsoBought::first();
    }

    public function getMostPopularSettings()
    {
        return MostPopular::first();
    }
    public function getProductDetails($productId)
    {
        $user = request()->user();
        return Product::with(["prices" => function ($query) use ($user, $productId) {
            $query->with(["supplier" => function ($query) use ($user, $productId) {
                $this->withCart($query, $user, $productId);
            }])->orderBy("clientDiscount", 'desc');
        }])->find($productId);
    }

    //Commons
    private function withCart($query, $user, $productId)
    {
        $query->when($user, function ($query) use ($user, $productId) {
            $query->with(["cart_info" => function ($query) use ($user, $productId) {
                $this->whereCartOfUserAndProduct($query, $user->id, $productId);
            }]);
        });
    }

    private function whereCartOfUserAndProduct($query, $userId, $productId)
    {
        $query->whereHas("order", function ($query) use ($userId) {
            $query->where("user_id", $userId)->where("order_status", OrderStatus::CART);
        })->where("product_id", $productId);
    }

    public function getSubCategories()
    {
        return SubCategory::with("products")->get();
    }
}
