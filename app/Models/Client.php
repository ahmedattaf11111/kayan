<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = ["id"];

    protected $attributes = [
        "same_address_shipping" => 1
    ];
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
