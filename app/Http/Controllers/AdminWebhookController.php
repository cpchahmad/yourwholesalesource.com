<?php

namespace App\Http\Controllers;

use App\FulfillmentLineItem;
use App\OrderFulfillment;
use App\OrderLog;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use Illuminate\Http\Request;

class AdminWebhookController extends Controller
{
    private $helper;
    private $notify;

    /**
     * AdminWebhookController constructor.
     * @param $helper
     * @param $notify
     */
    public function __construct()
    {
        $this->helper = new HelperController();
        $this->notify = new NotificationController();
    }


    public function set_fulfillments($data){
        $retailer_order = RetailerOrder::where('admin_shopify_id', $data->order_id)->first();
        if ($retailer_order != null && $retailer_order->paid == 1) {
            if ($retailer_order->custom == 1) {

                /*Order Fullfillment Record*/
                $new_fulfillment = new OrderFulfillment();
                $count = count($retailer_order->fulfillments) + 1;
                $new_fulfillment->name = $retailer_order->name . '.F' . $count;
                $new_fulfillment->retailer_order_id = $retailer_order->id;
                $new_fulfillment->status = 'fulfilled';
                $new_fulfillment->save();

                $this->after_fullfiment_process($new_fulfillment, $retailer_order, $data);
            }
            else {
                $shop = $this->helper->getSpecificShop($retailer_order->shop_id);
                $shopify_fulfillment = null;
                if ($shop != null) {
                    $location_response = $shop->api()->rest('GET', '/admin/locations.json');
                    if (!$location_response->errors) {
                        $fulfill_data = [
                            "fulfillment" => [
                                "location_id" => $location_response->body->locations[0]->id,
                                "tracking_number" => null,
                                "tracking_url" => null,
                                "line_items" => [

                                ]
                            ]
                        ];
                        if (count($data->tracking_numbers) > 0) {
                            $fulfill_data['fulfillment']['tracking_number'] = $data->tracking_numbers[0];
                        }
                        if (count($data->tracking_urls) > 0) {
                            $fulfill_data['fulfillment']['tracking_url'] = $data->tracking_urls[0];
                        }

                        foreach ($data->line_items as $line_item) {
                            $item = RetailerOrderLineItem::where('sku', $line_item->sku)->where('retailer_order_id',$retailer_order->id)->first();
                            if ($item != null) {
                                $fulfill_quantity =$item->fulfillable_quantity -  $line_item->fulfillable_quantity;
                                array_push($fulfill_data['fulfillment']['line_items'], [
                                    "id" => $item->retailer_product_variant_id,
                                    "quantity" => $fulfill_quantity,
                                ]);
                            }

                        }
                        $response = $shop->api()->rest('POST','/admin/orders/'.$retailer_order->shopify_order_id.'/fulfillments.json',$fulfill_data);
                        if(!$response->errors){

                            /*Order Fullfillment Record*/
                            $new_fulfillment = new OrderFulfillment();
                            $new_fulfillment->fulfillment_shopify_id = $response->body->fulfillment->id;
                            $new_fulfillment->name = $response->body->fulfillment->name;
                            $new_fulfillment->retailer_order_id = $retailer_order->id;
                            $new_fulfillment->status = 'fulfilled';
                            $new_fulfillment->save();

                            /*Order Log*/
                            $this->after_fullfiment_process($new_fulfillment, $retailer_order, $data);


                        }
                    }
                }
            }
        }
    }

    /**
     * @param  $data
     * @param $retailer_order
     * @return array
     */
    public function set_line_item_fullfill_status($data, $retailer_order): array
    {
        foreach ($data->line_items as $item) {
            $line_item = RetailerOrderLineItem::where('sku', $item->sku)->where('retailer_order_id', $retailer_order->id)->first();
            if ($line_item != null) {
                if ($item->fulfillable_quantity == 0) {
                    $line_item->fulfillment_status = 'fulfilled';
                    $line_item->fulfillable_quantity = 0;
                    $line_item->save();
                } else {
                    $line_item->fulfillment_status = 'partially-fulfilled';
                    $line_item->fulfillable_quantity = $item->fulfillable_quantity;
                    $line_item->save();
                }
            }
        }
        $retailer_order->status = $retailer_order->getStatus($retailer_order);
        $retailer_order->save();
        return array($item, $line_item);
    }

    /**
     * @param OrderFulfillment $new_fulfillment
     * @param $retailer_order
     * @param $data
     */
    public function after_fullfiment_process(OrderFulfillment $new_fulfillment, $retailer_order, $data): void
    {
        /*Order Log*/
        $order_log = new OrderLog();
        $order_log->message = "A fulfillment named " . $new_fulfillment->name . " has been processed successfully on " . date_create($new_fulfillment->created_at)->format('d M, Y h:i a');
        $order_log->status = "Fulfillment";
        $order_log->retailer_order_id = $retailer_order->id;
        $order_log->save();

        /*Fulfillment Line Item Relationship*/
        foreach ($data->line_items as $item) {
            $line_item = RetailerOrderLineItem::where('sku', $item->sku)->where('retailer_order_id', $retailer_order->id)->first();
            if ($line_item != null) {
                $fulfillment_line_item = new FulfillmentLineItem();
                $fulfillment_line_item->fulfilled_quantity = $line_item->fulfillable_quantity - $item->fulfillable_quantity;
                $fulfillment_line_item->order_fulfillment_id = $new_fulfillment->id;
                $fulfillment_line_item->order_line_item_id = $line_item->id;
                $fulfillment_line_item->save();
            }
        }
        /*Each Line Item Fulfillment Status*/
        list($item, $line_item) = $this->set_line_item_fullfill_status($data, $retailer_order);


        /*Notification*/
        $this->notify->generate('Order', 'Order Fulfillment', $retailer_order->name . ' line items fulfilled', $retailer_order);

        /*If Fulfillment has Tracking Information*/
        if (count($data->tracking_numbers) > 0) {
            $new_fulfillment->tracking_number = $data->tracking_numbers[0];
        }
        if (count($data->tracking_urls) > 0) {
            $new_fulfillment->tracking_url = $data->tracking_urls[0];
        }

        $new_fulfillment->admin_fulfillment_shopify_id = $data->id;
        $new_fulfillment->save();
        if (count($data->tracking_numbers) > 0) {
            $count = 0;
            $fulfillment_count = count($retailer_order->fulfillments);
            foreach ($retailer_order->fulfillments as $f) {
                if ($f->tracking_number != null) {
                    $count++;
                }
            }
            if($retailer_order->status == 'fulfilled'){
                if ($count == $fulfillment_count) {
                    $retailer_order->status = 'shipped';
                } else {
                    $retailer_order->status = 'partially-shipped';
                }
            }

            $retailer_order->save();
            $this->notify->generate('Order', 'Order Tracking Details', $retailer_order->name . ' tracking details added successfully!', $retailer_order);
        }
    }
}
