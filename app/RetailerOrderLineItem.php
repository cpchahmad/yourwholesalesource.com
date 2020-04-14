<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetailerOrderLineItem extends Model
{
    public function linked_product(){
        return $this->hasOne( RetailerProduct::class,'shopify_id','shopify_product_id');
    }
    public function linked_variant(){
        return $this->hasOne(RetailerProductVariant::class,'shopify_id','shopify_variant_id');
    }
}
