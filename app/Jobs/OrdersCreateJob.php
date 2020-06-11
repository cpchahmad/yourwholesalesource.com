<?php namespace App\Jobs;

use App\Customer;
use App\OrderLog;
use App\ProductVariant;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use App\RetailerProduct;
use App\RetailerProductVariant;
use App\ShippingRate;
use App\Shop;
use App\Zone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrdersCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's myshopify domain
     *
     * @var string
     */
    public $shopDomain;

    /**
     * The webhook data
     *
     * @var object
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @param string $shopDomain The shop's myshopify domain
     * @param object $data    The webhook data (JSON decoded)
     *
     * @return void
     */
    public function __construct($shopDomain, $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order =  $this->data;
        $shop = Shop::where('shopify_domain', $this->shopDomain)->first();
        $product_ids = [];
        $variant_ids  = [];
        foreach($order->line_items as $item){
            array_push($variant_ids,$item->variant_id);
            array_push($product_ids,$item->product_id);
        }
        if(RetailerProduct::whereIn('shopify_id',$product_ids)->exists()){
            if(!RetailerOrder::where('shopify_order_id',$order->id)->exists()){
                $new = new RetailerOrder();
                $new->shopify_order_id = $order->id;
                $new->email = $order->email;
                $new->phone = $order->phone;
                $new->shopify_created_at = date_create($order->created_at)->format('Y-m-d h:i:s');
                $new->shopify_updated_at =date_create($order->updated_at)->format('Y-m-d h:i:s');
                $new->note = $order->note;
                $new->name = $order->name;
                $new->total_price = $order->total_price;
                $new->subtotal_price = $order->subtotal_price;
                $new->total_weight = $order->total_weight;
                $new->taxes_included = $order->taxes_included;
                $new->total_tax = $order->total_tax;
                $new->currency = $order->currency;
                $new->total_discounts = $order->total_discounts;
                if(isset($order->customer)){
                    if (Customer::where('customer_shopify_id',$order->customer->id)->exists()){
                        $customer = Customer::where('customer_shopify_id',$order->customer->id)->first();
                        $new->customer_id = $customer->id;
                    }
                    else{
                        $customer = new Customer();
                        $customer->customer_shopify_id = $order->customer->id;
                        $customer->first_name = $order->customer->first_name;
                        $customer->last_name = $order->customer->last_name;
                        $customer->phone = $order->customer->phone;
                        $customer->email = $order->customer->email;
                        $customer->total_spent = $order->customer->total_spent;
                        $customer->shop_id = $shop->id;
                        $local_shop = $this->helper->getLocalShop();
                        if(count($local_shop->has_user) > 0){
                            $customer->user_id = $local_shop->has_user[0]->id;
                        }
                        $customer->save();
                        $new->customer_id = $customer->id;
                    }
                    $new->customer = json_encode($order->customer,true);
                }
                if(isset($order->shipping_address)){
                    $new->shipping_address = json_encode($order->shipping_address,true);
                }

                if(isset($order->billing_address)){
                    $new->billing_address = json_encode($order->billing_address,true);
                }

                $new->status = 'new';
                $new->shop_id = $shop->id;

                if(count($shop->has_user) > 0){
                    $new->user_id = $shop->has_user[0]->id;
                }

                $new->fulfilled_by = 'fantasy';
                $new->sync_status = 1;
                $new->save();

                $cost_to_pay = 0;

                foreach ($order->line_items as $item){
                    $new_line = new RetailerOrderLineItem();
                    $new_line->retailer_order_id = $new->id;
                    $new_line->retailer_product_variant_id = $item->id;
                    $new_line->shopify_product_id = $item->product_id;
                    $new_line->shopify_variant_id = $item->variant_id;
                    $new_line->title = $item->title;
                    $new_line->quantity = $item->quantity;
                    $new_line->sku = $item->sku;
                    $new_line->variant_title = $item->variant_title;
                    $new_line->title = $item->title;
                    $new_line->vendor = $item->vendor;
                    $new_line->price = $item->price;
                    $new_line->requires_shipping = $item->requires_shipping;
                    $new_line->taxable = $item->taxable;
                    $new_line->name = $item->name;
                    $new_line->properties = json_encode($item->properties,true);
                    $new_line->fulfillable_quantity = $item->fulfillable_quantity;
                    $new_line->fulfillment_status = $item->fulfillment_status;

                    $retailer_product = RetailerProduct::where('shopify_id',$item->product_id)->first();
                    if($retailer_product != null){
                        $new_line->fulfilled_by = $retailer_product->fulfilled_by;
                    }
                    else{
                        $new_line->fulfilled_by = 'store';
                    }

                    $related_variant =  RetailerProductVariant::where('shopify_id',$item->variant_id)->first();
                    if($related_variant != null){
                        $new_line->cost = $related_variant->cost;
                        $cost_to_pay = $cost_to_pay + $related_variant->cost * $item->quantity;
                    }
                    $new_line->save();
                }

                $new->cost_to_pay = $cost_to_pay;
                $new->save();

                if(isset($order->shipping_address)){
                    $total_weight = 0;
                    $country = $order->shipping_address->country;
                    foreach ($new->line_items as $index => $v){
                        if($v->linked_product != null){
                            $total_weight = $total_weight + ( $v->linked_product->weight *  $v->quantity);
                        }
                    }
                    $zoneQuery = Zone::query();
                    $zoneQuery->whereHas('has_countries',function ($q) use ($country){
                        $q->where('name',$country);
                    });
                    $zoneQuery = $zoneQuery->pluck('id')->toArray();

                    $shipping_rates = ShippingRate::where('type','weight')->whereIn('zone_id',$zoneQuery)->newQuery();
//                    $shipping_rates->whereRaw('min <='.$total_weight);
//                    $shipping_rates->whereRaw('max >='.$total_weight);
                    $shipping_rates =  $shipping_rates->first();
                    if($shipping_rates != null){
                        if($shipping_rates->min > 0){
                            $ratio = $total_weight/$shipping_rates->min;
                            $new->shipping_price = $shipping_rates->shipping_price*$ratio;
                            $new->total_price =  $order->total_price + $shipping_rates->shipping_price;
                            $new->save;
                        }
                        else{
                            $new->shipping_price = 0;
                            $new->save;
                        }
                    }
                    else{
                        $new->shipping_price = 0;
                        $new->save;
                    }
                }

                /*Maintaining Log*/
                $order_log =  new OrderLog();
                $order_log->message = "Order synced to WeFullFill on ".date_create($new->created_at)->format('d M, Y h:i a');
                $order_log->status = "Newly Synced";
                $order_log->retailer_order_id = $new->id;
                $order_log->save();

            }
        }
    }
}
