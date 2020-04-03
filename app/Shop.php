<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
   public function has_user(){
       return $this->belongsToMany('App\User','user_shop','shop_id','user_id');
   }

    public function has_imported(){
        return $this->belongsToMany('App\Product','retailer_product_shop','shop_id','product_id');
    }
}
