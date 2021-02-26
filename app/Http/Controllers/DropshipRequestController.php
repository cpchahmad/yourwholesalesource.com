<?php

namespace App\Http\Controllers;

use App\DropshipRequest;
use App\DropshipRequestAttachment;
use App\ManagerLog;
use App\User;
use App\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Str;

class DropshipRequestController extends Controller
{
    private $helper;
    private $log;
    private $notify;

    /**
     * WishlistController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
        $this->log = new ActivityLogController();
        $this->notify = new NotificationController();
    }

    public function create_dropship_requests(Request $request) {
        $manager = User::find($request->input('manager_id'));
        if($manager != null){
            $drop_request = new DropshipRequest();
            $drop_request->product_name = $request->input('product_name');
            $drop_request->cost = $request->input('cost');
            $drop_request->weekly_sales = $request->input('weekly_sales');
            $drop_request->description = $request->input('description');
            $drop_request->product_url = $request->input('product_url');
            $drop_request->battery = $request->input('battery');
            $drop_request->packing_size = $request->input('packing_size');
            $drop_request->weight = $request->input('weight');
            $drop_request->relabell = $request->input('relabell');
            $drop_request->re_pack = $request->input('re_pack');
            $drop_request->stock = $request->input('stock');
            $drop_request->option_count = $request->input('option_count');

            $drop_request->status_id = '1';
            $drop_request->manager_id = $manager->id;
            $user = null;
            if($request->type == 'shopify-user-wishlist'){
                $shop = $this->helper->getLocalShop();
                $user = $shop->has_user()->first();
                $drop_request->user_id = $user->id;
                $drop_request->shop_id = $request->input('shop_id');
            }
            else{
                $drop_request->user_id = Auth::id();
                $drop_request->shop_id = $request->input('shop_id');
            }

            $drop_request->save();
            $drop_request->has_market()->attach($request->input('countries'));

            /*Wishlist request email*/
//            $manager_email = $manager->email;
//            $users_temp =['info@wefullfill.com',$manager_email];
//            foreach($users_temp as $u){
//                if($u != null) {
//                    try{
//                        Mail::to($u)->send(new WishlistReqeustMail($drop_request));
//                    }
//                    catch (\Exception $e){
//                    }
//                }
//            }

            if($request->hasFile('attachments')){
                $files = $request->file('attachments');
                foreach ($files as $file){
                    $name = \Illuminate\Support\Str::slug($file->getClientOriginalName());
                    $attachement = date("mmYhisa_") . $name;
                    $file->move(public_path() . '/dropship-attachments/', $attachement);
                    $wa = new DropshipRequestAttachment();
                    $wa->source = $attachement;
                    $wa->dropship_request_id = $drop_request->id;
                    $wa->save();
                }
            }
            $this->log->store($drop_request->user_id, 'Dropship Request', $drop_request->id, $drop_request->product_name,  'Dropship Request Created');

            return redirect()->back()->with('success','Dropship Request created successfully!');
        }
        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }

    public function approve_dropship_request(Request $request){
        $manager = User::find($request->input('manager_id'));
        $drop_request = DropshipRequest::find($request->input('dropship_request_id'));
        if($manager != null && $drop_request != null){
            $drop_request->status_id = 2;
            //$drop_request->approved_price = $request->input('approved_price');
            $drop_request->updated_at = now();
            $drop_request->save();
            $tl = new ManagerLog();
            $tl->message = 'Manager Approved Dropship Request at ' . date_create($drop_request->updated_at)->format('d M, Y h:i a');
            $tl->status = "Manager Approved Wishlist";
            $tl->manager_id = $manager->id;
            $tl->save();

            $user = $drop_request->has_user;
//            try{
//                Mail::to($user->email)->send(new WishlistApproveMail($user, $drop_request));
//            }
//            catch (\Exception $e){
//            }

            $this->notify->generate('Dropship-Request','Dropship Request','Dropship Request named '.$drop_request->product_name.' has been approved by your manager',$drop_request);
            $this->log->store(0, 'Dropship Request', $drop_request->id, $drop_request->product_name, 'Dropship Request Approved');


            return redirect()->back()->with('success','Dropship Request Approved Successfully!');

        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }

    public function reject_dropship_request(Request $request){
        $manager = User::find($request->input('manager_id'));
        $drop_request = DropshipRequest::find($request->input('dropship_request_id'));


        if($manager != null && $drop_request != null){
            $drop_request->status_id = 4;
            $drop_request->reject_reason = $request->input('reject_reason');
            $drop_request->updated_at = now();
            $drop_request->save();

            if($request->has('by_user')){
                $drop_request->rejected_by_use = 1;
            }
            else {
                $tl = new ManagerLog();
                $tl->message = 'Manager Rejected Dropship Request against price '.number_format($drop_request->cost,2).' at ' . date_create($drop_request->updated_at)->format('d M, Y h:i a');
                $tl->status = "Manager Rejected Dropship Request";
                $tl->manager_id = $manager->id;
                $tl->save();
            }

            $drop_request->save();

            $this->notify->generate('Dropship-Request','Dropship Request Rejected','Dropship Request named '.$drop_request->product_name.' has been rejected by your manager',$drop_request);

            $user = $drop_request->has_user;
//            try{
//                Mail::to($user->email)->send(new WishlistRejectMail($user, $drop_request));
//            }
//            catch (\Exception $e){
//            }
            $this->log->store(0, 'Dropship Request', $drop_request->id, $drop_request->product_name, 'Dropship Request Rejected');

            return redirect()->back()->with('success','Dropship Request Rejected Successfully!');

        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }

    public function accept_dropship_request(Request $request){
        $manager = User::find($request->input('manager_id'));
        $drop_request = DropshipRequest::find($request->input('dropship_request_id'));
        if($manager != null && $drop_request != null){
//            if($request->has('has_product')){
//                $drop_request->has_store_product = 1;
//                $drop_request->product_shopify_id = $request->input('product_shopify_id');
//            }
            $drop_request->status_id = 3;
            $drop_request->updated_at = now();
            $drop_request->save();
            $this->notify->generate('Dropship-Request','Dropship Request Accepted','Dropship Request named '.$drop_request->product_name.' has been accepted',$drop_request);

            $this->log->store($drop_request->user_id, 'Dropship Request', $drop_request->id, $drop_request->product_name, 'Dropship Request Accepted');

            return redirect()->back()->with('success','Dropship Request Accepted Successfully!');
        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }

    public function create_shipping_mark($id) {
        $drop_request = DropshipRequest::find($id);

        return view('setttings.dropship-request.create-shipping-mark')->with([
           'drop_request' => $drop_request
        ]);
    }
}
