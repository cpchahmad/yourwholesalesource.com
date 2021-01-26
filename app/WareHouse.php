<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WareHouse extends Model
{
    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function warehouse_inventories() {
        return $this->hasMany(WarehouseInventory::class, 'warehouse_id');
    }

    public function zone() {
        return $this->hasOne(Zone::class, 'ware_house_id');
    }

    public function has_inventory($product) {
        $flag = false;
        $real_variants = $product->hasVariants()->pluck('id')->toArray();

        if(WarehouseInventory::where('warehouse_id', $this->id)->whereIn('product_variant_id', $real_variants)->exists()) {
            $flag = true;
        }
        if(WarehouseInventory::where('warehouse_id', $this->id)->where('product_id', $product->id)->exists()) {
            $flag = true;
        }
        return $flag;
    }

    public function get_inventory_quantity_for_product($product) {
        if(WarehouseInventory::where('warehouse_id', $this->id)->where('product_id', $product->id)->exists()) {
            $item = WarehouseInventory::where('warehouse_id', $this->id)->where('product_id', $product->id)->first();
            return $item->quantity;
        }
        return null;
    }

    public function has_inventory_quantity_for_product($product) {
        if(WarehouseInventory::where('warehouse_id', $this->id)->where('product_id', $product->id)->exists()) {
            return true;
        }
        return false;
    }

    public function get_inventory_quantity_for_variant($variant) {
        if(WarehouseInventory::where('warehouse_id', $this->id)->where('product_variant_id', $variant->id)->exists()) {
            $item = WarehouseInventory::where('warehouse_id', $this->id)->where('product_variant_id', $variant->id)->first();
            return $item->quantity;
        }
        return null;
    }

    public function get_inventory_quantity_for_retailer_variant($product, $variant) {
        $real_variant = $product->hasVariants()->where('sku', $variant->sku)->first();
        if(WarehouseInventory::where('warehouse_id', $this->id)->where('product_variant_id', $real_variant->id)->exists()) {
            $item = WarehouseInventory::where('warehouse_id', $this->id)->where('product_variant_id', $real_variant->id)->first();
            return $item->quantity;
        }
        return null;
    }

    public function has_inventory_quantity_for_retailer_variant($product, $variant) {
        $real_variant = $product->hasVariants()->where('sku', $variant->sku)->first();
        if(WarehouseInventory::where('warehouse_id', $this->id)->where('product_variant_id', $real_variant->id)->exists()) {
            return true;
        }
        return false;
    }
}
