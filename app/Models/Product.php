<?php

namespace App\Models;

use App\Utils\Models\ProductRelation;
use App\Utils\Models\ProductScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, ProductScope, ProductRelation;
}
