<?php

namespace App\Repositories;

use App\Models\Slider;

class SliderRepository
{
    public function getSliders()
    {
        return Slider::get();
    }
}
