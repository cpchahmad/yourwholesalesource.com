<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubSubCategory extends Model
{
    protected $fillable = ['title', 'sub_category_id'];
    public function hasSubCategory(){
        return $this->belongsTo(SubSubCategory::class, 'sub_category_id', 'id');
    }
}
