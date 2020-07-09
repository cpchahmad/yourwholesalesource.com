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

        dd($line_items);


//        dd(CountrySubdivisions::getCode('United States','Alabama'));

//        $draft_orders = $this->helper->getShopify()->call([
//            'METHOD' => 'POST',
//            'URL' => '/admin/draft_orders.json',
//            'DATA' =>
//                [
//                    "draft_order" => [
//                        'line_items' => $line_items,
//                        "customer" => [
//                            "id" => $request->input('customer_id'),
//                        ],
//                        "shipping_address" => [
//                            "address1" => $request->input('receipent_address1'),
//                            "address2" => $request->input('receipent_address2'),
//                            "city" => $request->input('receipent_city'),
//                            "company" => $request->input('receipent_business'),
//                            "first_name" => $request->input('receipent_first_name'),
//                            "last_name" => $request->input('receipent_last_name'),
//                            "province" => $request->input('receipent_state'),
//                            "country" => $request->input('receipent_country'),
//                            "phone" => $request->input('receipent_phone'),
//                            "zip" => $request->input('receipent_postecode'),
//                            "name" => $request->input('receipent_first_name') . ' ' . $request->input('receipent_last_name'),
//                            "country_code" => Countries::getCode($request->input('receipent_country')),
//                            "province_code" => CountrySubdivisions::getCode($request->input('receipent_country'), $request->input('receipent_state'))
//                        ],
//                        "billing_address" => [
//                            "address1" => $request->input('billing_address1'),
//                            "address2" => $request->input('billing_address2'),
//                            "city" => $request->input('billing_city'),
//                            "company" => $request->input('billing_business'),
//                            "first_name" => $request->input('billing_first_name'),
//                            "last_name" => $request->input('billing_last_name'),
//                            "province" => $request->input('billing_state'),
//                            "country" => $request->input('billing_country'),
//                            "zip" => $request->input('billing_postecode'),
//                            "name" => $request->input('billing_first_name') . ' ' . $request->input('billing_last_name'),
//                            "country_code" => Countries::getCode($request->input('billing_country')),
//                            "province_code" => CountrySubdivisions::getCode($request->input('billing_country'), $request->input('billing_state'))
//                        ],
//                        "shipping_line" => [
//                            "custom" => true,
//                            "price" => $request->input('new_shipping_price'),
//                            "title" => $request->input('shipping_method')
//                        ],
////                        "use_customer_default_address" => false
//                    ]
//
//                ]
//        ]);


    }
}
