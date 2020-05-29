<?php

namespace App\Http\Controllers;

use App\ManagerLog;
use App\User;
use App\Wishlist;
use App\WishlistAttachment;
use App\WishlistThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    public function create_wishlist(Request $request){
        $manager = User::find($request->input('manager_id'));
        if($manager != null){
            $wish = new Wishlist();
            $wish->product_name = $request->input('product_name');
            $wish->cost = $request->input('cost');
            $wish->monthly_sales = $request->input('monthly_sales');
            $wish->description = $request->input('description');
            $wish->reference = $request->input('reference');
            $wish->status_id = '1';
            $wish->manager_id = $manager->id;
            if($request->type == 'user-wishlist'){
                $wish->user_id = Auth::id();
            }
            else{
                $wish->shop_id = $request->input('shop_id');
            }

            $wish->save();
            $wish->has_market()->attach($request->input('countries'));

            if($request->hasFile('attachments')){
                $files = $request->file('attachments');
                foreach ($files as $file){
                    $name = Str::slug($file->getClientOriginalName());
                    $attachement = date("mmYhisa_") . $name;
                    $file->move(public_path() . '/wishlist-attachments/', $attachement);
                    $wa = new WishlistAttachment();
                    $wa->source = $attachement;
                    $wa->wishlist_id = $wish->id;
                    $wa->save();
                }
            }


            return redirect()->back()->with('success','Wishlist created successfully!');

        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }
    public function create_wishlist_thread(Request $request){
        $manager = User::find($request->input('manager_id'));
        $wish = Wishlist::find($request->input('wishlist_id'));
        if($manager != null && $wish != null){
            $thread = new WishlistThread();
            $thread->reply = $request->input('reply');
            $thread->source = $request->input('source');
            $thread->manager_id = $manager->id;
            $thread->user_id = $request->input('user_id');
            $thread->shop_id = $request->input('shop_id');
            $thread->wishlist_id = $request->input('wishlist_id');
            $thread->save();

            $wish->updated_at = now();
            $wish->save();

            if($request->hasFile('attachments')){
                $files = $request->file('attachments');
                foreach ($files as $file){
                    $name = Str::slug($file->getClientOriginalName());
                    $attachement = date("mmYhisa_") . $name;
                    $file->move(public_path() . '/wishlist-attachments/', $attachement);
                    $ta = new WishlistAttachment();
                    $ta->source = $attachement;
                    $ta->thread_id = $thread->id;
                    $ta->save();
                }
            }
            if($request->input('source') == 'manager') {
                $tl = new ManagerLog();
                $tl->message = 'A Reply Added By Manager on Wishlist at ' . date_create($thread->created_at)->format('d M, Y h:i a');
                $tl->status = "Reply From Manager";
                $tl->manager_id = $manager->id;
                $tl->save();
            }

            return redirect()->back()->with('success','Reply sent successfully!');

        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }
    public function approve_wishlist(Request $request){
        $manager = User::find($request->input('manager_id'));
        $wish = Wishlist::find($request->input('wishlist_id'));
        if($manager != null && $wish != null){
            $wish->status_id = 2;
            $wish->approved_price = $request->input('approved_price');
            $wish->updated_at = now();
            $wish->save();
            $tl = new ManagerLog();
            $tl->message = 'Manager Approved Wishlist against price '.number_format($wish->approved_price,2).' at ' . date_create($wish->updated_at)->format('d M, Y h:i a');
            $tl->status = "Manager Approved Wishlist";
            $tl->manager_id = $manager->id;
            $tl->save();

            return redirect()->back()->with('success','Wishlist Approved Successfully!');

        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }

    public function reject_wishlist(Request $request){
        $manager = User::find($request->input('manager_id'));
        $wish = Wishlist::find($request->input('wishlist_id'));
        if($manager != null && $wish != null){
            $wish->status_id = 4;
            $wish->reject_reason = $request->input('reject_reason');
            $wish->updated_at = now();
            $wish->save();
            $tl = new ManagerLog();
            $tl->message = 'Manager Rejected Wishlist against price '.number_format($wish->cost,2).' at ' . date_create($wish->updated_at)->format('d M, Y h:i a');
            $tl->status = "Manager Rejected Wishlist";
            $tl->manager_id = $manager->id;
            $tl->save();

            return redirect()->back()->with('success','Wishlist Rejected Successfully!');

        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }

    public function accept_wishlist(Request $request){
        $manager = User::find($request->input('manager_id'));
        $wish = Wishlist::find($request->input('wishlist_id'));
        if($manager != null && $wish != null){
            $wish->status_id = 3;
            $wish->updated_at = now();
            $wish->save();
            return redirect()->back()->with('success','Wishlist Accepted Successfully!');
        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }

    public function completed_wishlist(Request $request){
        $manager = User::find($request->input('manager_id'));
        $wish = Wishlist::find($request->input('wishlist_id'));
        if($manager != null && $wish != null){
            $wish->status_id = 5;
            $wish->related_product_id = $request->input('link_product_id');
            $wish->updated_at = now();
            $wish->save();
            return redirect()->back()->with('success','Wishlist Completed Successfully!');
        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }


}
