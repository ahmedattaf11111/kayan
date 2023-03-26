<?php

namespace App\Models;

use App\Commons\Traits\Image;
use App\Utils\Models\ProductRelation;
use App\Utils\Models\ProductScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, ProductScope, ProductRelation,Image;
    protected $guarded=[];
    public function getPriceAttribute()
    {
        //Supplier id come from join operations of products only
        return $this->supplier_id ? $this->prices()->with("supplier")->where("supplier_id", $this->supplier_id)->first()
            : $this->prices()->whereRelation("supplier", "is_our_supplier", 1)->first();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function pharmacistForm()
    {
        return $this->belongsTo(PharmacistForm::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
}
