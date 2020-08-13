<?php

namespace App\Http\Controllers;

use App\AdminSetting;
use App\OrderLog;
use App\OrderTransaction;
use App\RetailerOrder;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaypalController extends Controller
{
    private $helper;
    private $admin;
    private $inventory;

    /**
     * PaypalController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
        $this->admin = new AdminMaintainerController();
        $this->inventory = new InventoryController();
    }

    public function paypal_order_payment(Request $request)
    {
     $retailer_order = RetailerOrder::find($request->id);
     $setting = AdminSetting::all()->first();
     if($retailer_order->paid == 0){
         $items = [];
         $order_total = $retailer_order->cost_to_pay ;

         /*adding order-lime-items for paying through paypal*/
         foreach ($retailer_order->line_items as $item){
             array_push($items,[
                 'name' => $item->title .' - '.$item->variant_title,
                 'price' => $item->cost,
                 'qty' =>$item->quantity
             ]);
         }
         if($retailer_order->shipping_price != null){
             array_push($items,[
                 'name' => 'Shipping Price',
                 'price' => $retailer_order->shipping_price,
                 'qty' =>1
             ]);
         }

         if($setting != null){
             if($setting->payment_charge_percentage != null){
                 $order_total = $order_total + (number_format($retailer_order->cost_to_pay*$setting->paypal_percentage/100,2));
                 array_push($items,[
                     'name' => 'WeFullFill Charges('.$setting->paypal_percentage.'%)',
                     'price' => number_format($retailer_order->cost_to_pay*$setting->paypal_percentage/100,2),
                     'qty' =>1
                 ]);
             }
         }
         $data = [];
         $data['items'] = $items;
         $data['invoice_id'] = 'WeFullFill-Invoice'.rand(1,1000);
         $data['invoice_description'] = "Order #".$retailer_order->name." Invoice";
         $data['return_url'] = route('store.order.paypal.pay.success',$retailer_order->id);
         $data['cancel_url'] = route('store.order.paypal.pay.cancel',$retailer_order->id);
         $data['total'] = $order_total;

         $provider = new ExpressCheckout;
         try {
             $response = $provider->setExpressCheckout($data);

             $retailer_order->paypal_token = $response['TOKEN'];
             $retailer_order->save();

             return redirect($response['paypal_link']);
         }
         catch (\Exception $e){
             return redirect()->back()->with('error','System Process Failure');
         }
     }
     else{
         return redirect()->back()->with('error','This order status is paid');
     }

    }


    public function paypal_payment_cancel(Request $request)
    {   $retailer_order = RetailerOrder::find($request->id);
      if($retailer_order != null){
          if($retailer_order->custom == 0){
              return redirect()->route('store.order.view',$retailer_order->id)->with('error','Paypal Transaction Process cancelled successfully');

          }
          else{
              return redirect()->route('users.order.view',$retailer_order->id)->with('error','Paypal Transaction Process cancelled successfully');

          }
      }
      else{
          return redirect()->route('store.orders')->with('error','Paypal Transaction Process cancelled successfully');
      }
    }
    /*Updated Inventory*/
    public function paypal_payment_success(Request $request)
    {

        $retailer_order = RetailerOrder::find($request->id);
        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING']) && $retailer_order  != null && $retailer_order->paid == 0)
        {
            $retailer_order->paypal_payer_id =$request->PayerID;
            $new_transaction = new OrderTransaction();
            $new_transaction->amount =  $response['AMT'];
            $new_transaction->name = $response['FIRSTNAME'].' '.$response['LASTNAME'];
            $new_transaction->retailer_order_id = $retailer_order->id;
            $new_transaction->paypal_payment_id = $request->PayerID;
            $new_transaction->user_id = $retailer_order->user_id;
            $new_transaction->shop_id = $retailer_order->shop_id;
            $new_transaction->save();

            $retailer_order->paid = 1;
            if(count($retailer_order->fulfillments) > 0){
                $retailer_order->status = $retailer_order->getStatus($retailer_order);

            }
            else{
                $retailer_order->status = 'Paid';
            }

            $retailer_order->pay_by = 'Paypal';
            $retailer_order->save();

            /*Maintaining Log*/
            $order_log =  new OrderLog();
            $order_log->message = "An amount of ".$new_transaction->amount." USD paid to WeFullFill through PAYPAL on ".date_create($new_transaction->created_at)->format('d M, Y h:i a')." for further process";
            $order_log->status = "paid";
            $order_log->retailer_order_id = $retailer_order->id;
            $order_log->save();
            $this->admin->sync_order_to_admin_store($retailer_order);
//            $this->inventory->OrderQuantityUpdate($retailer_order,'new');

            if($retailer_order->custom == 0){
                return redirect()->route('store.order.view',$retailer_order->id)->with('success','Order Transaction Process Successfully And Will Managed By WeFullFill Administration!');
            }
            else{
                return redirect()->route('users.order.view',$retailer_order->id)->with('success','Order Transaction Process Successfully And Will Managed By WeFullFill Administration!');

            }
        }
        else{
            return redirect()->route('store.orders')->with('error','Order Not Found!');
        }

    }

    public function test($id){
        $order = RetailerOrder::find($id);
        $this->admin->sync_order_to_admin_store($order);
    }
}
