<?php

namespace App\Utils\Models;

trait ProductScope
{
    public function scopeBiggestDiscountProducts($query)
    {
        return $query->with("biggestClientDiscountPrice")
            ->has("biggestClientDiscountPrice");
    }
    public function scopeSearchByName($query, $name)
    {
        return $query->when($name, function ($query) use ($name) {
            $query->whereHas("productName", function ($query) use ($name) {
                $query->where("nameAr", "like", "%$name%")
                    ->orWhere("nameEn", "like", "%$name%");
            });
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
    public function scopeSearchByCompanyId($query, $companyId)
    {
        return $query->when($companyId, function ($query) use ($companyId) {
            $query->where("company_id", $companyId);
        });
    }
    public function scopeSearchBySupplierId($query, $supplierId)
    {
        return $query->when($supplierId, function ($query) use ($supplierId) {
            $query->where("supplier_id", $supplierId);
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
}
