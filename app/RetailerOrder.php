<?php

namespace App;

use App\Http\Controllers\UspsController;
use App\Traits\RetailerOrderTrait;
use Illuminate\Database\Eloquent\Model;

class RetailerOrder extends Model
{
    use RetailerOrderTrait;
    public function line_items(){
        return $this->hasMany('App\RetailerOrderLineItem','retailer_order_id');
    }

    public function has_payment(){
        return $this->hasOne('App\OrderTransaction','retailer_order_id');
    }

    public function has_store(){
        return $this->belongsTo('App\Shop','shop_id');
    }

    public function has_customer(){
        return $this->belongsTo('App\Customer','customer_id');
    }

    public function has_user(){
        return $this->belongsTo('App\User','user_id');
    }

    public function fulfillments(){
        return $this->hasMany('App\OrderFulfillment','retailer_order_id');
    }
    public function logs(){
        return $this->hasMany('App\OrderLog','retailer_order_id');
    }
    public function imported(){
        return $this->hasOne('App\UserFileTemp','order_id');
    }

    public function getCourierNameAttribute() {
        if($this->shipping_address)
        {
            $shipping = json_decode($this->shipping_address);
            $country = $shipping->country;

            $zoneQuery = Zone::query();
            $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                $q->where('name','LIKE','%'.$country.'%');
            });
            $zoneQuery = $zoneQuery->first();


            if($zoneQuery == null)
                return '';

            if($zoneQuery->courier == null)
                return '';

            return$zoneQuery->courier->title;
        }
        return '';
    }

    public function getCourierUrlAttribute() {
        if($this->shipping_address)
        {
            $shipping = json_decode($this->shipping_address);
            $country = $shipping->country;

            $zoneQuery = Zone::query();
            $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                $q->where('name','LIKE','%'.$country.'%');
            });
            $zoneQuery = $zoneQuery->first();

            if($zoneQuery == null)
                return '';

            if($zoneQuery->courier == null)
                return '';

            return$zoneQuery->courier->url;
        }
        return '';
    }

    public function getCourierIdAttribute() {
        if($this->shipping_address)
        {
            $shipping = json_decode($this->shipping_address);
            $country = $shipping->country;

            $zoneQuery = Zone::query();
            $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                $q->where('name','LIKE','%'.$country.'%');
            });
            $zoneQuery = $zoneQuery->first();

            if($zoneQuery == null)
                return '';

            if($zoneQuery->courier == null)
                return '';

            return$zoneQuery->courier->id;
        }
        return '';
    }

    public function getTotalCostAttribute() {
        $cost_to_pay = 0;
        foreach ($this->line_items as $index => $item) {
            $cost_to_pay = $cost_to_pay + $item->cost * $item->quantity;
        }

        return $cost_to_pay;
    }

    public function getShippingRateAttribute() {

        $shipping_address = json_decode($this->shipping_address);
        $total_shipping = 0;

        if(isset($shipping_address)){
            $country = $shipping_address->country;
            foreach ($this->line_items as $index => $v){
                if($v->linked_product != null && $v->linked_product->linked_product){
                    $weight = $v->linked_product->linked_product->weight *  $v->quantity;
                    if($v->linked_product->linked_product != null) {
                        $zoneQuery = Zone::where('warehouse_id', 3)->newQuery();
                        $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                            $q->where('name','LIKE','%'.$country.'%');
                        });
                        $zoneQuery = $zoneQuery->pluck('id')->toArray();

                        $shipping_rates = ShippingRate::whereIn('zone_id',$zoneQuery)->newQuery();
                        $shipping_rates =  $shipping_rates->first();
                        if($shipping_rates != null){

                            if($shipping_rates->type == 'flat'){
                                $total_shipping += $shipping_rates->shipping_price;
                            }
                            else{
                                if($shipping_rates->min > 0){
                                    $ratio = $weight/$shipping_rates->min;
                                    $total_shipping +=  $shipping_rates->shipping_price*$ratio;
                                }
                                else{
                                    $total_shipping += 0;
                                }
                            }

                        }
                        else{
                            $total_shipping += 0;
                        }
                    }
                }
            }

            return number_format($total_shipping, 2);
        }
    }

    public function getShippingRateForNonShopifyAttribute() {

        $shipping_address = json_decode($this->shipping_address);
        $total_shipping = 0;

        if(isset($shipping_address)){
            $country = $shipping_address->country;
            foreach ($this->line_items as $index => $v){
                if($v->linked_real_product)
                    $weight = $v->linked_real_product->weight *  $v->quantity;
                elseif($v->linked_woocommerce_product)
                    $weight = $v->linked_woocommerce_product->weight *  $v->quantity;
                elseif($v->linked_dropship_variant)
                    $weight = $v->linked_dropship_variant->linked_product->weight *  $v->quantity;


                if($v->linked_real_product != null){
                    $zoneQuery = Zone::where('warehouse_id', 3)->newQuery();
                    $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                        $q->where('name','LIKE','%'.$country.'%');
                    });
                    $zoneQuery = $zoneQuery->pluck('id')->toArray();

                    $shipping_rates = ShippingRate::whereIn('zone_id',$zoneQuery)->newQuery();
                    $shipping_rates =  $shipping_rates->first();

                    if($shipping_rates != null){

                        if($shipping_rates->type == 'flat'){
                            $total_shipping += $shipping_rates->shipping_price;
                        }
                        else{
                            if($shipping_rates->min > 0){
                                $ratio = $weight/$shipping_rates->min;
                                $total_shipping +=  $shipping_rates->shipping_price*$ratio;
                            }
                            else{
                                $total_shipping += 0;
                            }
                        }

                    }
                    else{
                        $total_shipping += 0;
                    }

                }
                elseif($v->linked_woocommerce_product != null){
                    $zoneQuery = Zone::where('warehouse_id', 3)->newQuery();
                    $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                        $q->where('name','LIKE','%'.$country.'%');
                    });
                    $zoneQuery = $zoneQuery->pluck('id')->toArray();

                    $shipping_rates = ShippingRate::whereIn('zone_id',$zoneQuery)->newQuery();
                    $shipping_rates =  $shipping_rates->first();

                    if($shipping_rates != null){

                        if($shipping_rates->type == 'flat'){
                            $total_shipping += $shipping_rates->shipping_price;
                        }
                        else{
                            if($shipping_rates->min > 0){
                                $ratio = $weight/$shipping_rates->min;
                                $total_shipping +=  $shipping_rates->shipping_price*$ratio;
                            }
                            else{
                                $total_shipping += 0;
                            }
                        }

                    }
                    else{
                        $total_shipping += 0;
                    }

                }
                elseif($v->linked_dropship_variant != null) {
                    $zoneQuery = Zone::where('warehouse_id', 3)->newQuery();
                    $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                        $q->where('name','LIKE','%'.$country.'%');
                    });
                    $zoneQuery = $zoneQuery->pluck('id')->toArray();

                    $shipping_rates = ShippingRate::whereIn('zone_id',$zoneQuery)->newQuery();
                    $shipping_rates =  $shipping_rates->first();

                    if($shipping_rates != null){

                        if($shipping_rates->type == 'flat'){
                            $total_shipping += $shipping_rates->shipping_price;
                        }
                        else{
                            if($shipping_rates->min > 0){
                                $ratio = $weight/$shipping_rates->min;
                                $total_shipping +=  $shipping_rates->shipping_price*$ratio;
                            }
                            else{
                                $total_shipping += 0;
                            }
                        }

                    }
                    else{
                        $total_shipping += 0;
                    }
                }
            }

            return number_format($total_shipping, 2);
        }
    }

    public function isShippable() {
        $shipping_address = json_decode($this->shipping_address);

        if(isset($shipping_address)){

            $country = $shipping_address->country;

            $zoneQuery = Zone::where('warehouse_id', 3)->newQuery();
            $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                $q->where('name','LIKE','%'.$country.'%');
            });
            $zoneQuery = $zoneQuery->pluck('id')->toArray();

            $shipping_rates = ShippingRate::whereIn('zone_id',$zoneQuery)->newQuery();
            $shipping_rates =  $shipping_rates->first();
            if($shipping_rates != null){
                return true;
            }
            else{
                return false;
            }
        }

        return false;
    }

    public function getHandlingFeeAttribute() {
        $handling_fee = 0;
        foreach($this->line_items()->where('fulfilled_by', '!=', 'store')->cursor() as $index => $item) {
            if($index == 0)
                $handling_fee += 2.5;
            else
                $handling_fee += 0.5;
        }


        return number_format($handling_fee, 2);
    }

    public function getPostalCodeAttribute() {
        $shipping_address = json_decode($this->shipping_address);

        if(isset($shipping_address))
            return $shipping_address->zip;
    }

    public function getWeightAttribute() {
        $total_weight = 0;
        foreach($this->line_items()->where('fulfilled_by', '!=', 'store')->cursor() as $index => $item) {
            $total_weight += $item->weight;
        }

        return $total_weight;
    }


    public function getOunceWeightAttribute() {
        return $this->weight * 35.274;
    }

    public function getUspsShippingAttribute() {
        $usps = new UspsController();
        $shipping_rates = $usps->getShippingInfo($this);
        if($shipping_rates !== null)
            dd($shipping_rates->Package);
            //$shipping_rate = $shipping_rates->Postage->Rate;
        else
            $shipping_rate = 0;

        return $shipping_rate;


    }
}
