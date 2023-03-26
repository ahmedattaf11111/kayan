<?php

namespace App\Utils\Models;

use App\Constants\OrderStatus;
use App\Models\Order;

trait ProductScope
{

    public function scopeSearchByName($query, $name)
    {
        return $query->when($name, function ($query) use ($name) {
            $query->where("name", "like", "%$name%")->orWhere("name_e", "like", "%$name%");
        });
    }

    public function scopeSearchByEffectiveMaterial($query, $effectiveMaterial)
    {
        return $query->when($effectiveMaterial, function ($query) use ($effectiveMaterial) {
            $query->where("effective_material", $effectiveMaterial);
        });
    }

    public function scopeSearchByPharmacistFormId($query, $pharmacologicalFormId)
    {
        return $query->when($pharmacologicalFormId, function ($query) use ($pharmacologicalFormId) {
            $query->whereIn("pharmacist_form_id", $pharmacologicalFormId);
        });
    }

    public function scopeSearchByCompanyId($query, $companyId)
    {
        return $query->when($companyId, function ($query) use ($companyId) {
            $query->whereIn("company_id", $companyId);
        });
    }

    public function scopeSearchByCategory($query, $categoryId)
    {
        return $query->when($categoryId, function ($query) use ($categoryId) {
            $query->whereIn("category_id", $categoryId);
        });
    }

    public function scopeWithCarts($query, $userId)
    {
        $query->when($userId, function ($query) use ($userId) {
            $query->with(["carts" => function ($query) use ($userId) {
                $query->whereHas("order", function ($query) use ($userId) {
                    $query->where("user_id", $userId)->where("order_status", OrderStatus::CART);
                });
            }]);
        });
    }

    public function scopeWithWhereHasCarts($query, $userId)
    {
        return $query->whereHas("carts", function ($query) use ($userId) {
            $query->whereHas("order", function ($query) use ($userId) {
                $query->where("user_id", $userId)->where("order_status", OrderStatus::CART);
            });
        })->with(["carts" => function ($query) use ($userId) {
            $query->whereHas("order", function ($query) use ($userId) {
                $query
                    ->where("user_id", $userId)
                    ->where("order_status", OrderStatus::CART);
            });
        }]);
    }

    public function scopeBestSeller($query)
    {
        $orderIds = Order::where("order_status", "<>", OrderStatus::CART)->pluck("id");
        return $query->join("cart_items", "products.id", "cart_items.product_id")
            ->whereIn("cart_items.order_id", $orderIds)
            ->selectRaw("products.*,supplier_id,sum(cart_items.quantity) as sum")
            ->groupBy("cart_items.product_id", "cart_items.supplier_id")
            ->orderByDesc("sum");
    }

    public function scopeMostPopular($query)
    {
        $orderIds = Order::where("order_status", "<>", OrderStatus::CART)->pluck("id");
        return $query->join("cart_items", "products.id", "cart_items.product_id")
            ->whereIn("cart_items.order_id", $orderIds)
            ->selectRaw("products.*,supplier_id,count(*) as count")
            ->groupBy("cart_items.product_id", "cart_items.supplier_id")
            ->orderByDesc("count");
    }
}
