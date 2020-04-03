<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetailerProduct extends Model
{
    public function hasVariants(){
        return $this->hasMany('App\RetailerProductVariant');
    }
    public function has_images(){
        return $this->hasMany('App\RetailerImage','product_id');
    }
    public function has_categories(){
        return $this->belongsToMany('App\Category','retailer_product_category','product_id','category_id');
    }
    public function has_subcategories(){
        return $this->belongsToMany('App\SubCategory','retailer_product_subcategory','product_id','subcategory_id');
    }
}
