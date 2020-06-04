<?php

namespace App\Http\Controllers;

use App\ManagerLog;
use App\RetailerImage;
use App\RetailerProduct;
use App\RetailerProductVariant;
use App\User;
use App\Wishlist;
use App\WishlistAttachment;
use App\WishlistThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WishlistController extends Controller
{

    private $helper;

    /**
     * WishlistController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
    }

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
            if($request->has('has_product')){
                $wish->has_store_product = 1;
                $wish->product_shopify_id = $request->input('product_shopify_id');
            }
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
            if($wish->has_store_product == 1){
               $related_product_id = $this->import_to_store($wish);
               if($related_product_id != null){
                   $wish->status_id = 5;
                   $wish->related_product_id = $related_product_id;
                   $wish->updated_at = now();
                   $wish->save();
                   return redirect()->back()->with('success','Wishlist Completed Successfully!');
               }
               else{
                   return redirect()->back()->with('error','Wishlist cant be completed because user enter shopify id doesnt belong to any product!');
               }

            }
            else{
                $wish->status_id = 5;
                $wish->related_product_id = $request->input('link_product_id');
                $wish->updated_at = now();
                $wish->save();
                return redirect()->back()->with('success','Wishlist Completed Successfully!');
            }

        }

        else{
            return redirect()->back()->with('error','Associated Manager Not Found');
        }
    }


    public function import_to_store(Wishlist $wishlist){
        $shop = $this->helper->getSpecificShop($wishlist->has_store->id);
        $response = $shop->api()->rest('GET', '/admin/api/2019-10/products/'.$wishlist->product_shopify_id.'.json');
        if(!$response->errors){

            $product = $response->body->product;
            $retailerProduct = new RetailerProduct();
            $product->shopify_id = $product->id;
            $retailerProduct->title = $product->title;
            $retailerProduct->description = $product->body_html;
            $retailerProduct->type = $product->product_type;
            $retailerProduct->tags = $product->tags;
            $retailerProduct->vendor = $product->vendor;
            $retailerProduct->price = $wishlist->approved_price;
            $retailerProduct->cost = $wishlist->approved_price;

            if(count($product->variants) > 0){
                $retailerProduct->variants = 1;
            }
            $retailerProduct->status = 1;
            $retailerProduct->fulfilled_by = 'Fantasy';
            $retailerProduct->toShopify = 1;
            $retailerProduct->shop_id = $wishlist->shop_id;
            $retailerProduct->import_from_shopify = 1;
            $retailerProduct->save();

            /*Product Images SYNC*/
            if(count($product->images) > 0){
                foreach ($product->images as $index => $image){
                    $retailerProductImage = new RetailerImage();
                    if(count($image->variant_ids) > 0){
                        $retailerProductImage->isV = 1;
                    }
                    else{
                        $retailerProductImage->isV = 0;
                    }
                    $retailerProductImage->shopify_id = $image->id;
                    $retailerProductImage->product_id = $retailerProduct->id;
                    $retailerProductImage->shop_id =  $wishlist->shop_id;
                    $retailerProductImage->image = $image->src;
                    $retailerProductImage->position = $image->position;
                    $retailerProductImage->save();
                }
            }
            /*Product Variants SYNC*/

            if(count($product->variants) > 0){
                foreach ($product->variants as $index => $variant){
                    $retailerProductVariant = new RetailerProductVariant();
                    $retailerProductVariant->shopify_id = $variant->id;
                    $retailerProductVariant->title = $variant->title;
                    $retailerProductVariant->option1 = $variant->option1;
                    $retailerProductVariant->option2 = $variant->option2;
                    $retailerProductVariant->option3 = $variant->option3;
                    $retailerProductVariant->price = $wishlist->approved_price;
                    $retailerProductVariant->cost = $wishlist->approved_price;
                    $retailerProductVariant->quantity = $variant->inventory_quantity;
                    $retailerProductVariant->sku = $variant->sku;
                    $retailerProductVariant->barcode = $variant->barcode;
                    $retailerProductVariant->product_id = $retailerProduct->id;
                    $retailerProductVariant->shop_id =  $wishlist->shop_id;

                    if($variant->image_id != null){
                        $image_linked = $retailerProduct->has_images()->where('shopify_id',$variant->image_id)->first();
                        $retailerProductVariant->image =$image_linked->id;
                    }

                    if($index == 0){
                        $retailerProduct->quantity = $variant->inventory_quantity;
                        $retailerProduct->weight = $variant->weight;
                        $retailerProduct->sku = $variant->sku;
                        $retailerProduct->barcode = $variant->barcode;
                        $retailerProduct->save();
                    }

                    $retailerProductVariant->save();
                }
            }

            return $retailerProduct->id;
        }
        else{
            return null;
        }


    }


}
