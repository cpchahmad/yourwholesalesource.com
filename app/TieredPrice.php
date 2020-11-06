<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TieredPrice extends Model
{
    public function product_variant() {
        return $this->belongsTo(ProductVariant::class);
    }
}
