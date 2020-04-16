<?php

namespace App;

use App\Traits\RetailerOrderTrait;
use Illuminate\Database\Eloquent\Model;

class RetailerOrder extends Model
{
    use RetailerOrderTrait;
    public function line_items(){
        return $this->hasMany('App\RetailerOrderLineItem','retailer_order_id');
    }

    public function has_payment(){
        return $this->hasOne('App\OrderTransaction','retailer_order_id');
    }

    public function has_store(){
        return $this->belongsTo('App\Shop','shop_id');
    }

    public function fulfillments(){
        return $this->hasMany('App\OrderFulfillment','retailer_order_id');
    }
}
