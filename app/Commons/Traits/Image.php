<?php

namespace App\Commons\Traits;

trait Image
{
    public function getImageAttribute($value)
    {
        return $value ? asset("uploads/$value") : null;
    }
    public function getImageName()
    {
        return $this->attributes["image"];
    }
}
