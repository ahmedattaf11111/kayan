<?php

namespace App\Repositories;

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
  
}
