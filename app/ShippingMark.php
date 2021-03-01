<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingMark extends Model
{
    public function dropship_product() {
        return $this->belongsTo(DropshipProduct::class);
    }
}
