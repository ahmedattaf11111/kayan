<?php

namespace App\Utils\Models;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Media;
use App\Models\Price;
use App\Models\ProductName;
use App\Models\SubCategory;

trait ProductRelation
{
    public function biggestClientDiscountPrice()
    {
        return $this->hasOne(Price::class)->ofMany("clientDiscount", "max");
    }

    public function productName()
    {
        return $this->belongsTo(ProductName::class, "productName_id");
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }

    public function price()
    {
        return $this->hasOne(Price::class)->latestOfMany();
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function carts()
    {
        return $this->hasMany(CartItem::class);
    }
}
