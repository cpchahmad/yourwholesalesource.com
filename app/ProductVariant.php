<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    public function hasCategory(){
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
