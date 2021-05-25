<?php

namespace App\Http\Controllers;

use App\AdminSetting;
use App\Mail\OrderPlaceEmail;
use App\OrderLog;
use App\OrderTransaction;
use App\RetailerOrder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Charge;
use Stripe\Stripe;

class StripeController extends Controller
{
    private $admin;
    private $inventory;
    private $log;

    public function __construct()
    {
        $this->admin = new AdminMaintainerController();
        $this->inventory = new InventoryController();
        $this->log = new ActivityLogController();
    }

    public function processPayment(Request $request)
    {
        $retailer_order = RetailerOrder::find($request->order_id);
        $settings = AdminSetting::first();

        Stripe::setApiKey($settings->stripe_private);
        Charge::create ([
            "amount" => $request->amount_to_be_paid * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Order Payment for ". $retailer_order->admin_shopify_name
        ]);

        /*Order placing email*/
        $user = User::find($retailer_order->user_id);
        $manager_email = null;
        if($user->has_manager()->count() > 0) {
            $manager_email = $user->has_manager->email;
        }
        $users_temp =['info@wefullfill.com',$manager_email];

        foreach($users_temp as $u){
            if($u != null) {
                try{
                    Mail::to($u)->send(new OrderPlaceEmail($retailer_order));
                }
                catch (\Exception $e){
                }
            }
        }

        /*Order Processing*/
        $new_transaction = new OrderTransaction();
        $new_transaction->amount =  $request->amount_to_be_paid;
        if($retailer_order->custom == 0){
            $new_transaction->name = $retailer_order->has_store->shopify_domain;
        }
        else{
            $new_transaction->name = Auth::user()->email;
        }

        $new_transaction->retailer_order_id = $retailer_order->id;
        $new_transaction->user_id = $retailer_order->user_id;
        $new_transaction->shop_id = $retailer_order->shop_id;
        $new_transaction->save();

        /*Changing Order Status*/
        $retailer_order->paid = 1;
        if(count($retailer_order->fulfillments) > 0){
            $retailer_order->status = $retailer_order->getStatus($retailer_order);
        }
        else{
            $retailer_order->status = 'Paid';
        }
        $retailer_order->save();

        /*Maintaining Log*/
        $order_log =  new OrderLog();
        $order_log->message = "An amount of ".$new_transaction->amount." USD paid to WeFullFill through Credit Card on ".date_create($new_transaction->created_at)->format('d M, Y h:i a')." for further process";
        $order_log->status = "paid";
        $order_log->retailer_order_id = $retailer_order->id;
        $order_log->save();


        //$this->admin->sync_order_to_admin_store($retailer_order);
        $this->inventory->OrderQuantityUpdate($retailer_order,'new');
//                try {
//                    $this->admin->push_to_mabang($retailer_order->id);
//                }
//                catch (\Exception $e) {
//                    $log = new ErrorLog();
//                    $log->message = "ERP order pushing bug from single order wallet payment: ". $retailer_order->id . ': '. $e->getMessage();
//                    $log->save();
//                }

        $this->log->store($retailer_order->user_id, 'Order', $retailer_order->id, $retailer_order->name, 'Order Payment Paid');


        return redirect()->back()->with('success', 'Order Payment completed successful!');

    }
}
