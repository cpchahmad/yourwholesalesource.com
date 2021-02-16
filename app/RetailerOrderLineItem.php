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

    public function linked_real_product(){
        return $this->hasOne( Product::class,'shopify_id','shopify_product_id');
    }
    public function linked_real_variant(){
        return $this->hasOne(ProductVariant::class,'shopify_id','shopify_variant_id');
    }

    public function has_associated_warehouse() {
        if($this->linked_product != null && $this->linked_product->linked_product != null)
            $admin_product = $this->linked_product->linked_product;
        else
            return false;

        if(WarehouseInventory::where('product_id', $admin_product->id)->whereNotNull('quantity')->exists())
            return WarehouseInventory::where('product_id', $admin_product->id)->whereNotNull('quantity')->get();

        $real_product_variants = $admin_product->hasVariants()->pluck('id')->toArray();

        if(WarehouseInventory::whereIn('product_variant_id', $real_product_variants)->whereNotNull('quantity')->exists())
            return WarehouseInventory::whereIn('product_variant_id', $real_product_variants)->whereNotNull('quantity')->get();

        return false;
    }
}
