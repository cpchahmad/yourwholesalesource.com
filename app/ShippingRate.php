<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
   protected $fillable = [
       'name','shipping_price','type','min','max','zone_id','shipping_time','processing_time'
   ];

}
