<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function hasVariants(){
        return $this->hasMany('App\ProductVariant');
    }
}
