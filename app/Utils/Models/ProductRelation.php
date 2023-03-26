<?php

namespace App\Utils\Models;

use App\Models\AlsoBoughtProduct;
use App\Models\BestSellerProduct;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\DealPrice;
use App\Models\Media;
use App\Models\MostPopularProduct;
use App\Models\Price;
use App\Models\SubCategory;

trait ProductRelation
{
    public function biggestClientDiscountPrice()
    {
        return $this->hasOne(Price::class)->ofMany("client_discount", "max");
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

    public function dealPrice()
    {
        return $this->hasOne(DealPrice::class);
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
    public function bestSellerProducts()
    {
        return $this->hasMany(BestSellerProduct::class);
    }
    public function mostPopularProducts()
    {
        return $this->hasMany(MostPopularProduct::class);
    }
    public function alsoBougtProducts()
    {
        return $this->hasMany(AlsoBoughtProduct::class);
    }
    public function cart_info()
    {
        return $this->hasOne(CartItem::class);
    }
}
