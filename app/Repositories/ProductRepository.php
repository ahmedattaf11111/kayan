<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;

class ProductRepository
{
    public function getBiggestClientDiscountProducts($categoryId, $categoryLevel, $pageSize)
    {
        return Product::with("biggestClientDiscountPrice")
            ->has("biggestClientDiscountPrice")
            ->when($categoryLevel == 1, function ($query) use ($categoryId) {
                $query->whereRelation("category", "id", $categoryId);
            })
            ->when($categoryLevel == 2, function ($query) use ($categoryId) {
                $query->whereRelation("sub_category", "id", $categoryId);
            })
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
        return Product::has("deal")->has("price")->with("deal","price")->take($limit)->get();
    }
}
