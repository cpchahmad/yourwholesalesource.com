<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DropshipProduct extends Model
{
    public function dropship_product_variants() {
        return $this->hasMany(DropshipProductVariant::class);
    }
}
