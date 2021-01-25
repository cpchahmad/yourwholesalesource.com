<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WareHouse extends Model
{
    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function get_inventory_quantity($product) {
        if(WarehouseInventory::where('warehouse_id', $this->id)->where('product_id', $product->id)->exists()) {
            $item = WarehouseInventory::where('warehouse_id', $this->id)->where('product_id', $product->id)->first();
            return $item->quantity;
        }
        return null;
    }
}
