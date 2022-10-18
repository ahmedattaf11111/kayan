<?php

namespace App\Utils\Models;

use App\Constants\OrderStatus;

trait ProductScope
{

    public function scopeSearchByName($query, $name)
    {
        return $query->when($name, function ($query) use ($name) {
            $query->where("nameAr", "like", "%$name%")->orWhere("nameEn", "like", "%$name%");
        });
    }

    public function scopeSearchByEffectiveMaterial($query, $effectiveMaterial)
    {
        return $query->when($effectiveMaterial, function ($query) use ($effectiveMaterial) {
            $query->where("effectiveMaterial", $effectiveMaterial);
        });
    }

    public function scopeSearchByPharmacistFormId($query, $pharmacologicalFormId)
    {
        return $query->when($pharmacologicalFormId, function ($query) use ($pharmacologicalFormId) {
            $query->where("pharmacistForm_id", $pharmacologicalFormId);
        });
    }

    public function scopeSearchBySupplierId($query, $supplierId)
    {
        return $query->when($supplierId, function ($query) use ($supplierId) {
            $query->whereRelation("prices", "supplier_id", $supplierId);
        });
    }

    public function scopeSearchByDiscount($query, $discount)
    {
        return $query->when($discount, function ($query) use ($discount) {
            $query->whereRelation("prices", "clientDiscount", $discount);
        });
    }

    public function scopeSearchByCategory($query, $categoryId, $categoryLevel)
    {
        return $query->when($categoryId, function ($query) use ($categoryId, $categoryLevel) {
            $query->when($categoryLevel == 1, function ($query) use ($categoryId) {
                $query->whereRelation("category", "id", $categoryId);
            })->when($categoryLevel == 2, function ($query) use ($categoryId) {
                $query->whereRelation("sub_category", "id", $categoryId);
            });
        });
    }

    public function scopeWithCartInfo($query, $userId)
    {
        $query->when($userId, function ($query) use ($userId) {
            $query->with(["cart_info" => function ($query) use ($userId) {
                $query->whereHas("order", function ($query) use ($userId) {
                    $query->where("user_id", $userId)->where("order_status", OrderStatus::CART);
                });
            }]);
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

    public function scopewithWhereHasCarts($query, $userId)
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
}
