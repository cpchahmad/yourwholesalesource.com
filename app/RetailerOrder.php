<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetailerOrder extends Model
{
    public function line_items(){
        return $this->hasMany('App\RetailerOrderLineItem','retailer_order_id');
    }

    public function has_payment(){
        return $this->hasOne('App\OrderTransaction','retailer_order_id');
    }
}
