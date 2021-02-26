<?php

namespace App\Http\Controllers;

use App\DropshipRequest;
use App\DropshipRequestAttachment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Util\Str;

class DropshipRequestController extends Controller
{
    private $helper;
    private $log;

    /**
     * WishlistController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
        $this->log = new ActivityLogController();
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
                    $name = Str::slug($file->getClientOriginalName());
                    $attachement = date("mmYhisa_") . $name;
                    $file->move(public_path() . '/wishlist-attachments/', $attachement);
                    $wa = new DropshipRequestAttachment();
                    $wa->source = $attachement;
                    $wa->wishlist_id = $drop_request->id;
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

}
