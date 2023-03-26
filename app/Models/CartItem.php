<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $guarded = [];

    protected $appends = ["price","dealPrice"];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    //Prices
    public function getPriceAttribute()
    {
        return  $this->supplier->prices()->where('product_id', $this->product_id)->first();
    }
    public function getDealPriceAttribute()
    {
        return  $this->supplier->dealPrices()->where('product_id', $this->product_id)->first();
    }
}
