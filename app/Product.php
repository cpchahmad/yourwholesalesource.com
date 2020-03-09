<?php

namespace App;

use App\Traits\ProductVariantTrait;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use ProductVariantTrait;
    public function hasVariants(){
        return $this->hasMany('App\ProductVariant');
    }
    public function has_categories(){
        return $this->belongsToMany('App\Category','category_product','product_id','category_id');
    }
    public function has_subcategories(){
        return $this->belongsToMany('App\SubCategory','subcategory_product','product_id','subcategory_id');
    }

}
