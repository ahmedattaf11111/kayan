<?php

namespace App\Models;

use App\Utils\Models\ProductRelation;
use App\Utils\Models\ProductScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, ProductScope, ProductRelation;

    public function getPriceAttribute()
    {
        //Supplier id come from join operations of products only
        return $this->supplier_id ? $this->price()->where("supplier_id", $this->supplier_id)->first()
            : $this->prices()->whereRelation("supplier", "is_our_supplier", 1)->first();
    }
}
