<?php

namespace App\Http\Controllers;

use App\FulfillmentLineItem;
use App\OrderFulfillment;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    private $helper;

    /**
     * AdminOrderController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function index(Request $request){
        $orders  = RetailerOrder::where('paid',1)->newQuery();
        $orders = $orders->orderBy('created_at','DESC')->paginate(30);

        return view('orders.index')->with([
            'orders' => $orders
        ]);
    }
    public function view_order($id){
        $order  = RetailerOrder::find($id);
        if($order != null){
            return view('orders.view')->with([
                'order' => $order
            ]);
        }
    }
    public function fulfill_order($id){
        $order  = RetailerOrder::find($id);
        if($order != null){
            return view('orders.fulfillment')->with([
                'order' => $order
            ]);
        }
    }
    public function fulfillment_order(Request $request,$id){
        $order  = RetailerOrder::find($id);
        if($order != null){
            $fulfillable_quantities = $request->input('item_fulfill_quantity');
            $shop = $this->helper->getSpecificShop($order->shop_id);
            $shopify_fulfillment = null;
            if($shop != null){
                $location_response = $shop->api()->rest('GET','/admin/locations.json');
                if(!$location_response->errors){
                    $data = [
                        "fulfillment" => [
                            "location_id"=> $location_response->body->locations[0]->id,
                            "tracking_number"=> null,
                            "line_items" => [

                            ]
                        ]
                    ];
                    foreach ($request->input('item_id') as $index => $item) {
                        $line_item = RetailerOrderLineItem::find($item);
                        if ($line_item != null && $fulfillable_quantities[$index] > 0) {
                            array_push($data['fulfillment']['line_items'], [
                                "id" => $line_item->retailer_product_variant_id,
                                "quantity" => $fulfillable_quantities[$index],
                            ]);
                        }
                    }
                    $response = $shop->api()->rest('POST','/admin/orders/'.$order->shopify_order_id.'/fulfillments.json',$data);
                    if($response->errors){
                        return redirect()->back()->with('error','Cant Fulfill Items of Order in Related Store!');

                    }
                    else{

                        foreach ($request->input('item_id') as $index => $item){
                            $line_item = RetailerOrderLineItem::find($item);
                            if($line_item != null && $fulfillable_quantities[$index] > 0 ){
                                if( $fulfillable_quantities[$index] == $line_item->fulfillable_quantity ){
                                    $line_item->fulfillment_status = 'fulfilled';

                                }
                                else if($fulfillable_quantities[$index] < $line_item->fulfillable_quantity){
                                    $line_item->fulfillment_status = 'partially-fulfilled';
                                }
                                $line_item->fulfillable_quantity = $line_item->fulfillable_quantity - $fulfillable_quantities[$index];
                            }
                            $line_item->save();
                        }
                        $order->status = $order->getStatus($order);
                        $order->save();

                        $fulfillment = new OrderFulfillment();
                        $fulfillment->fulfillment_shopify_id = $response->body->fulfillment->id;
                        $fulfillment->retailer_order_id = $order->id;
                        $fulfillment->status = 'fulfilled';
                        $fulfillment->name = $response->body->fulfillment->name;
                        $fulfillment->save();

                        foreach ($request->input('item_id') as $index => $item){
                            if($fulfillable_quantities[$index] > 0 ){
                                $fulfillment_line_item = new FulfillmentLineItem();
                                $fulfillment_line_item->fulfilled_quantity = $fulfillable_quantities[$index];
                                $fulfillment_line_item->order_fulfillment_id =$fulfillment->id;
                                $fulfillment_line_item->order_line_item_id = $item;
                                $fulfillment_line_item->save();
                            }
                        }
                        return redirect()->route('admin.order.view',$id)->with('success','Order Line Items Marked as Fulfilled Successfully!');
                    }
                }
                else{
                    return redirect()->back()->with('error','Cant Fulfill Item Cause Related Store Dont have Location Stored!');
                }
            }
            else{
                return redirect()->back()->with('error','Order Related Store Not Found');

            }

        }
        else{
            return redirect()->route('admin.order')->with('error','Order Not Found To Process Fulfillment');
        }

    }
    public function fulfillment_cancel_order(Request $request){
        $order = RetailerOrder::find($request->id);
        $fulfillment = OrderFulfillment::find($request->fulfillment_id);
        if($order != null && $fulfillment != null){
            $shop = $this->helper->getSpecificShop($order->shop_id);
            if($shop != null){
                $response = $shop->api()->rest('POST','/admin/orders/'.$order->shopify_order_id.'/fulfillments/'.$fulfillment->fulfillment_shopify_id.'/cancel.json');
                if($response->errors){
                    return redirect()->back()->with('error','Order Fulfillment Cancellation Failed!');
                }
                else{
                    foreach ($fulfillment->line_items as $item){
                        if ($item->linked_line_item != null){
                            $item->linked_line_item->fulfillable_quantity = $item->linked_line_item->fulfillable_quantity + $item->fulfilled_quantity;
                            $item->linked_line_item->save();
                            if($item->linked_line_item->fulfillable_quantity < $item->linked_line_item->quantity){
                                $item->linked_line_item->fulfillment_status = "partially-fulfilled";
                            }
                            else if($item->linked_line_item->fulfillable_quantity == $item->linked_line_item->quantity){
                                $item->linked_line_item->fulfillment_status = null;
                            }
                            $item->linked_line_item->save();
                        }
                        $item->delete();
                    }
                    $fulfillment->delete();
                    $order->status = $order->getStatus($order);
                    $order->save();

                    return redirect()->back()->with('success','Order Fulfillment Cancelled Successfully');
                }
            }
            else{
                return redirect()->back()->with('error','Order Related Store Not Found');

            }
        }
        else{
            return redirect()->route('admin.order')->with('error','Order Not Found To Cancel Fulfillment');
        }
    }

    public function fulfillment_add_tracking(Request $request){
        $order = RetailerOrder::find($request->id);
        if($order != null ){
            $shop = $this->helper->getSpecificShop($order->shop_id);
            if($shop != null){
               $fulfillments = $request->input('fulfillment');
               $tracking_numbers = $request->input('tracking_number');
               $tracking_urls = $request->input('tracking_url');
               $tracking_notes = $request->input('tracking_notes');
               foreach ($fulfillments as $index => $f){
                  $current = OrderFulfillment::find($f);
                  if($current != null){
                      $data = [
                          "fulfillment" => [
                              "tracking_number"=> $tracking_numbers[$index],
                              "tracking_url" =>$tracking_urls[$index],
                          ]
                      ];
                      $response = $shop->api()->rest('PUT','/admin/orders/'.$order->shopify_order_id.'/fulfillments/'.$current->fulfillment_shopify_id.'.json',$data);

                      if(!$response->errors){
                          $current->tracking_number = $tracking_numbers[$index];
                          $current->tracking_url = $tracking_urls[$index];
                          $current->tracking_notes = $tracking_notes[$index];
                          $current->save();
                      }

                  }
               }

               $order->status = 'shipped';
               $order->save();
               return redirect()->back()->with('success','Tracking Details Added To Fulfillment Successfully!');

            }
            else{
                return redirect()->back()->with('error','Order Related Store Not Found');
            }
        }
        else{
            return redirect()->route('admin.order')->with('error','Order Not Found To Add Tracking In Fulfillment');

        }
    }
}
