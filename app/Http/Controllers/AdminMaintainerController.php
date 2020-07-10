<?php

namespace App\Http\Controllers;


use App\RetailerOrder;

class AdminMaintainerController extends Controller
{
    private $helper;

    /**
     * AdminMaintainerController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function sync_order_to_admin_store(RetailerOrder $order){
        $admin_store = $this->helper->getAdminShop();
        $line_items = [];
        if($order->custom == 1){
            foreach ($order->line_items as $item){
                if($item->linked_real_variant != null){
                    array_push($line_items, [
                        "variant_id" => $item->linked_real_variant->shopify_id,
                        "quantity" => $item->quantity,
                    ]);
                }
                else{
                    array_push($line_items, [
                        "title"=> $item->name,
                        "price"=> $item->cost,
                        "quantity"=> $item->qunatity,
                    ]);
                }
            }
        }
        else{
            foreach ($order->line_items as $item){
                $retailer_product = $item->linked_product;
                $retailer_variant = $item->linked_variant;
                $admin_product = $retailer_product->linked_product;
                if(count($admin_product->hasVariants) > 0){
                    $variant =  $admin_product->hasVariants->where('option1',$retailer_variant->option1)
                        ->where('option2',$retailer_variant->option2)
                        ->where('option3',$retailer_variant->option3)->first();
                    if($variant != null){
                        $variant_id = $variant->shopify_id;
                    }
                    else{
                        $variant_id = null;
                    }
                }
                else{
                    $response = $admin_store->api()->rest('GET', '/admin/api/2019-10/products/'.$admin_product->shopify_id.'.json');
                    if(!$response->errors){
                        $shopifyVariants = $response->body->product->variants;
                        $variant_id = $shopifyVariants[0]->id;
                    }
                }
                if($variant_id != null){
                    array_push($line_items, [
                        "variant_id" => $variant_id,
                        "quantity" => $item->quantity,
                    ]);
                }
                else{
                    array_push($line_items, [
                        "title"=> $item->name,
                        "price"=> $item->cost,
                        "quantity"=> $item->qunatity,
                    ]);

                }

            }

        }

        if($order->email == null){
            $email = 'super_admin@wefullfill.com';
        }
        else{
            $email =  $order->email;
        }
        if($order->billing_address != null){
            $billing_address = json_decode($order->billing_address);
            $billing =  [
                "address1" =>$billing_address->address1,
                "address2" => $billing_address->address2,
                "city" =>$billing_address->city,
                "first_name" => $billing_address->first_name,
                "last_name" => $billing_address->last_name,
                "province" => $billing_address->province,
                "country" =>$billing_address->country,
                "zip" => $billing_address->zip,
                "name" => $billing_address->first_name . ' ' . $billing_address->last_name,

            ];
        }
        else{
            $billing = null;
        }
        if($order->shipping_address != null){
            $shipping_address = json_decode($order->shipping_address);
            $shipping =  [
                "address1" =>$shipping_address->address1,
                "address2" => $shipping_address->address2,
                "city" =>$shipping_address->city,
                "first_name" => $shipping_address->first_name,
                "last_name" => $shipping_address->last_name,
                "province" => $shipping_address->province,
                "country" =>$shipping_address->country,
                "zip" => $shipping_address->zip,
                "name" => $shipping_address->first_name . ' ' . $shipping_address->last_name,

            ];
        }
        else{
            $shipping = null;
        }

        if($order->shipping_price != null){
            $shipping_line =  [
                "custom" => true,
                "price" => $order->shipping_price,
                "title" => 'WefullFill Shipping'
            ];
        }
        else{
            $shipping_line =  [
                "custom" => true,
                "price" => 0,
                "title" => 'WefullFill Shipping'
            ];
        }
        $orderData = [
            "draft_order" => [
                "line_items" => $line_items,
                "email" =>$email,
                "shipping_address" => $shipping,
                "billing_address" => $billing,
                "shipping_line" => $shipping_line,
            ]
        ];
        $response = $admin_store->api()->rest('POST', '/admin/api/2019-10/draft_orders.json', $orderData);

        if(!$response->errors){
            $draft_order = $response->body->draft_order;
            $admin_order_response = $admin_store->api()->rest('PUT', '/admin/api/2020-04/draft_orders/'.$draft_order->id.'/complete.json');
            if(!$admin_order_response->errors){
                $admin_order = $admin_order_response->body->draft_order;
                $order->admin_shopify_id = $admin_order->order_id;
                $order->save();
                return 1;
            }
            else{
               dd($admin_order_response);
            }
        }
        else{
            dd($response);
        }


    }
}
