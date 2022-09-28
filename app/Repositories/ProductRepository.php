<?php

namespace App\Repositories;

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
        $companyId,
        $supplierId,
        $discount,
        $pageSize
    ) {
        return Product::biggestDiscountProducts()
            ->searchByName($name)
            ->searchByEffectiveMaterial($effectiveMaterial)
            ->searchByPharmacistFormId($pharmacologicalFormId)
            ->searchByCompanyId($companyId)
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
    public function getDealProducts($limit)
    {
        return Product::has("deal")->has("price")->with("deal", "price")->take($limit)->get();
    }
}
