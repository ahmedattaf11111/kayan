<?php

namespace App\Models;

use App\Commons\Traits\Image;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $guarded = [];
    use Image;
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
