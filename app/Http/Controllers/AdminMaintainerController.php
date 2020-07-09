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

        }
        else{
            foreach ($order->line_items as $item){
                dd($item->linked_real_variant);
            }
        }


//        array_push($line_items, [
//            "variant_id" => $request->input('product_id'),
//            "quantity" => 1,
//        ]);

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
