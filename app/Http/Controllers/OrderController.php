<?php

namespace App\Http\Controllers;

use App\OrderTransaction;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use App\RetailerProduct;
use App\RetailerProductVariant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $helper;

    /**
     * OrderController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function index(Request $request){
        $orders  = RetailerOrder::where('shop_id',$this->helper->getShop()->id)->newQuery();
        $orders = $orders->orderBy('created_at','DESC')->paginate(30);

        return view('single-store.orders.index')->with([
            'orders' => $orders
        ]);
    }



    public function view_order($id){
        $order  = RetailerOrder::find($id);
        if($order != null){
            return view('single-store.orders.view')->with([
                'order' => $order
            ]);
        }
    }

    public function proceed_payment(Request $request){
        $order = RetailerOrder::find($request->input('order_id'));
        if($order != null && $order->paid == 0){
            $last_four = substr($request->input('card_number'),0,3);
            $new_transaction = new OrderTransaction();
            $new_transaction->note = $request->input('note');
            $new_transaction->amount =  $order->cost_to_pay;
            $new_transaction->name = $request->input('card_name');
            $new_transaction->card_last_four = $last_four;
            $new_transaction->retailer_order_id = $order->id;
            $new_transaction->user_id = $order->user_id;
            $new_transaction->shop_id = $order->shop_id;
            $new_transaction->save();

            $order->paid = 1;
            $order->status = 'paid';
            $order->save();
            return redirect()->back()->with('success','Order Transaction Process Successfully And Will Managed By WeFullFill Administration!');
        }
        else{
            return redirect()->back();
        }
    }

    public function delete($id){
        $r = RetailerOrder::find($id);
        foreach ($r->line_items as $i){
            $i->delete();
        }
        foreach ($r->fulfillments as $f){
            foreach ($f->line_items as $item){
                $item->delete();
            }
            $f->delete();
        }
        $r->delete();
        return redirect()->back()->with('success','Order Deleted Successfully!');
    }
    public function getOrders(){
        $shop = $this->helper->getShop();
        $response = $shop->api()->rest('GET', '/admin/api/2019-10/orders.json');
        if(!$response->errors){
            $orders = $response->body->orders;

            foreach ($orders as $index =>$order){
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
                        $local_shop = $this->helper->getLocalShop();
                        if(count($local_shop->has_user) > 0){
                            $new->user_id = $local_shop->has_user[0]->id;
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

                    }
                }
            }
        }
        return redirect()->route('store.orders')->with('success','Orders Synced Successfully');
    }

}
