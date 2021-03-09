<?php

namespace App;

use App\Traits\ProductVariantTrait;
use Illuminate\Database\Eloquent\Model;

class RetailerProduct extends Model
{
    use ProductVariantTrait;
    public function hasVariants(){
        return $this->hasMany('App\RetailerProductVariant','product_id');
    }
    public function has_images(){
        return $this->hasMany('App\RetailerImage','product_id');
    }
    public function has_categories(){
        return $this->belongsToMany('App\Category','retailer_product_category','product_id','category_id');
    }
    public function has_subcategories(){
        return $this->belongsToMany('App\SubCategory','retailer_product_subcategory','product_id','subcategory_id');
    }

    public function linked_product(){
        return $this->belongsTo('App\Product','linked_product_id');
    }

    public function has_shop() {
        return $this->belongsTo('App\Shop', 'shop_id');
    }

    public function has_inventory() {
        $real_product = $this->linked_product;
        $flag = false;

        if(!$real_product)
            return false;

        if(WarehouseInventory::where('product_id', $real_product->id)->whereNotNull('quantity')->exists())
            $flag = true;

        $real_product_variants = $real_product->hasVariants()->pluck('id')->toArray();

        if(WarehouseInventory::whereIn('product_variant_id', $real_product_variants)->whereNotNull('quantity')->exists())
            $flag = true;

        return $flag;
    }


    public function tags() {
        return $this->belongsToMany(Tag::class, 'retailer_product_tag');
    }

}
