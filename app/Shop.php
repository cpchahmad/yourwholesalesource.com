<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
   public function has_user(){
       return $this->belongsToMany('App\User','user_shop','shop_id','user_id');
   }

}
