<?php

namespace App\Http\Controllers;

use App\FulfillmentLineItem;
use App\OrderFulfillment;
use App\OrderLog;
use App\Product;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use App\RetailerProduct;
use App\Shop;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use function foo\func;

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
        $orders  = RetailerOrder::whereIn('paid',[1,2])->newQuery();
        if($request->has('search')){
            $orders->where('name','LIKE','%'.$request->input('search').'%');

        }
        $orders = $orders->orderBy('created_at','DESC')->paginate(30);

        return view('orders.index')->with([
            'orders' => $orders,
            'search' => $request->input('search')
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
            if($order->paid == 1){
                return view('orders.fulfillment')->with([
                    'order' => $order
                ]);
            }
            else{
                return redirect()-back()->with('error','Refunded Order Cant Be Processed Fulfillment');
            }

        }
    }
    public function fulfillment_order(Request $request,$id){
        $order  = RetailerOrder::find($id);
        if($order != null){
            if($order->paid == 1){
                $fulfillable_quantities = $request->input('item_fulfill_quantity');
                if($order->custom == 0){
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

                                return $this->set_fulfilments($request, $id, $fulfillable_quantities, $order, $response);
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
                    return $this->set_fulfilments($request, $id, $fulfillable_quantities, $order, '');
                }
            }
            else{
                return redirect()-back()->with('error','Refunded Order Cant Be Processed Fulfillment');
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
            if($order->paid == 1){
                if($order->custom == 0){
                    $shop = $this->helper->getSpecificShop($order->shop_id);
                    if($shop != null){
                        $response = $shop->api()->rest('POST','/admin/orders/'.$order->shopify_order_id.'/fulfillments/'.$fulfillment->fulfillment_shopify_id.'/cancel.json');
                        if($response->errors){
                            return redirect()->back()->with('error','Order Fulfillment Cancellation Failed!');
                        }
                        else{
                            return $this->unset_fullfilment($fulfillment, $order);
                        }
                    }
                    else{
                        return redirect()->back()->with('error','Order Related Store Not Found');

                    }
                }
                else{
                    return $this->unset_fullfilment($fulfillment, $order);
                }
            }
            else{
                return redirect()-back()->with('error','Refunded Order Cant Be Processed Fulfillment');
            }

        }
        else{
            return redirect()->route('admin.order')->with('error','Order Not Found To Cancel Fulfillment');
        }
    }

    public function fulfillment_add_tracking(Request $request){
        $order = RetailerOrder::find($request->id);
        if($order != null ){
            if($order->paid == 1) {
                $fulfillments = $request->input('fulfillment');
                $tracking_numbers = $request->input('tracking_number');
                $tracking_urls = $request->input('tracking_url');
                $tracking_notes = $request->input('tracking_notes');
                if ($order->custom == 0) {
                    $shop = $this->helper->getSpecificShop($order->shop_id);
                    if ($shop != null) {
                        foreach ($fulfillments as $index => $f) {
                            $current = OrderFulfillment::find($f);
                            if ($current != null) {
                                $data = [
                                    "fulfillment" => [
                                        "tracking_number" => $tracking_numbers[$index],
                                        "tracking_url" => $tracking_urls[$index],
                                    ]
                                ];
                                $response = $shop->api()->rest('PUT', '/admin/orders/' . $order->shopify_order_id . '/fulfillments/' . $current->fulfillment_shopify_id . '.json', $data);

                                if (!$response->errors) {
                                    $current->tracking_number = $tracking_numbers[$index];
                                    $current->tracking_url = $tracking_urls[$index];
                                    $current->tracking_notes = $tracking_notes[$index];
                                    $current->save();

                                    /*Maintaining Log*/
                                    $order_log = new OrderLog();
                                    $order_log->message = "Tracking detailed added to fulfillment named " . $current->name . "  successfully on " . now()->format('d M, Y h:i a');
                                    $order_log->status = "Tracking Details Added";
                                    $order_log->retailer_order_id = $order->id;
                                    $order_log->save();
                                }

                            }
                        }
                    } else {
                        return redirect()->back()->with('error', 'Order Related Store Not Found');
                    }
                } else {
                    foreach ($fulfillments as $index => $f) {
                        $current = OrderFulfillment::find($f);
                        if ($current != null) {
                            $current->tracking_number = $tracking_numbers[$index];
                            $current->tracking_url = $tracking_urls[$index];
                            $current->tracking_notes = $tracking_notes[$index];
                            $current->save();

                            /*Maintaining Log*/
                            $order_log = new OrderLog();
                            $order_log->message = "Tracking detailed added to fulfillment named " . $current->name . "  successfully on " . now()->format('d M, Y h:i a');
                            $order_log->status = "Tracking Details Added";
                            $order_log->retailer_order_id = $order->id;
                            $order_log->save();
                        }

                    }
                }
                $count = 0;
                $fulfillment_count = count($order->fulfillments);
                foreach ($order->fulfillments as $f) {
                    if ($f->tracking_number != null) {
                        $count++;
                    }
                }
                if ($count == $fulfillment_count) {
                    $order->status = 'shipped';
                } else {
                    $order->status = 'partially-shipped';
                }

                $order->save();


                return redirect()->back()->with('success', 'Tracking Details Added To Fulfillment Successfully!');
            }
            else{
                return redirect()-back()->with('error','Refunded Order Cant Be Processed Fulfillment');
            }

        }
        else{
            return redirect()->route('admin.order')->with('error','Order Not Found To Add Tracking In Fulfillment');

        }
    }

    public function mark_as_delivered(Request $request){
        $order = RetailerOrder::find($request->id);
        if($order != null) {
            if($order->paid == 1) {
                $order->status = 'delivered';
                $order->save();

                /*Maintaining Log*/
                $order_log =  new OrderLog();
                $order_log->message = "Order marked as delivered successfully on ".now()->format('d M, Y h:i a');
                $order_log->status = "Delivered";
                $order_log->retailer_order_id = $order->id;
                $order_log->save();

                return redirect()->back()->with('success', 'Order Marked as Delivered Successfully');
            }
            else{
                return redirect()-back()->with('error','Refunded Order Cant Be Processed Fulfillment');
            }
        }  else{
            return redirect()->back()->with('error','Order Marked as Delivered Failed');

        }

    }

    public function mark_as_completed(Request $request){
        $order = RetailerOrder::find($request->id);
        if($order != null){
            if($order->paid == 1) {
                $order->status = 'completed';
                $order->save();

                $order_log =  new OrderLog();
                $order_log->message = "Order marked as completed successfully on ".now()->format('d M, Y h:i a');
                $order_log->status = "Completed";
                $order_log->retailer_order_id = $order->id;
                $order_log->save();

                return redirect()->back()->with('success','Order Marked as Completed Successfully');
            }
            else{
                return redirect()-back()->with('error','Refunded Order Cant Be Processed Fulfillment');
            }
        }
        else{
            return redirect()->back()->with('error','Order Marked as Completed Failed');

        }

    }

    /**
     * @param Request $request
     * @param $id
     * @param $fulfillable_quantities
     * @param $order
     * @param $response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function set_fulfilments(Request $request, $id, $fulfillable_quantities, $order, $response): \Illuminate\Http\RedirectResponse
    {
        foreach ($request->input('item_id') as $index => $item) {
            $line_item = RetailerOrderLineItem::find($item);
            if ($line_item != null && $fulfillable_quantities[$index] > 0) {
                if ($fulfillable_quantities[$index] == $line_item->fulfillable_quantity) {
                    $line_item->fulfillment_status = 'fulfilled';

                } else if ($fulfillable_quantities[$index] < $line_item->fulfillable_quantity) {
                    $line_item->fulfillment_status = 'partially-fulfilled';
                }
                $line_item->fulfillable_quantity = $line_item->fulfillable_quantity - $fulfillable_quantities[$index];
            }
            $line_item->save();
        }
        $order->status = $order->getStatus($order);
        $order->save();

        $fulfillment = new OrderFulfillment();
        if($order->custom == 0){
            $fulfillment->fulfillment_shopify_id = $response->body->fulfillment->id;
            $fulfillment->name = $response->body->fulfillment->name;
        }
        else{
            $count = count($order->fulfillments) + 1;
            $fulfillment->name = $order->name.'.F'.$count;
        }
        $fulfillment->retailer_order_id = $order->id;
        $fulfillment->status = 'fulfilled';

        $fulfillment->save();

        /*Maintaining Log*/
        $order_log = new OrderLog();
        $order_log->message = "A fulfillment named " . $fulfillment->name . " has been processed successfully on " . date_create($fulfillment->created_at)->format('d M, Y h:i a');
        $order_log->status = "Fulfillment";
        $order_log->retailer_order_id = $order->id;
        $order_log->save();

        foreach ($request->input('item_id') as $index => $item) {
            if ($fulfillable_quantities[$index] > 0) {
                $fulfillment_line_item = new FulfillmentLineItem();
                $fulfillment_line_item->fulfilled_quantity = $fulfillable_quantities[$index];
                $fulfillment_line_item->order_fulfillment_id = $fulfillment->id;
                $fulfillment_line_item->order_line_item_id = $item;
                $fulfillment_line_item->save();

            }
        }
        return redirect()->route('admin.order.view', $id)->with('success', 'Order Line Items Marked as Fulfilled Successfully!');
    }

    /**
     * @param $fulfillment
     * @param $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unset_fullfilment($fulfillment, $order): \Illuminate\Http\RedirectResponse
    {
        foreach ($fulfillment->line_items as $item) {
            if ($item->linked_line_item != null) {
                $item->linked_line_item->fulfillable_quantity = $item->linked_line_item->fulfillable_quantity + $item->fulfilled_quantity;
                $item->linked_line_item->save();
                if ($item->linked_line_item->fulfillable_quantity < $item->linked_line_item->quantity) {
                    $item->linked_line_item->fulfillment_status = "partially-fulfilled";
                } else if ($item->linked_line_item->fulfillable_quantity == $item->linked_line_item->quantity) {
                    $item->linked_line_item->fulfillment_status = null;
                }
                $item->linked_line_item->save();
            }
            $item->delete();
        }
        $order_log = new OrderLog();
        $order_log->message = "A fulfillment named " . $fulfillment->name . " has been cancelled successfully on " . now()->format('d M, Y h:i a');

        $fulfillment->delete();
        $order->status = $order->getStatus($order);
        $order->save();

        /*Maintaining Log*/

        $order_log->status = "Fulfillment Cancelled";
        $order_log->retailer_order_id = $order->id;
        $order_log->save();

        return redirect()->back()->with('success', 'Order Fulfillment Cancelled Successfully');
    }

    public function dashboard(Request $request)
    {
        if ($request->has('date-range')) {
            $date_range = explode('-',$request->input('date-range'));
            $start_date = $date_range[0];
            $end_date = $date_range[1];
            $comparing_start_date = Carbon::parse($start_date)->format('Y-m-d');
            $comparing_end_date = Carbon::parse($end_date)->format('Y-m-d');

            $orders = RetailerOrder::whereIN('paid',[1,2])->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();
            $sales = RetailerOrder::whereIN('paid',[1,2])->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');
            $refund = RetailerOrder::whereIN('paid',[2])->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->sum('cost_to_pay');
            $stores = Shop::whereNotIn('shopify_domain',['wefullfill.myshopify.com'])->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])->count();


            $ordersQ = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->whereIn('paid',[1,2])
                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
                ->groupBy('date')
                ->get();


            $ordersQR = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->whereIn('paid',[2])
                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
                ->groupBy('date')
                ->get();

            $shopQ = DB::table('shops')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->whereNotIn('shopify_domain',['wefullfill.myshopify.com'])
                ->whereBetween('created_at', [$comparing_start_date, $comparing_end_date])
                ->groupBy('date')
                ->get();





        } else {

            $orders = RetailerOrder::whereIN('paid',[1,2])->count();
            $sales = RetailerOrder::whereIN('paid',[1,2])->sum('cost_to_pay');
            $refund = RetailerOrder::whereIN('paid',[2])->sum('cost_to_pay');
            $stores = Shop::whereNotIn('shopify_domain',['wefullfill.myshopify.com'])->count();

            $ordersQ = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->whereIn('paid',[1,2])
                ->groupBy('date')
                ->get();


            $ordersQR = DB::table('retailer_orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total, sum(cost_to_pay) as total_sum'))
                ->whereIn('paid',[2])
                ->groupBy('date')
                ->get();

            $shopQ = DB::table('shops')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->whereNotIn('shopify_domain',['wefullfill.myshopify.com'])
                ->groupBy('date')
                ->get();
        }


        $graph_one_order_dates = $ordersQ->pluck('date')->toArray();
        $graph_one_order_values = $ordersQ->pluck('total')->toArray();
        $graph_two_order_values = $ordersQ->pluck('total_sum')->toArray();

        $graph_three_order_dates = $ordersQR->pluck('date')->toArray();
        $graph_three_order_values = $ordersQR->pluck('total_sum')->toArray();

        $graph_four_order_dates = $shopQ->pluck('date')->toArray();
        $graph_four_order_values = $shopQ->pluck('total')->toArray();


        $top_products =  Product::join('retailer_order_line_items',function($join) {
            $join->join('retailer_orders',function($o) {
                $o->on('retailer_order_line_items.retailer_order_id','=','retailer_orders.id')
                    ->whereIn('paid',[1,2]);
            });
        })->select('products.*',DB::raw('sum(retailer_order_line_items.quantity) as sold'),DB::raw('sum(retailer_order_line_items.cost) as selling_cost'))
            ->groupBy('products.id')
            ->orderBy('sold','DESC')
            ->get()
            ->take(10);

        $top_stores = Shop::whereNotIn('shopify_domain',['wefullfill.myshopify.com'])
            ->join('retailer_products',function($join) {
            $join->on('retailer_products.shop_id','=','shops.id')
                ->join('retailer_order_line_items',function ($j){
                    $j->on('retailer_order_line_items.shopify_product_id','=','retailer_products.shopify_id')
                        ->join('retailer_orders',function($o){
                            $o->on('retailer_order_line_items.retailer_order_id','=','retailer_orders.id')
                                ->whereIn('paid',[1,2]);
                        });
                });
        })
            ->select('shops.*',DB::raw('sum(retailer_order_line_items.quantity) as sold'),DB::raw('sum(retailer_order_line_items.cost) as selling_cost'))
            ->groupBy('shops.id')
            ->orderBy('sold','DESC')
            ->get()
            ->take(10);

        $top_users = User::role('non-shopify-users')->join('retailer_products',function($join){
            $join->on('retailer_products.user_id','=','users.id')
                ->join('retailer_order_line_items',function ($j){
                    $j->join('products',function ($p){
                        $p->on('retailer_order_line_items.shopify_product_id','=','products.shopify_id');
                    });
                    $j->join('retailer_orders',function($o){
                        $o->on('retailer_order_line_items.retailer_order_id','=','retailer_orders.id')
                            ->whereIn('paid',[1,2]);
                    });
                });
        })
            ->select('users.*',DB::raw('sum(retailer_order_line_items.quantity) as sold'),DB::raw('sum(retailer_order_line_items.cost) as selling_cost'))
            ->groupBy('users.id')
            ->orderBy('sold','DESC')
            ->get()
            ->take(10);

//        dd($top_products);


        return view('welcome')->with([
            'date_range' => $request->input('date-range'),
            'orders' => $orders,
            'refunds' => $refund,
            'sales' =>$sales,
            'stores' => $stores,
            'graph_one_labels' => $graph_one_order_dates,
            'graph_one_values' => $graph_one_order_values,
            'graph_two_values' => $graph_two_order_values,
            'graph_three_labels' => $graph_three_order_dates,
            'graph_three_values' => $graph_three_order_values,
            'graph_four_values' => $graph_four_order_values,
            'graph_four_labels' => $graph_four_order_dates,
            'top_products' => $top_products,
            'top_stores' => $top_stores,
            'top_users' => $top_users
        ]);
    }

}
