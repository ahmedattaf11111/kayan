<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Newsletter;
use App\Models\SimpleAdvertise;
use App\Models\Slider;

class HomeRepository
{
    public function storeNewsletter($newsLetterInput)
    {
        Newsletter::create($newsLetterInput);
    }
    public function getSimpleAdvertises()
    {
        return SimpleAdvertise::get();
    }
    public function getSliders()
    {
        return Slider::get();
    }
    public function getCategories()
    {
        return Category::with("media")->where("status", 1)->get();
    }
}
