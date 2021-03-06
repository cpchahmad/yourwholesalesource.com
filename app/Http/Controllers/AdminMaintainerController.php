<?php

namespace App\Http\Controllers;


use App\AdminSetting;
use App\ErrorLog;
use App\Jobs\BulkImportJob;
use App\Mail\OrderPlaceEmail;
use App\Mail\WalletBalanceMail;
use App\OrderFulfillment;
use App\OrderLog;
use App\OrderTransaction;
use App\Product;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use App\User;
use App\WalletLog;
use App\WalletSetting;
use Carbon\Carbon;
use http\Client;
use http\Message\Body;
use http\QueryString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminMaintainerController extends Controller
{
    private $helper;
    private $log;
    private $notify;
    private $inventory;



    /**
     * AdminMaintainerController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
        $this->log = new ActivityLogController();
        $this->notify = new NotificationController();
        $this->inventory = new InventoryController();
    }

    public function getPages() {
        $admin_store = $this->helper->getAdminShop();
        $response = $admin_store->api()->rest('GET', '/admin/api/2019-10/pages/json');

        dd($response);
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
                } else if($item->linked_real_product != null) {
                    $response = $admin_store->api()->rest('GET', '/admin/api/2019-10/products/' . $item->shopify_product_id . '.json');
                    if (!$response->errors) {
                        $shopifyVariants = $response->body->product->variants;
                        $variant_id = $shopifyVariants[0]->id;
                        array_push($line_items, [
                            "variant_id" => $variant_id,
                            "quantity" => $item->quantity,
                        ]);
                    }
                }
                else{
                    array_push($line_items, [
                        "title" => $item->name,
                        "price" => $item->cost,
                        "quantity" => $item->quantity,
                    ]);
                }
            }
        } else {
            foreach ($order->line_items()->where('fulfilled_by', 'fantasy')->get() as $item) {

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
                        "quantity" => $item->quantity,
                    ]);

                }

            }

        }

        if ($order->email == null) {
            $email = 'dispatched@wefullfill.com';
        } else {
//            $email = $order->email;
            $email = 'dispatched@wefullfill.com';
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
                "send_receipt" => false,
                "send_fulfillment_receipt" => false
            ]
        ];

        $response = $admin_store->api()->rest('POST', '/admin/api/2019-10/draft_orders.json', $orderData);
        $location_response = $admin_store->api()->rest('GET', 'admin/api/2020-04/locations.json');


        if (!$response->errors) {
            $draft_order = $response->body->draft_order;
            $admin_order_response = $admin_store->api()->rest('PUT', '/admin/api/2020-04/draft_orders/' . $draft_order->id . '/complete.json');
            if (!$admin_order_response->errors) {
                $admin_order = $admin_order_response->body->draft_order;
                $order->admin_shopify_id = $admin_order->order_id;

                $res = $admin_store->api()->rest('GET', '/admin/api/2020-04/orders/' . $admin_order->order_id . '.json');
                $temp_order = $res->body->order;
                $order->admin_shopify_name = $temp_order->name;

                //$this->log->store($order->user_id, 'Order', $order->id, $order->name, 'Order Pushed to WeFullFill After Payment');


                $order->save();
                /*Fulfillments*/
                $this->already_fulfillment($order, $location_response, $admin_store);

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
            foreach ($location_response->body->locations as $location){
                if($location->name == "WeFullFill"){
                    $data = [
                        "fulfillment" => [
                            "location_id" => $location->id,
                            "tracking_number" => null,
                            "line_items" => [

                            ]
                        ]
                    ];
                }
            }

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
                    $fulfillment->admin_fulfillment_shopify_id = $response->body->fulfillment->id;
                    $fulfillment->save();
                }
            }

        } else {
            return 0;
        }
    }

    public function admin_order_fulfillment_cancel(RetailerOrder $order,OrderFulfillment $fulfillment){
        $admin_shop = $this->helper->getAdminShop();
        $response = $admin_shop->api()->rest('POST','/admin/orders/'.$order->admin_shopify_id.'/fulfillments/'.$fulfillment->admin_fulfillment_shopify_id.'/cancel.json');
    }

    public function admin_order_fulfillment_add_tracking(RetailerOrder $order,OrderFulfillment $fulfillment,$data){
        $admin_shop = $this->helper->getAdminShop();
        $response = $admin_shop->api()->rest('PUT', '/admin/orders/' . $order->admin_shopify_id . '/fulfillments/' . $fulfillment->admin_fulfillment_shopify_id . '.json', $data);
    }

    public function admin_order_fulfillment_edit_tracking(RetailerOrder $order,OrderFulfillment $fulfillment,$data){
        $admin_shop = $this->helper->getAdminShop();
        $response = $admin_shop->api()->rest('PUT', '/admin/orders/' . $order->admin_shopify_id . '/fulfillments/' . $fulfillment->admin_fulfillment_shopify_id . '.json', $data);
    }

    /**
     * @param RetailerOrder $order
     * @param $location_response
     * @param $admin_store
     */
    public function already_fulfillment(RetailerOrder $order, $location_response, $admin_store): void
    {
        if (count($order->fulfillments) > 0) {
            foreach ($order->fulfillments as $fulfillment) {
                if (!$location_response->errors) {
                    foreach ($location_response->body->locations as $location){
                        if($location->name == "WeFullFill"){
                            $data = [
                                "fulfillment" => [
                                    "location_id" => $location->id,
                                    "tracking_number" => null,
                                    "line_items" => [

                                    ]
                                ]
                            ];
                        }
                    }
                    foreach ($fulfillment->line_items as $line_item) {
                        if ($line_item->linked_line_item != null) {
                            array_push($data['fulfillment']['line_items'], [
                                "id" => $line_item->linked_line_item->retailer_product_variant_id,
                                "quantity" => $line_item->fulfilled_quantity,
                            ]);
                        }
                    }
                    //sleep(20);
                    if (count($data['fulfillment']['line_items']) > 0) {
                        $response = $admin_store->api()->rest('POST', '/admin/orders/' . $order->admin_shopify_id . '/fulfillments.json', $data);
                        if (!$response->errors) {
                            $fulfillment->admin_fulfillment_shopify_id = $response->body->fulfillment->id;
                            $fulfillment->save();
                        }
                    }

                }
            }
        }
    }


    public function sendGrid() {

        $users = User::all();
        $contacts = [];

        foreach ($users as $user) {
            array_push($contacts, [
               'email' => $user->email,
               'first_name' => $user->name,
            ]);
        }

        $contacts_payload = [
            'list_ids' => ["33d743f3-a906-4512-83cd-001f7ba5ab33"],
            'contacts' => $contacts
        ];

        $payload = json_encode($contacts_payload);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer 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",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
    }

    public function make_auto_payment(RetailerOrder $new) {
        $settings = WalletSetting::where('user_id', $new->user_id)->first();

        DB::beginTransaction();
        try{
            if($settings && $settings->enable) {

                if($new->paid == 0){

                    $user = User::find($new->user_id);
                    if ($user && $user->has_wallet != null) {
                        $wallet = $user->has_wallet;
                    }

                    if($wallet && $wallet->available >= $new->cost_to_pay) {

                        /*Wallet Deduction*/
                        $wallet->available =   $wallet->available -  $new->cost_to_pay;
                        $wallet->used =  $wallet->used + $new->cost_to_pay;
                        $wallet->save();
                        /*Maintaining Wallet Log*/
                        $wallet_log = new WalletLog();
                        $wallet_log->wallet_id =$wallet->id;
                        $wallet_log->status = "Order Payment";
                        $wallet_log->amount = $new->cost_to_pay;
                        $wallet_log->message = 'An Amount '.number_format($new->cost_to_pay,2).' USD For Order Cost Against Wallet ' . $wallet->wallet_token . ' Deducted At ' . now()->format('d M, Y h:i a');
                        $wallet_log->save();

                        $this->notify->generate('Wallet','Wallet Order Payment','An Amount '.number_format($new->cost_to_pay,2).' USD For Order Cost Against Wallet ' . $wallet->wallet_token . ' Deducted At ' . now()->format('d M, Y h:i a'),$wallet);


                        /*Order placing email*/
                        $user = User::find($new->user_id);
                        $manager_email = null;
                        if($user->has_manager()->count() > 0) {
                            $manager_email = $user->has_manager->email;
                        }
                        $users_temp =['info@wefullfill.com',$manager_email];

                        foreach($users_temp as $u){
                            if($u != null) {
                                try{
                                    Mail::to($u)->send(new OrderPlaceEmail($new));
                                }
                                catch (\Exception $e){
                                }
                            }
                        }



                        /*Order Processing*/
                        $new_transaction = new OrderTransaction();
                        $new_transaction->amount =  $new->cost_to_pay;
                        if($new->custom == 0){
                            $new_transaction->name = $new->has_store->shopify_domain;
                        }
                        else{
                            $new_transaction->name = $user->email;
                        }

                        $new_transaction->retailer_order_id = $new->id;
                        $new_transaction->user_id = $new->user_id;
                        $new_transaction->shop_id = $new->shop_id;
                        $new_transaction->save();


                        /*Changing Order Status*/
                        $new->paid = 1;
                        if(count($new->fulfillments) > 0){
                            $new->status = $new->getStatus($new);
                        }
                        else{
                            $new->status = 'Paid';
                        }
                        $new->pay_by = 'Wallet';
                        $new->save();

                        /*Maintaining Log*/
                        $order_log =  new OrderLog();
                        $order_log->message = "An amount of ".$new_transaction->amount." USD paid to WeFullFill through Wallet on ".date_create($new_transaction->created_at)->format('d M, Y h:i a')." for further process";
                        $order_log->status = "paid";
                        $order_log->retailer_order_id = $new->id;
                        $order_log->save();

                        $this->log->store($new->user_id, 'Order', $new->id, $new->name, 'Order Payment Paid');

                        //$this->admin->sync_order_to_admin_store($new);

                        $this->inventory->OrderQuantityUpdate($new,'new');

//                        try {
//                            $this->push_to_mabang($new->id);
//                        }
//                        catch (\Exception $e) {
//                            $log = new ErrorLog();
//                            $log->message = "ERP order BUG from Auto Wallet Payment: ". $new->id . " : " . $e->getMessage();
//                            $log->save();
//                        }

                    }
                    else{
                        $this->notify->generate('Wallet','Auto Wallet Order Payment Failure','Your Wallet amount is not enough for making payment for '. $new->name .' kindly top-up your wallet',$wallet);

                        $user = User::find($new->user_id);
                        try{
                            Mail::to($user->email)->send(new WalletBalanceMail($wallet));
                        }
                        catch (\Exception $e){
                        }
                    }
                }
            }
            DB::commit();
        }
        catch(\Exception $e) {
            DB::rollBack();
            $log = new ErrorLog();
            $log->message = "Payment issue in order create job for: ".$new->id . ": ". $e->getMessage();
            $log->save();
        }
    }

    public function manualPushToShipStation($id) {
        $this->pushToShipStation($id);

        return redirect()->back()->with('success', 'Order Pushed Successfully');
    }

    public function pushToShipStation($id) {
        $order = RetailerOrder::find($id);
        $admin_settings = AdminSetting::first();

        $url = "https://ssapi.shipstation.com/orders/createorder";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Authorization: Basic ".$admin_settings->ship_station_key,
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $shipping = json_decode($order->shipping_address);


        $bill_to  = [
            "name"=> is_null($shipping->first_name) ? "No customer Found" : $shipping->first_name. ' '.$shipping->last_name,
            "street1"=> is_null($shipping->address1) ? 'No First Address' : $shipping->address1,
            "city"=> is_null($shipping->city) ? 'No City' : $shipping->city,
            "postalCode"=> is_null($shipping->zip) ? 'No Zip' : $shipping->zip,
            "country"=> is_null($shipping->country_code) ? 'No country' : $shipping->country_code,
            "phone"=> isset($shipping->phone) && $shipping->phone != "" ? $shipping->phone : 'No Phone',
        ];

        $ship_to  = [
            "name"=> is_null($shipping->first_name) ? null : $shipping->first_name. ' '.$shipping->last_name,
            "street1"=> is_null($shipping->address1) ? 'No First Address' : $shipping->address1,
            "city"=> is_null($shipping->city) ? 'No City' : $shipping->city,
            "postalCode"=> is_null($shipping->zip) ? 'No Zip' : $shipping->zip,
            "country"=> is_null($shipping->country_code) ? 'No country' : $shipping->country_code,
            "phone"=> isset($shipping->phone) && $shipping->phone != "" ? $shipping->phone : 'No Phone',
        ];

        $line_items = [];
        $images = [];


        foreach ($order->line_items as $index =>  $item) {
            if($item->linked_real_variant != null) {
                if($item->linked_real_variant->has_image == null) {
                    array_push($images, "https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg");
                }
                else {
                    if($item->linked_real_variant->has_image->isV == 1) {
                        array_push($images, "https://app.yourwholesalesource.com/images/variants/".$item->linked_real_variant->has_image->image);
                    }
                    else {
                        array_push($images, "https://app.yourwholesalesource.com/images/".$item->linked_real_variant->has_image->image);
                    }
                }
            }
            else {
                if($item->linked_real_product != null) {
                    if(count($item->linked_real_product->has_images)>0) {
                        if($item->linked_real_product->has_images[0]->isV == 1) {
                            array_push($images, "https://app.yourwholesalesource.com/images/variants".$item->linked_real_product->has_images[0]->image);
                        }
                        else {
                            array_push($images, "https://app.yourwholesalesource.com/images/".$item->linked_real_product->has_images[0]->image);
                        }
                    }
                    else {
                        array_push($images, "https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg");
                    }
                }
                else {
                    array_push($images, "https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg");
                }
            }

            if($item->linked_variant != null)
                $sku = $item->linked_variant->sku;
            elseif($item->linked_product != null)
                $sku = $item->linked_product->sku;
            elseif($item->linked_woocommerce_variant != null)
                $sku = $item->linked_woocommerce_variant->sku;
            elseif($item->linked_dropship_variant != null)
                $sku = $item->linked_dropship_variant->sku;
            else
                $sku = $item->linked_woocommerce_product->sku;

            array_push($line_items, [
                "lineItemKey" => $item->id,
                "name" => str_replace('"', ' ', $item->name),
                "sku" => $sku,
                "quantity" => $item->quantity,
                "imageUrl" => $images[$index],
                "unitPrice"=> $item->cost,
                "shippingAmount"=> $order->shipping_price,
                "fulfillmentSku"=> $sku,
            ]);
        }


        $data = [
            "orderNumber"=> $order->id,
            "orderKey"=> "WYS". $order->id,
            "orderDate"=> $order->created_at,
            "orderStatus"=> "awaiting_shipment",
            "customerId"=> is_null($order->has_customer) ? "No customer Found" : $order->has_customer->id,
            "customerUsername"=> is_null($shipping->first_name) ? "No customer Found" : $shipping->first_name. ' '.$shipping->last_name,
            "customerEmail"=> is_null($order->has_customer) ? "No customer Found" : $order->has_customer->email,
            "amountPaid"=> $order->cost_to_pay,
            "shippingAmount"=> $order->shipping_price,
            "billTo" => $bill_to,
            "shipTo" => $ship_to,
            "items" => $line_items
        ];

        $data = str_replace("\\", '', json_encode($data));


        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);


        $resp = curl_exec($curl);
        curl_close($curl);

        dd($resp);

        $order_log =  new OrderLog();
        $order_log->message = "Order synced to Shipstation on ".date_create($order->created_at)->format('d M, Y h:i a');
        $order_log->status = "Newly Synced";
        $order_log->retailer_order_id = $order->id;
        $order_log->save();
    }

}
