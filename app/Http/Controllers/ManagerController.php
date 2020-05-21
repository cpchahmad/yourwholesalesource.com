<?php

namespace App\Http\Controllers;

use App\Country;
use App\Customer;
use App\FulfillmentLineItem;
use App\ManagerLog;
use App\OrderFulfillment;
use App\OrderLog;
use App\RetailerOrder;
use App\RetailerOrderLineItem;
use App\RetailerProduct;
use App\Shop;
use App\Ticket;
use App\User;
use App\Wallet;
use App\WalletLog;
use App\WalletRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ManagerController extends Controller
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
    public function tickets(){
        $tickets = Ticket::where('manager_id',Auth::id())->newQuery();
        $tickets = $tickets->paginate(30);
        return view('sales_managers.tickets.index')->with([
            'tickets' => $tickets
        ]);
    }
    public function view_ticket(Request $request){
        $manager = User::find(Auth::id());
        $ticket = Ticket::find($request->id);
        return view('sales_managers.tickets.view')->with([
            'manager' => $manager,
            'ticket' => $ticket,
        ]);
    }

    public function index(Request $request){
        $manager = User::find(Auth::id());
        $orders  = RetailerOrder::where('paid',1)->newQuery();
        $users_array = $manager->has_users()->pluck('id')->toArray();
        $shop_array = $manager->has_sales_stores()->pluck('id')->toArray();
        $orders->WhereIn('user_id',$users_array);
        $orders->orWhereIn('shop_id',$shop_array);
        $orders = $orders->orderBy('created_at','DESC')->paginate(30);
//        dd($orders);
        return view('sales_managers.orders.index')->with([
            'orders' => $orders
        ]);
    }
    public function view_order($id){
        $order  = RetailerOrder::find($id);
        $manager = User::find(Auth::id());
        if($order != null){
            return view('sales_managers.orders.view')->with([
                'order' => $order
            ]);
        }

    }
    public function fulfill_order($id){
        $order  = RetailerOrder::find($id);

        if($order != null){
            return view('sales_managers.orders.fulfillment')->with([
                'order' => $order
            ]);
        }

    }
    public function fulfillment_order(Request $request,$id){
        $order  = RetailerOrder::find($id);
        if($order != null){
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
            return redirect()->route('sales_managers.orders')->with('error','Order Not Found To Process Fulfillment');
        }

    }

    public function fulfillment_cancel_order(Request $request){
        $order = RetailerOrder::find($request->id);
        $fulfillment = OrderFulfillment::find($request->fulfillment_id);
        if($order != null && $fulfillment != null){
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
            return redirect()->route('sales_managers.orders')->with('error','Order Not Found To Cancel Fulfillment');
        }
    }

    public function fulfillment_add_tracking(Request $request){
        $order = RetailerOrder::find($request->id);
        if($order != null ){
            $fulfillments = $request->input('fulfillment');
            $tracking_numbers = $request->input('tracking_number');
            $tracking_urls = $request->input('tracking_url');
            $tracking_notes = $request->input('tracking_notes');
            if($order->custom == 0){
                $shop = $this->helper->getSpecificShop($order->shop_id);
                if($shop != null){
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

                                /*Maintaining Log*/
                                $order_log =  new OrderLog();
                                $order_log->message = "Tracking detailed added to fulfillment named ". $current->name."  successfully on ".now()->format('d M, Y h:i a');
                                $order_log->status = "Tracking Details Added";
                                $order_log->retailer_order_id = $order->id;
                                $order_log->save();

                                $manager = User::find(Auth::id());
                                $ml = new ManagerLog();
                                $ml->message = 'Order '.$order->name.' Tracking detail added to fulfillment named '. $current->name.' by Manager successfully on " ' . now()->format('d M, Y h:i a');
                                $ml->status = "Add Tracking in Order's Fulfillment";
                                $ml->manager_id = $manager->id;
                                $ml->save();
                            }

                        }
                    }
                }
                else{
                    return redirect()->back()->with('error','Order Related Store Not Found');
                }
            }
            else{
                foreach ($fulfillments as $index => $f){
                    $current = OrderFulfillment::find($f);
                    if($current != null){
                        $current->tracking_number = $tracking_numbers[$index];
                        $current->tracking_url = $tracking_urls[$index];
                        $current->tracking_notes = $tracking_notes[$index];
                        $current->save();

                        /*Maintaining Log*/
                        $order_log =  new OrderLog();
                        $order_log->message = "Tracking detailed added to fulfillment named ". $current->name."  successfully on ".now()->format('d M, Y h:i a');
                        $order_log->status = "Tracking Details Added";
                        $order_log->retailer_order_id = $order->id;
                        $order_log->save();

                        $manager = User::find(Auth::id());
                        $ml = new ManagerLog();
                        $ml->message = 'Order '.$order->name.' Tracking detail added to fulfillment named '. $current->name.' by Manager successfully on " ' . now()->format('d M, Y h:i a');
                        $ml->status = "Add Tracking in Order's Fulfillment";
                        $ml->manager_id = $manager->id;
                        $ml->save();
                    }

                }
            }
            $count = 0;
            $fulfillment_count = count($order->fulfillments);
            foreach ($order->fulfillments as $f){
                if($f->tracking_number != null){
                    $count ++;
                }
            }
            if($count == $fulfillment_count){
                $order->status = 'shipped';
            }
            else{
                $order->status = 'partially-shipped';
            }

            $order->save();


            return redirect()->back()->with('success','Tracking Details Added To Fulfillment Successfully!');

        }
        else{
            return redirect()->route('sales_managers.orders')->with('error','Order Not Found To Add Tracking In Fulfillment');

        }
    }

    public function mark_as_delivered(Request $request){
        $order = RetailerOrder::find($request->id);
        if($order != null) {
            $order->status = 'delivered';
            $order->save();

            /*Maintaining Log*/
            $order_log =  new OrderLog();
            $order_log->message = "Order marked as delivered successfully on ".now()->format('d M, Y h:i a');
            $order_log->status = "Delivered";
            $order_log->retailer_order_id = $order->id;
            $order_log->save();

            $manager = User::find(Auth::id());
            $ml = new ManagerLog();
            $ml->message = 'Order '.$order->name.' marked as delivered by Manager successfully on ' . now()->format('d M, Y h:i a');
            $ml->status = "Order Marked as Delivered";
            $ml->manager_id = $manager->id;
            $ml->save();

            return redirect()->back()->with('success', 'Order Marked as Delivered Successfully');
        }  else{
            return redirect()->back()->with('error','Order Marked as Delivered Failed');

        }

    }

    public function mark_as_completed(Request $request){
        $order = RetailerOrder::find($request->id);
        if($order != null){
            $order->status = 'completed';
            $order->save();

            $order_log =  new OrderLog();
            $order_log->message = "Order marked as completed successfully on ".now()->format('d M, Y h:i a');
            $order_log->status = "Completed";
            $order_log->retailer_order_id = $order->id;
            $order_log->save();

            $manager = User::find(Auth::id());
            $ml = new ManagerLog();
            $ml->message = 'Order '.$order->name.' marked as completed by Manager successfully on ' . now()->format('d M, Y h:i a');
            $ml->status = "Order Marked as Completed";
            $ml->manager_id = $manager->id;
            $ml->save();

            return redirect()->back()->with('success','Order Marked as Completed Successfully');
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

        $manager = User::find(Auth::id());
        $ml = new ManagerLog();
        $ml->message = 'Order '.$order->name.' line-items fulfillment by Manager processed successfully on ' . now()->format('d M, Y h:i a');
        $ml->status = "Order Fulfillment";
        $ml->manager_id = $manager->id;
        $ml->save();

        return redirect()->route('sales_managers.order.view', $id)->with('success', 'Order Line Items Marked as Fulfilled Successfully!');
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

        $manager = User::find(Auth::id());
        $ml = new ManagerLog();
        $ml->message = 'Order '.$order->name.' line-items fulfillment by Manager cancelled successfully on ' . now()->format('d M, Y h:i a');
        $ml->status = "Order Fulfillment Cancelled";
        $ml->manager_id = $manager->id;
        $ml->save();

        return redirect()->back()->with('success', 'Order Fulfillment Cancelled Successfully');
    }

    public function stores(Request $request){
        $manager= User::find(Auth::id());
        $stores = $manager->has_sales_stores;
        return view('sales_managers.stores.index')->with([
            'stores'=>$stores
        ]);
    }
    public function store(Request $request){
        $store = Shop::find($request->id);
        if (count($store->has_user) > 0) {
            if ($store->has_user[0]->has_wallet == null) {
                $wallet = null;
            } else {
                $wallet = $store->has_user[0]->has_wallet;
            }
        } else {
            $wallet = null;
        }
        return view('sales_managers.stores.view')->with([
            'store' => $store,
            'wallet' => $wallet
        ]);
    }
    public function product($id){
        $product = RetailerProduct::find($id);
        return view('sales_managers.products.view_product')->with([
            'product' => $product,
        ]);
    }

    public function customer_view($id){
        $customer = Customer::find($id);
        return view('sales_managers.customers.view')->with([
            'customer' => $customer,
        ]);
    }

    public function user(Request $request){
        $user = User::find($request->id);

        if ($user->has_wallet == null) {
            $wallet = null;
        } else {
            $wallet = $user->has_wallet;
        }

        return view('sales_managers.users.view')->with([
            'user' => $user,
            'wallet' => $wallet
        ]);
    }

    public function users(Request $request){
        $manager= User::find(Auth::id());
        $users = $manager->has_users;
        return view('sales_managers.users.index')->with([
            'users'=>$users
        ]);
    }

    public function view_setting(){
        $manager = User::find(Auth::id());
        return view('sales_managers.settings.index')->with([
            'manager' => $manager,
            'countries' => Country::all(),
        ]);
    }

    public function save_personal_info(Request $request){
        $manager = User::find($request->input('manager_id'));
        if($manager != null){
            $manager->name =  $request->input('name');
            $manager->save();
            if($request->hasFile('profile')){
                $file = $request->file('profile');
                $name = Str::slug($file->getClientOriginalName());
                $profile = date("mmYhisa_") . $name;
                $file->move(public_path() . '/managers-profiles/', $profile);
                $manager->profile = $profile;
                $manager->save();
            }
            return redirect()->back()->with('success','Personal Information Updated Successfully!');
        }
        else{
            return redirect()->back()->with('error','Manager Not Found!');
        }
    }
    public function save_address(Request $request){
        $manager = User::find($request->input('manager_id'));
        if($manager != null){
            $manager->address =  $request->input('address');
            $manager->address2 =  $request->input('address2');
            $manager->city =  $request->input('city');
            $manager->state =  $request->input('state');
            $manager->zip =  $request->input('zip');
            $manager->country =  $request->input('country');
            $manager->save();
            return redirect()->back()->with('success','Address Updated Successfully!');

        }
        else{
            return redirect()->back()->with('error','Manager Not Found!');
        }
    }
    public function change_password(Request $request){
        $manager = User::find($request->input('manager_id'));
        if($manager != null){
            $array_to_check = [
                'email' => $manager->email,
                'password' =>$request->input('current_password')
            ];
            if(Auth::validate($array_to_check)){
                if($request->input('new_password') == $request->input('new_password_again')){
                    $manager->password = Hash::make($request->input('new_password'));
                    $manager->save();
                    return redirect()->back()->with('success','Password Changed Successfully!');

                }
                else{
                    return redirect()->back()->with('error','New Password Mismatched!');
                }
            }
            else{
                return redirect()->back()->with('error','Current Password is Invalid!');
            }

        }
        else{
            return redirect()->back()->with('error','Manager Not Found!');
        }
    }

    public function wallet_index(){
        $manager = User::find(Auth::id());
        $users  = $manager->has_users;
        foreach ($users as $user){
            if ($user->has_wallet == null) {
                $this->wallet_create($user->id);
            }
        }
        return view('sales_managers.wallets.index')->with([
            'users' => $users
        ]);
    }

    public function wallet_details(Request $request,$id){
        $wallet = Wallet::find($id);
        $user = User::find($wallet->user_id);
        return view('sales_managers.wallets.wallet_detail')->with([
            'user' => $user,
            'wallet' => $wallet
        ]);
    }

    public function approved_bank_statement($id){
        $req = WalletRequest::find($id);
        if($req->status == 0){
            $related_wallet = Wallet::find($req->wallet_id);
            if($related_wallet!= null){
                $related_wallet->pending =  $related_wallet->pending - $req->amount;
                $related_wallet->available =   $related_wallet->available + $req->amount;
                $related_wallet->save();
                $req->status = 1;
                $req->save();
                $wallet_log = new WalletLog();
                $wallet_log->wallet_id =$related_wallet->id;
                $wallet_log->status = "Bank Transfer Approved";
                $wallet_log->amount = $req->amount;
                $wallet_log->message = 'A Top-up Request of Amount '.number_format($req->amount,2).' USD Through Bank Transfer Against Wallet ' . $related_wallet->wallet_token . ' Approved By Your Manager At ' . now()->format('d M, Y h:i a'). ' By Administration';
                $wallet_log->save();
                return redirect()->back()->with('success','Top-up Request through Bank Transfer Approved Successfully!');
            }
            else{
                return redirect()->back()->with('error','No wallet found related to this request!');
            }
        }
        else{
            return redirect()->back()->with('error','You cant approve an already approved request!');
        }
    }

    public function topup_wallet_by_admin(Request $request){
        $wallet = Wallet::find($request->input('wallet_id'));
        if($wallet != null){
            if($request->input('amount') > 0){
                $wallet->available =  $wallet->available + $request->input('amount');
                $wallet->save();
                $wallet_log = new WalletLog();
                $wallet_log->wallet_id =$wallet->id;
                $wallet_log->status = "Top-up By Manager";
                $wallet_log->amount = $request->input('amount');
                $wallet_log->message = 'A Top-up of Amount '.number_format($request->input('amount'),2).' USD Added Against Wallet ' . $wallet->wallet_token . ' At ' . now()->format('d M, Y h:i a'). ' By Your Manager';
                $wallet_log->save();
                return redirect()->back()->with('success','Wallet Top-up Successfully!');
            }


        }else{
            return redirect()->back()->with('error','Wallet Not Found!');
        }

    }
}
