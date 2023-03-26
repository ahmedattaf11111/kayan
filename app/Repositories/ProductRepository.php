<?php

namespace App\Repositories;

use App\Constants\OrderStatus;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Product;

class ProductRepository
{
    public function getBiggestClientDiscountProducts(
        $categoryIds,
        $name,
        $effectiveMaterial,
        $pharmacologicalFormIds,
        $companyIds,
        $pageSize,
        $userId
    ) {
        return Product::with("biggestClientDiscountPrice.supplier")
            ->has("biggestClientDiscountPrice")
            ->withCarts($userId)
            ->searchByName($name)
            ->searchByEffectiveMaterial($effectiveMaterial)
            ->searchByPharmacistFormId($pharmacologicalFormIds)
            ->searchByCompanyId($companyIds)
            ->searchByCategory($categoryIds)
            ->paginate($pageSize);
    }

    public function getCategories()
    {
        return Category::get();
    }

    public function getDealProducts($userId, $limit)
    {
        return Product::has("dealPrice")->with("dealPrice.supplier")->withCarts($userId)->take($limit)->get();
    }
    public function getDealSettings()
    {
        return Deal::first();
    }
    public function getBestSellerProducts($userId,$limit)
    {
        return Product::bestSeller()->withCarts($userId)->take($limit)->get()->each->append("price");
    }
    public function getProductDetails($productId)
    {
        $user = request()->user();
        return Product::with(["prices" => function ($query) use ($user, $productId) {
            $query->with(["supplier" => function ($query) use ($user, $productId) {
                $this->withCart($query, $user, $productId);
            }])->orderBy("client_discount", 'desc');
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
}
