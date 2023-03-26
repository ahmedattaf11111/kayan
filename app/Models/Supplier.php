<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded=[];
    public function prices()
    {
        return $this->hasMany(Price::class);
    }
    public function cart_info()
    {
        return $this->hasOne(CartItem::class);
    }
    public function dealPrices()
    {
        return $this->hasMany(DealPrice::class);
    }
}
