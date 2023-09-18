<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserCartItems extends Model
{
    protected $guarded = [];

    public function product():HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
