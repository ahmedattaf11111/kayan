<?php

namespace App\Repositories;

use App\Models\SimpleAdvertise;

class SimpleAdvertiseRepository
{
    public function getSimpleAdvertises()
    {
        return SimpleAdvertise::get();
    }
}
