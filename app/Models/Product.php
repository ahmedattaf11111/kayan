<?php

namespace App\Models;

use App\Utils\Models\ProductScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $with = ['productName'];

    use HasFactory, ProductScope;

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
}
