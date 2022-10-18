<?php

namespace App\Repositories;

use App\Constants\OrderStatus;
use App\Models\Category;
use App\Models\Product;

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
        return Product::has("deal")->with("deal")->withCartInfo($userId)->take($limit)->get();
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

    public function getBoughtProducts($userId)
    {
        return Product::with("biggestClientDiscountPrice")
            ->has("biggestClientDiscountPrice")
            ->withCarts($userId)
            ->whereRelation("carts.order", "order_status", OrderStatus::COMPLETED)
            ->get();
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
