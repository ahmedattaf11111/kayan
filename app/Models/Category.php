<?php

namespace App\Models;

use App\Commons\Traits\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Image;
    use HasFactory;
    protected $guarded = ['id'];
    //start raletions
    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
