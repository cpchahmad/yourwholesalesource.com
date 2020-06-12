<?php

namespace App\Http\Controllers;

use App\Category;
use App\Image;
use App\ManagerLog;
use App\Product;
use App\ProductVariant;
use App\RetailerImage;
use App\RetailerProduct;
use App\RetailerProductVariant;
use App\User;
use App\WarnedPlatform;
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
               if($this->check_product($wish,$request->input('product_shopify_id'))){
                 $response = $this->fetch_product($wish,$request->input('product_shopify_id'));
                if(!$response->errors){
                    $categories = Category::latest()->get();
                    $platforms = WarnedPlatform::all();
                    return view('sales_managers.wishlist.map_product')->with([
                        'product' => $response->body->product,
                        'wishlist' => $wish,
                        'product_shopify_id' => $request->input('product_shopify_id'),
                        'categories' => $categories,
                        'platforms' => $platforms
                    ]);
                }
                else{
                    return redirect()->back()->with('error','Wishlist cant be completed because user enter shopify id doesnt belong to any product!');
                }

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

    public function check_product(Wishlist $wishlist,$shopify_product_id){
        $response = $this->fetch_product($wishlist, $shopify_product_id);
        if(!$response->errors){
            return 1;
        }
        else{
            return 0;
        }
    }

    public function map_product(Request $request){

        $wish = Wishlist::find($request->input('wishlist_id'));
        $response = $this->fetch_product($wish,$request->input('product_shopify_id'));
        if($wish !=null ){
            if(!$response->errors){
                /*Create and Synced Product to Admin*/
                $product =  $this->create_sync_product_to_admin($request, $response);
                /*Import Product to requested store*/
                $related_product_id = $this->import_to_store($wish,$request->input('product_shopify_id'),$product->id);
                $wish->status_id = 5;
                $wish->related_product_id = $related_product_id;
                $wish->updated_at = now();
                $wish->save();
                return redirect()->back()->with('success','Wishlist Completed Successfully!');
            }
            else{
                return redirect()->route('sales_managers.wishlist')->with('errors','Product Not Found on respective store, cant complete the wishlist process!');

            }
        }
        else{
            return redirect()->route('sales_managers.wishlist')->with('errors','Wishlist Not Found!');
        }
    }

    public function import_to_store(Wishlist $wishlist,$shopify_product_id,$linked_product_id){
        $response = $this->fetch_product($wishlist, $shopify_product_id);
        if(!$response->errors){
            $product = $response->body->product;
            return $this->map_to_retailer_product($wishlist, $product,$linked_product_id);
        }
        else{
            return null;
        }


    }

    /**
     * @param Wishlist $wishlist
     * @param $product
     * @return mixed
     */
    public function map_to_retailer_product(Wishlist $wishlist, $product,$linked_product_id): mixed
    {
        $retailerProduct = new RetailerProduct();
        $retailerProduct->shopify_id = $product->id;
        $retailerProduct->title = $product->title;
        $retailerProduct->description = $product->body_html;
        $retailerProduct->type = $product->product_type;
        $retailerProduct->tags = $product->tags;
        $retailerProduct->vendor = $product->vendor;
        $retailerProduct->price = $wishlist->approved_price;
        $retailerProduct->cost = $wishlist->approved_price;

        if (count($product->variants) > 0) {
            $retailerProduct->variants = 1;
        }
        $retailerProduct->status = 1;
        $retailerProduct->fulfilled_by = 'Fantasy';
        $retailerProduct->toShopify = 1;
        $retailerProduct->shop_id = $wishlist->shop_id;
        $retailerProduct->import_from_shopify = 1;
        $retailerProduct->save();

        /*Product Images SYNC*/
        if (count($product->images) > 0) {
            foreach ($product->images as $index => $image) {
                $retailerProductImage = new RetailerImage();
                if (count($image->variant_ids) > 0) {
                    $retailerProductImage->isV = 1;
                } else {
                    $retailerProductImage->isV = 0;
                }
                $retailerProductImage->shopify_id = $image->id;
                $retailerProductImage->product_id = $retailerProduct->id;
                $retailerProductImage->shop_id = $wishlist->shop_id;
                $retailerProductImage->image = $image->src;
                $retailerProductImage->position = $image->position;
                $retailerProductImage->save();
            }
        }
        /*Product Variants SYNC*/

        if (count($product->variants) > 0) {
            foreach ($product->variants as $index => $variant) {
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
                $retailerProductVariant->shop_id = $wishlist->shop_id;

                if ($variant->image_id != null) {
                    $image_linked = $retailerProduct->has_images()->where('shopify_id', $variant->image_id)->first();
                    $retailerProductVariant->image = $image_linked->id;
                }

                if ($index == 0) {
                    $retailerProduct->quantity = $variant->inventory_quantity;
                    $retailerProduct->weight = $variant->weight;
                    $retailerProduct->sku = $variant->sku;
                    $retailerProduct->barcode = $variant->barcode;
                    $retailerProduct->save();
                }
                $retailerProduct->linked_product_id = $linked_product_id;

                $retailerProductVariant->save();
            }
        }

        return $retailerProduct->id;
    }

    /**
     * @param Wishlist $wishlist
     * @param $shopify_product_id
     * @return mixed
     */
    public function fetch_product(Wishlist $wishlist, $shopify_product_id)
    {
        $shop = $this->helper->getSpecificShop($wishlist->has_store->id);
        $response = $shop->api()->rest('GET', '/admin/api/2019-10/products/' . $shopify_product_id . '.json');
        return $response;
    }

    public function ProductVariants($data, $id)
    {
        for ($i = 0; $i < count($data->variant_title); $i++) {
            $options = explode('/', $data->variant_title[$i]);
            $variants = new  ProductVariant();
            if (!empty($options[0])) {
                $variants->option1 = $options[0];
            }
            if (!empty($options[1])) {
                $variants->option2 = $options[1];
            }
            if (!empty($options[2])) {
                $variants->option3 = $options[2];
            }
            $variants->title = $data->variant_title[$i];
            $variants->price = $data->variant_price[$i];
            $variants->compare_price = $data->variant_comparePrice[$i];
            $variants->quantity = $data->variant_quantity[$i];
            $variants->cost = $data->variant_cost[$i];
            $variants->sku = $data->variant_sku[$i];
            $variants->barcode = $data->variant_barcode[$i];
            $variants->product_id = $id;
            $variants->save();
        }
    }

    public function variants_template_array($product){
        $variants_array = [];
        foreach ($product->hasVariants as $index => $varaint) {
            array_push($variants_array, [
                'title' => $varaint->title,
                'sku' => $varaint->sku,
                'option1' => $varaint->option1,
                'option2' => $varaint->option2,
                'option3' => $varaint->option3,
//                'inventory_quantity' => $varaint->quantity,
//                'inventory_management' => 'shopify',
                'grams' => $product->weight * 1000,
                'weight' => $product->weight,
                'weight_unit' => 'kg',
                'barcode' => $varaint->barcode,
                'price' => $varaint->price,
                'cost' => $varaint->cost,
            ]);
        }
        return $variants_array;
    }

    public function options_template_array($product){
        $options_array = [];
        if (count($product->option1($product)) > 0) {
            $temp = [];
            foreach ($product->option1($product) as $a) {
                array_push($temp, $a);
            }
            array_push($options_array, [
                'name' => 'Option1',
                'position' => '1',
                'values' => json_encode($temp),
            ]);
        }
        if (count($product->option2($product)) > 0) {
            $temp = [];
            foreach ($product->option2($product) as $a) {
                array_push($temp, $a);
            }
            array_push($options_array, [
                'name' => 'Option2',
                'position' => '2',
                'values' => json_encode($temp),
            ]);
        }
        if (count($product->option3($product)) > 0) {
            $temp = [];
            foreach ($product->option3($product) as $a) {
                array_push($temp, $a);
            }
            array_push($options_array, [
                'name' => 'Option3',
                'position' => '3',
                'values' => json_encode($temp),
            ]);
        }
        return $options_array;
    }

    /**
     * @param Request $request
     * @param $response
     * @return Product
     */
    public function create_sync_product_to_admin(Request $request, $response): Product
    {
        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->cost = $request->cost;
        $product->type = $request->product_type;
        $product->vendor = $request->vendor;
        $product->tags = $request->tags;
        $product->quantity = $request->quantity;
        $product->weight = $request->weight;
        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->fulfilled_by = $request->input('fulfilled-by');
        $product->status = $request->input('status');
        $product->processing_time = $request->input('processing_time');

        if ($request->variants) {
            $product->variants = $request->variants;
        }
        $product->save();
        if ($request->category) {
            $product->has_categories()->attach($request->category);
        }
        if ($request->sub_cat) {
            $product->has_subcategories()->attach($request->sub_cat);
        }
        if ($request->platforms) {
            $product->has_platforms()->attach($request->platforms);
        }
        if ($request->variants) {
            $this->ProductVariants($request, $product->id);
        }
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $index => $image) {
                $destinationPath = 'images/';
                $filename = now()->format('YmdHi') . str_replace([' ', '(', ')'], '-', $image->getClientOriginalName());
                $image->move($destinationPath, $filename);
                $image = new Image();
                $image->isV = 0;
                $image->product_id = $product->id;
                $image->position = $index + 1;
                $image->image = $filename;
                $image->save();
            }

        }
        $count_product_images = count($product->has_images);
        if (!$response->errors) {
            $shopify_product = $response->body->product;
            foreach ($shopify_product->images as $index => $image) {
                $image = file_get_contents($image->src);
                $filename = now()->format('YmdHi') . $request->input('title') . rand(12321, 456546464) . 'jpg';
                file_put_contents(public_path('images/' . $filename), $image);
                $image = new Image();
                $image->isV = 0;
                $image->position = $index + 1 + $count_product_images;
                $image->product_id = $product->id;
                $image->image = $filename;
                $image->save();
            }

        }

        /*Import to WeFullFill Store*/
        $variants_array = [];
        $options_array = [];
        $images_array = [];
        //converting variants into shopify api format
        $variants_array = $this->variants_template_array($product, $variants_array);
        /*Product Options*/
        $options_array = $this->options_template_array($product, $options_array);
        /*Product Images*/

        foreach ($product->has_images as $index => $image) {
            if ($image->isV == 0) {
                $src = asset('images') . '/' . $image->image;
            } else {
                $src = asset('images/variants') . '/' . $image->image;
            }
            array_push($images_array, [
                'alt' => $product->title . '_' . $index,
                'position' => $index + 1,
                'src' => $src,
            ]);
        }
        $shop = $this->helper->getAdminShop();
        /*Categories and Subcategories*/
        $tags = $product->tags;
        if (count($product->has_categories) > 0) {
            $categories = implode(',', $product->has_categories->pluck('title')->toArray());
            $tags = $tags . ',' . $categories;
        }
        if (count($product->has_subcategories) > 0) {
            $subcategories = implode(',', $product->has_subcategories->pluck('title')->toArray());
            $tags = $tags . ',' . $subcategories;
        }
        if ($product->status == 1) {
            $published = true;
        } else {
            $published = false;
        }

        $productdata = [
            "product" => [
                "title" => $product->title,
                "body_html" => $product->description,
                "vendor" => $product->vendor,
                "tags" => $tags,
                "product_type" => $product->type,
                "variants" => $variants_array,
                "options" => $options_array,
                "images" => $images_array,
                "published" => $published
            ]
        ];

        $response = $shop->api()->rest('POST', '/admin/api/2019-10/products.json', $productdata);
//            dd($response);
        $product_shopify_id = $response->body->product->id;
        $product->shopify_id = $product_shopify_id;
        $price = $product->price;
        $product->save();

        $shopifyImages = $response->body->product->images;
        $shopifyVariants = $response->body->product->variants;
        if (count($product->hasVariants) == 0) {
            $variant_id = $shopifyVariants[0]->id;
            $i = [
                'variant' => [
                    'price' => $price
                ]
            ];
            $shop->api()->rest('PUT', '/admin/api/2019-10/variants/' . $variant_id . '.json', $i);
        }
        foreach ($product->hasVariants as $index => $v) {
            $v->shopify_id = $shopifyVariants[$index]->id;
            $v->save();
        }
        foreach ($product->has_platforms as $index => $platform) {
            $index = $index + 1;
            $productdata = [
                "metafield" => [
                    "key" => "warned_platform" . $index,
                    "value" => $platform->name,
                    "value_type" => "string",
                    "namespace" => "platform"
                ]
            ];
            $resp = $shop->api()->rest('POST', '/admin/api/2019-10/products/' . $product_shopify_id . '/metafields.json', $productdata);
        }
        if (count($shopifyImages) == count($product->has_images)) {
            foreach ($product->has_images as $index => $image) {
                $image->shopify_id = $shopifyImages[$index]->id;
                $image->save();
            }
        }
        foreach ($product->hasVariants as $index => $v) {
            if ($v->has_image != null) {
                $i = [
                    'image' => [
                        'id' => $v->has_image->shopify_id,
                        'variant_ids' => [$v->shopify_id]
                    ]
                ];
                $imagesResponse = $shop->api()->rest('PUT', '/admin/api/2019-10/products/' . $product_shopify_id . '/images/' . $v->has_image->shopify_id . '.json', $i);
            }
        }
        return $product;
    }

}
