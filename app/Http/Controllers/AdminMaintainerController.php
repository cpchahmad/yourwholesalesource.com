<?php

namespace App\Http\Controllers;


use App\OrderFulfillment;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use Illuminate\Http\Request;
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

    public function sync_order_to_admin_store(RetailerOrder $order)
    {
        $admin_store = $this->helper->getAdminShop();
        $line_items = [];
        if ($order->custom == 1) {
            foreach ($order->line_items as $item) {
                if ($item->linked_real_variant != null) {
                    array_push($line_items, [
                        "variant_id" => $item->linked_real_variant->shopify_id,
                        "quantity" => $item->quantity,
                    ]);
                } else {
                    array_push($line_items, [
                        "title" => $item->name,
                        "price" => $item->cost,
                        "quantity" => $item->qunatity,
                    ]);
                }
            }
        } else {
            foreach ($order->line_items as $item) {
                $retailer_product = $item->linked_product;
                $retailer_variant = $item->linked_variant;
                $admin_product = $retailer_product->linked_product;
                if (count($admin_product->hasVariants) > 0) {
                    $variant = $admin_product->hasVariants->where('option1', $retailer_variant->option1)
                        ->where('option2', $retailer_variant->option2)
                        ->where('option3', $retailer_variant->option3)->first();
                    if ($variant != null) {
                        $variant_id = $variant->shopify_id;
                    } else {
                        $variant_id = null;
                    }
                } else {
                    $response = $admin_store->api()->rest('GET', '/admin/api/2019-10/products/' . $admin_product->shopify_id . '.json');
                    if (!$response->errors) {
                        $shopifyVariants = $response->body->product->variants;
                        $variant_id = $shopifyVariants[0]->id;
                    }
                }
                if ($variant_id != null) {
                    array_push($line_items, [
                        "variant_id" => $variant_id,
                        "quantity" => $item->quantity,
                    ]);
                } else {
                    array_push($line_items, [
                        "title" => $item->name,
                        "price" => $item->cost,
                        "quantity" => $item->qunatity,
                    ]);

                }

            }

        }

        if ($order->email == null) {
            $email = 'super_admin@wefullfill.com';
        } else {
            $email = $order->email;
        }
        if ($order->billing_address != null) {
            $billing_address = json_decode($order->billing_address);
            $billing = [
                "address1" => $billing_address->address1,
                "address2" => $billing_address->address2,
                "city" => $billing_address->city,
                "first_name" => $billing_address->first_name,
                "last_name" => $billing_address->last_name,
                "province" => $billing_address->province,
                "country" => $billing_address->country,
                "zip" => $billing_address->zip,
                "name" => $billing_address->first_name . ' ' . $billing_address->last_name,

            ];
        } else {
            $billing = null;
        }
        if ($order->shipping_address != null) {
            $shipping_address = json_decode($order->shipping_address);
            $shipping = [
                "address1" => $shipping_address->address1,
                "address2" => $shipping_address->address2,
                "city" => $shipping_address->city,
                "first_name" => $shipping_address->first_name,
                "last_name" => $shipping_address->last_name,
                "province" => $shipping_address->province,
                "country" => $shipping_address->country,
                "zip" => $shipping_address->zip,
                "name" => $shipping_address->first_name . ' ' . $shipping_address->last_name,

            ];
        } else {
            $shipping = null;
        }

        if ($order->shipping_price != null) {
            $shipping_line = [
                "custom" => true,
                "price" => $order->shipping_price,
                "title" => 'WefullFill Shipping'
            ];
        } else {
            $shipping_line = [
                "custom" => true,
                "price" => 0,
                "title" => 'WefullFill Shipping'
            ];
        }
        $orderData = [
            "draft_order" => [
                "line_items" => $line_items,
                "email" => $email,
                "shipping_address" => $shipping,
                "billing_address" => $billing,
                "shipping_line" => $shipping_line,
            ]
        ];
        $response = $admin_store->api()->rest('POST', '/admin/api/2019-10/draft_orders.json', $orderData);

        if (!$response->errors) {
            $draft_order = $response->body->draft_order;
            $admin_order_response = $admin_store->api()->rest('PUT', '/admin/api/2020-04/draft_orders/' . $draft_order->id . '/complete.json');
            if (!$admin_order_response->errors) {
                $admin_order = $admin_order_response->body->draft_order;
                $order->admin_shopify_id = $admin_order->order_id;
                $order->save();
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }


    }

    public function admin_order_fullfillment(RetailerOrder $order, Request $request, OrderFulfillment $fulfillment)
    {
        $fulfillable_quantities = $request->input('item_fulfill_quantity');

        $admin_shop = $this->helper->getAdminShop();
        /*Location and Admin Order Fetch!*/
        $location_response = $admin_shop->api()->rest('GET', '/admin/locations.json');
        $admin_order_response = $admin_shop->api()->rest('GET', '/admin/orders/'.$order->admin_shopify_id.'.json');

        if (!$location_response->errors && !$admin_order_response->errors) {
            $data = [
                "fulfillment" => [
                    "location_id" => $location_response->body->locations[0]->id,
                    "tracking_number" => null,
                    "line_items" => [

                    ]
                ]
            ];
            $admin_variants = $admin_order_response->body->order->line_items;

            foreach ($request->input('item_id') as $index => $item) {
                $line_item = RetailerOrderLineItem::find($item);
                if ($line_item != null && $fulfillable_quantities[$index] > 0) {
                    if ($order->custom == 1) {
                        if ($line_item->linked_real_variant != null) {
                            $item_variant_id = $line_item->linked_real_variant->shopify_id;
                            foreach ($admin_variants as $variant){
                                if($variant->variant_id == $item_variant_id){
                                    array_push($data['fulfillment']['line_items'], [
                                        "id" => $variant->id,
                                        "quantity" => $fulfillable_quantities[$index],
                                    ]);
                                }
                            }
                        }
                    }
                    else{
                        $retailer_product = $line_item->linked_product;
                        $retailer_variant = $line_item->linked_variant;
                        $admin_product = $retailer_product->linked_product;
                        if (count($admin_product->hasVariants) > 0) {
                            $variant = $admin_product->hasVariants->where('option1', $retailer_variant->option1)
                                ->where('option2', $retailer_variant->option2)
                                ->where('option3', $retailer_variant->option3)->first();
                            if ($variant != null) {
                                $variant_id = $variant->shopify_id;
                            } else {
                                $variant_id = null;
                            }
                        } else {
                            $response = $admin_shop->api()->rest('GET', '/admin/api/2019-10/products/' . $admin_product->shopify_id . '.json');
                            if (!$response->errors) {
                                $shopifyVariants = $response->body->product->variants;
                                $variant_id = $shopifyVariants[0]->id;
                            }
                        }
                        if($variant_id != null){
                            foreach ($admin_variants as $variant){
                                if($variant->variant_id == $variant_id){
                                    array_push($data['fulfillment']['line_items'], [
                                        "id" => $variant->id,
                                        "quantity" => $fulfillable_quantities[$index],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            if(count($data['fulfillment']['line_items']) > 0){
                $response = $admin_shop->api()->rest('POST', '/admin/orders/' . $order->admin_shopify_id . '/fulfillments.json', $data);
                if(!$response->errors){
                    $fulfillment->fulfillment_shopify_id = $response->body->fulfillment->id;
                    $fulfillment->save();
                }
            }

        } else {
            return 0;
        }
    }
}
