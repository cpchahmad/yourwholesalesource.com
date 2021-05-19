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

    public function linked_woocommerce_product(){
        return $this->hasOne( Product::class,'woocommerce_id','woocommerce_id');
    }


    public function linked_real_variant(){
        return $this->hasOne(ProductVariant::class,'shopify_id','shopify_variant_id');
    }

    public function linked_woocommerce_variant(){
        return $this->hasOne(ProductVariant::class,'woocommerce_id','woocommerce_variant_id');
    }

    public function linked_dropship_variant(){
        return $this->hasOne(ProductVariant::class,'id','dropship_variant_id');
    }

    public function linked_admin_product() {
        return $this->hasOne( Product::class,'id','admin_product_id');
    }

    public function linked_admin_variant(){
        return $this->hasOne(ProductVariant::class,'id','admin_variant_id');
    }

    public function has_associated_warehouse() {
        if($this->linked_product != null && $this->linked_product->linked_product != null)
            $admin_product = $this->linked_product->linked_product;
        else
            return false;

        if(WarehouseInventory::where('product_id', $admin_product->id)->whereNotNull('quantity')->exists())
            return WarehouseInventory::where('product_id', $admin_product->id)->whereNotNull('quantity')->groupBy('warehouse_id')->get();

        $real_product_variants = $admin_product->hasVariants()->pluck('id')->toArray();

        if(WarehouseInventory::whereIn('product_variant_id', $real_product_variants)->whereNotNull('quantity')->exists())
            return WarehouseInventory::whereIn('product_variant_id', $real_product_variants)->whereNotNull('quantity')->groupBy('warehouse_id')->get();

        return false;
    }


    public function has_associated_non_shopify_warehouse() {
        if($this->linked_real_product != null)
            $admin_product = $this->linked_real_product;
        elseif($this->linked_woocommerce_product != null)
            $admin_product = $this->linked_woocommerce_product;
        else
            return false;

        if(WarehouseInventory::where('product_id', $admin_product->id)->whereNotNull('quantity')->exists())
            return WarehouseInventory::where('product_id', $admin_product->id)->whereNotNull('quantity')->groupBy('warehouse_id')->get();

        $real_product_variants = $admin_product->hasVariants()->pluck('id')->toArray();

        if(WarehouseInventory::whereIn('product_variant_id', $real_product_variants)->whereNotNull('quantity')->exists())
            return WarehouseInventory::whereIn('product_variant_id', $real_product_variants)->whereNotNull('quantity')->groupBy('warehouse_id')->get();

        return false;
    }

    public function has_warehouse() {
        return $this->belongsTo(WareHouse::class, 'selected_warehouse');
    }

    public function getWeightAttribute() {
        if($this->linked_real_product)
            $weight = $this->linked_real_product->weight *  $this->quantity;
        elseif($this->linked_admin_product)
            $weight = $this->linked_admin_product->weight *  $this->quantity;
        elseif($this->linked_product != null)
            $weight = $this->linked_product->weight *  $this->quantity;
        elseif($this->linked_admin_variant != null && $this->linked_admin_variant->linked_product != null)
            $weight = $this->linked_admin_variant->linked_product->weight *  $this->quantity;

        return $weight;

    }
    public function getOunceWeightAttribute() {
        return $this->weight * 35.274;
    }
}
