<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function getShippingCost($product) {
        $country = $this;

        if ($product != null) {
            $total_weight = $product->weight;
        } else {
            $total_weight = 0;
        }


        $zoneQuery = Zone::query();
        $zoneQuery->whereHas('has_countries', function ($q) use ($country) {
            $q->where('name', 'LIKE', '%' . $country . '%');
        });
        $zoneQuery = $zoneQuery->pluck('id')->toArray();

        $shipping_rates = ShippingRate::whereIn('zone_id', $zoneQuery)->newQuery();

        $shipping_rates = $shipping_rates->get();

        foreach ($shipping_rates as $shipping_rate) {
            if ($shipping_rate->min > 0) {
                if ($shipping_rate->type == 'flat') {

                } else {
                    $ratio = $total_weight / $shipping_rate->min;
                    $shipping_rate->shipping_price = $shipping_rate->shipping_price * $ratio;
                }

            } else {
                $ratio = 0;
                $shipping_rate->shipping_price = $shipping_rate->shipping_price * $ratio;
            }

        }
    }
}
