<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RetailerProductVariant extends Model
{
    public function has_image(){
        return $this->belongsTo('App\RetailerImage', 'image');
    }
}
