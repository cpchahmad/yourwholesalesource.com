<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DropshipRequest extends Model
{
    public function has_market(){
        return $this->belongsToMany(Country::class,'dropship_request_countries','dropship_request_id','country_id');
    }

    public function has_status()
    {
        return $this->belongsTo(DropshipRequestStatus::class,'status_id');
    }

    public function has_attachments()
    {
        return $this->hasMany(DropshipRequestAttachment::class,'dropship_request_id');
    }

    public function has_store()
    {
        return $this->belongsTo(Shop::class,'shop_id');
    }

    public function has_manager()
    {
        return $this->belongsTo(User::class,'manager_id');
    }

    public function has_user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function shipping_marks()
    {
        return $this->hasMany(ShippingMark::class);
    }
}
