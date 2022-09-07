<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getCategories()
    {
        return Category::with("media")->where("status", 1)->get();
    }
}
