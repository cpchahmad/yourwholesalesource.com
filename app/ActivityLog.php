<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class, 'model_id');
    }
}
