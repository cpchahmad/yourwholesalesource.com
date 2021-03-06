<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductVariant;
use App\RetailerImage;
use App\RetailerProduct;
use App\RetailerProductVariant;
use App\Shop;
use App\WareHouse;
use App\Wishlist;
use Illuminate\Http\Request;

class RetailerProductController extends Controller
{
    private $helper;
    private $log;

    /**
     * RetailerProductController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
        $this->log = new ActivityLogController();

    }

    public function add_to_import_list(Request $request){
//        dd($this->helper->getLocalShop()->has_user);
        $product = Product::find($request->id);
        if($product != null){
            if(RetailerProduct::where('linked_product_id',$product->id)->where('shop_id',$this->helper->getLocalShop()->id)->exists()){
                return redirect()->back()->with([
                    'info' => 'This Product Already Imported'
                ]);
            }
            else{
                if($request->wishlist_id) {
                    $wishlist = Wishlist::find($request->wishlist_id);
                    $wishlist->imported_to_store = 1;
                    $wishlist->save();
                }

                /*Product Copy*/
                $retailerProduct = new RetailerProduct();

                $retailerProduct->linked_product_id = $product->id;

                $retailerProduct->title = $product->title;
                $retailerProduct->description = $product->description;
                $retailerProduct->type = $product->type;
                $retailerProduct->tags = $product->tags;
                $retailerProduct->vendor = $product->vendor;
                $retailerProduct->price = $product->price;
                $retailerProduct->cost = $product->price;
                $retailerProduct->quantity = $product->quantity;
                $retailerProduct->weight = $product->weight;
                $retailerProduct->sku = $product->sku;
                $retailerProduct->barcode = $product->barcode;
                $retailerProduct->variants = $product->variants;
                $retailerProduct->status = 1;
                $retailerProduct->fulfilled_by = $product->fulfilled_by;
                $retailerProduct->toShopify = 0;
                $retailerProduct->shop_id = $this->helper->getLocalShop()->id;

                if(count($this->helper->getLocalShop()->has_user) > 0){
                    $retailerProduct->user_id = $this->helper->getLocalShop()->has_user[0]->id;
                }

                $retailerProduct->save();
                /*Product Images Copy*/
                if(count($product->has_images) > 0){
                    foreach ($product->has_images()->orderBy('position')->get() as $index => $image){
                        $retailerProductImage = new RetailerImage();
                        $retailerProductImage->isV = $image->isV;
                        $retailerProductImage->product_id = $retailerProduct->id;
                        $retailerProductImage->shop_id =  $retailerProduct->shop_id;
                        $retailerProductImage->user_id =  $retailerProduct->user_id;
                        $retailerProductImage->image = $image->image;
                        $retailerProductImage->position = $index+1;
                        $retailerProductImage->save();
                    }
                }
                /*Product Variants Copy*/
                if($retailerProduct->variants != null){
                    if(count($product->hasVariants) > 0){
                        foreach ($product->hasVariants as $variant){
                            $retailerProductVariant = new RetailerProductVariant();
                            $retailerProductVariant->title = $variant->title;
                            $retailerProductVariant->option1 = $variant->option1;
                            $retailerProductVariant->option2 = $variant->option2;
                            $retailerProductVariant->option3 = $variant->option3;
                            $retailerProductVariant->price = $variant->price;
                            $retailerProductVariant->cost = $variant->price;
                            $retailerProductVariant->quantity = $variant->quantity;
                            $retailerProductVariant->sku = $variant->sku;
                            $retailerProductVariant->barcode = $variant->barcode;

                            $retailerProductVariant->product_id = $retailerProduct->id;
                            $retailerProductVariant->shop_id =  $retailerProduct->shop_id;
                            $retailerProductVariant->user_id =  $retailerProduct->user_id;

                            if($variant->has_image != null){
                                $image_linked = $retailerProduct->has_images()->where('image',$variant->has_image->image)->first();
                                $retailerProductVariant->image =$image_linked->id;
                            }

                            $retailerProductVariant->save();
                        }
                    }
                }

                /*Product Category Copy*/
                $category_ids = $product->has_categories->pluck('id')->toArray();
                $retailerProduct->has_categories()->attach($category_ids);

                /*Product SubCategory Copy*/
                $subcategory_ids = $product->has_subcategories->pluck('id')->toArray();
                $retailerProduct->has_subcategories()->attach($subcategory_ids);

                /*Shop Product Import Relation*/
                $shop = $this->helper->getLocalShop();
                if($shop != null){
                    if(!in_array($product->id,$shop->has_imported->pluck('id')->toArray())){
                        $shop->has_imported()->attach([$product->id]);
                    }
                }
                /*Shop-User Import Relation*/
                if(count($this->helper->getLocalShop()->has_user) > 0){
                    $user = $this->helper->getLocalShop()->has_user[0];
                    if(!in_array($product->id,$user->has_imported->pluck('id')->toArray())){
                        $user->has_imported()->attach([$product->id]);
                    }
                }

                $this->log->store($retailerProduct->user_id, 'RetailerProduct', $retailerProduct->id, $retailerProduct->title, 'Product Added to Import List');

                return redirect()->back()->with([
                    'success' => 'Product Added to Import List Successfully'
                ]);

            }
        }
        else{
            return redirect()->back()->with([
                'error' => 'This Product Cannot Be Imported'
            ]);
        }
    }

    public function add_to_woocommerce_import_list(Request $request){
        $product = Product::find($request->id);
        if($product != null){
            if(RetailerProduct::where('linked_product_id',$product->id)->where('woocommerce_shop_id',$this->helper->getCurrentWooShop()->id)->exists()){
                return redirect()->back()->with([
                    'info' => 'This Product Already Imported'
                ]);
            }
            else{
                if($request->wishlist_id) {
                    $wishlist = Wishlist::find($request->wishlist_id);
                    $wishlist->imported_to_store = 1;
                    $wishlist->save();
                }

                /*Product Copy*/
                $retailerProduct = new RetailerProduct();

                $retailerProduct->linked_product_id = $product->id;

                $retailerProduct->title = $product->title;
                $retailerProduct->description = $product->description;
                $retailerProduct->short_description = $product->short_description;
                $retailerProduct->type = $product->type;
                $retailerProduct->tags = $product->tags;
                $retailerProduct->vendor = $product->vendor;
                $retailerProduct->price = $product->price;
                $retailerProduct->cost = $product->price;
                $retailerProduct->quantity = $product->quantity;
                $retailerProduct->weight = $product->weight;
                $retailerProduct->sku = $product->sku;
                $retailerProduct->barcode = $product->barcode;
                $retailerProduct->variants = $product->variants;
                $retailerProduct->status = 1;
                $retailerProduct->fulfilled_by = $product->fulfilled_by;
                $retailerProduct->to_woocommerce = 0;
                $retailerProduct->attribute1 = $product->attribute1;
                $retailerProduct->attribute2 = $product->attribute2;
                $retailerProduct->attribute3 = $product->attribute3;
                $retailerProduct->length = $product->length;
                $retailerProduct->width = $product->width;
                $retailerProduct->height = $product->height;
                $retailerProduct->woocommerce_shop_id = $this->helper->getCurrentWooShop()->id;


                if(count($this->helper->getCurrentWooShop()->has_owner) > 0){
                    $retailerProduct->user_id = $this->helper->getCurrentWooShop()->has_owner[0]->id;
                }

                $retailerProduct->save();
                /*Product Images Copy*/
                if(count($product->has_images) > 0){
                    foreach ($product->has_images()->orderBy('position')->get() as $index => $image){
                        $retailerProductImage = new RetailerImage();
                        $retailerProductImage->isV = $image->isV;
                        $retailerProductImage->product_id = $retailerProduct->id;
                        $retailerProductImage->shop_id =  $retailerProduct->shop_id;
                        $retailerProductImage->user_id =  $retailerProduct->user_id;
                        $retailerProductImage->image = $image->image;
                        $retailerProductImage->position = $index+1;
                        $retailerProductImage->save();
                    }
                }
                /*Product Variants Copy*/
                if($retailerProduct->variants != null){
                    if(count($product->hasVariants) > 0){
                        foreach ($product->hasVariants as $variant){
                            $retailerProductVariant = new RetailerProductVariant();
                            $retailerProductVariant->title = $variant->title;
                            $retailerProductVariant->option1 = $variant->option1;
                            $retailerProductVariant->option2 = $variant->option2;
                            $retailerProductVariant->option3 = $variant->option3;
                            $retailerProductVariant->price = $variant->price;
                            $retailerProductVariant->cost = $variant->price;
                            $retailerProductVariant->quantity = $variant->quantity;
                            $retailerProductVariant->sku = $variant->sku;
                            $retailerProductVariant->barcode = $variant->barcode;
                            $retailerProductVariant->linked_variant_id = $variant->id;

                            $retailerProductVariant->product_id = $retailerProduct->id;
                            $retailerProductVariant->woocommerce_shop_id =  $retailerProduct->woocommerce_shop_id;
                            $retailerProductVariant->user_id =  $retailerProduct->user_id;

                            if($variant->has_image != null){
                                $image_linked = $retailerProduct->has_images()->where('image',$variant->has_image->image)->first();
                                $retailerProductVariant->image =$image_linked->id;
                            }

                            $retailerProductVariant->save();
                        }
                    }
                }

                /*Product Category Copy*/
                $category_ids = $product->has_categories->pluck('id')->toArray();
                $retailerProduct->has_categories()->attach($category_ids);

                /*Product SubCategory Copy*/
                $subcategory_ids = $product->has_subcategories->pluck('id')->toArray();
                $retailerProduct->has_subcategories()->attach($subcategory_ids);

                /*Shop Product Import Relation*/
                $shop = $this->helper->getCurrentWooShop();
                if($shop != null){
                    if(!in_array($product->id,$shop->has_imported_woocommerce_products->pluck('id')->toArray())){
                        $shop->has_imported_woocommerce_products()->attach([$product->id]);
                    }
                }
                /*Shop-User Import Relation*/
                if(count($this->helper->getCurrentWooShop()->has_owner) > 0){
                    $user = $this->helper->getCurrentWooShop()->has_owner[0];
                    if(!in_array($product->id,$user->has_imported->pluck('id')->toArray())){
                        $user->has_imported()->attach([$product->id]);
                    }
                }

                $this->log->store($retailerProduct->user_id, 'RetailerProduct', $retailerProduct->id, $retailerProduct->title, 'Product Added to Import List');

                return redirect()->back()->with([
                    'success' => 'Product Added to Import List Successfully'
                ]);

            }
        }
        else{
            return redirect()->back()->with([
                'error' => 'This Product Cannot Be Imported'
            ]);
        }
    }


    public function show_updated_product($id) {
        $product = Product::find($id);
        $shop= $this->helper->getLocalShop();
        return view('single-store.products.updated_product')->with([
            'product' => $product,
            'shop' => $shop
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = RetailerProduct::find($id);
        $shop =$this->helper->getShop();
        if ($product != null) {
            if ($request->has('request_type')) {

                /*Single Variant Update Shopify and Database*/
                if ($request->input('request_type') == 'single-variant-update') {
                    $variant = RetailerProductVariant::find($request->variant_id);
                    $variant->price = $request->input('price');
                    $variant->barcode = $request->input('barcode');
                    $variant->product_id = $id;
                    $variant->save();

                    if($product->toShopify == 1){
                        $productdata = [
                            "variant" => [
                                'title' => $variant->title,
                                'option1' => $variant->option1,
                                'option2' => $variant->option2,
                                'option3' => $variant->option3,
                                'grams' => $product->weight * 1000,
                                'weight' => $product->weight,
                                'weight_unit' => 'kg',
                                'barcode' => $variant->barcode,
                                'price' => $variant->price,
                                'cost' => $variant->cost,
                            ]
                        ];
                        $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Variant Updated');

                        $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'/variants/'.$variant->shopify_id.'.json',$productdata);
                    }

                }


                /*Default Variant Update*/
                if ($request->input('request_type') == 'default-variant-update') {

                    $product->price = $request->input('price');
                    $product->quantity = $request->input('quantity');
                    $product->barcode = $request->input('barcode');
                    $product->save();

                    if($product->toShopify == 1){
                        $response = $shop->api()->rest('GET', '/admin/api/2019-10/products/' . $product->shopify_id .'.json');
                        if(!$response->errors){
                            $shopifyVariants = $response->body->product->variants;
                            $variant_id = $shopifyVariants[0]->id;
                            $i = [
                                'variant' => [
                                    'price' =>$product->price,
                                    'grams' => $product->weight * 1000,
                                    'weight' => $product->weight,
                                    'weight_unit' => 'kg',
                                    'barcode' => $product->barcode,

                                ]
                            ];
                            $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Variant Updated');

                            $shop->api()->rest('PUT', '/admin/api/2019-10/variants/' . $variant_id .'.json', $i);
                        }
                    }

                }


                /*Product Basic Update Shopify and Database*/
                if ($request->input('request_type') == 'basic-info') {
                    $product->title = $request->title;
                    $product->type = $request->type;
                    $product->vendor = $request->vendor;
                    $product->tags = $request->tags;
                    $product->save();
                    if ($product->toShopify == 1) {
                        $productdata = [
                            "product" => [
                                "title" => $request->title,
                                "vendor" => $request->vendor,
                                "product_type" => $request->type,
                                "tags" =>$request->tags,
                            ]
                        ];
                        $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Basic Information Updated');

                        $resp = $shop->api()->rest('PUT', '/admin/api/2019-10/products/' . $product->shopify_id . '.json', $productdata);
                    }
                }

                if ($request->input('request_type') == 'description') {
                    $product->description = $request->description;
                    $product->save();
                    if ($product->toShopify == 1) {
                        $productdata = [
                            "product" => [
                                "body_html" => $request->description,
                            ]
                        ];
                        $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Description Updated');

                        $resp = $shop->api()->rest('PUT', '/admin/api/2019-10/products/' . $product->shopify_id . '.json', $productdata);
                    }
                }


                if ($request->input('request_type') == 'variant-image-update') {
//                    dd($request);
                    $variant = RetailerProductVariant::find($request->variant_id);
                    if ($request->hasFile('varaint_src')) {
                        $image = $request->file('varaint_src');
                        $destinationPath = 'images/variants/';
                        $filename = now()->format('YmdHi') . str_replace([' ','(',')'], '-', $image->getClientOriginalName());
                        $image->move($destinationPath, $filename);
                        $image = new RetailerImage();
                        $image->isV = 1;
                        $image->product_id = $product->id;
                        $image->image = $filename;
                        $image->save();
                        $variant->image = $image->id;
                        $variant->save();
                        if ($product->toShopify == 1) {
                            $imageData = [
                                'image' => [
                                    'src' => asset('images/variants') . '/' . $image->image,
                                    'variant_ids' => [$variant->shopify_id]
                                ]
                            ];
                            $imageResponse = $shop->api()->rest('POST', '/admin/api/2019-10/products/' . $product->shopify_id . '/images.json', $imageData);
                            $image->shopify_id = $imageResponse->body->image->id;
                            $image->save();

                        }
                    }
                    $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Variant Image Updated');

                    return redirect()->back();

                }

                if ($request->input('request_type') == 'existing-product-image-delete') {
                    $image =  RetailerImage::find($request->input('file'));
                    if ($product->toShopify == 1) {
                        $shop->api()->rest('DELETE', '/admin/api/2019-10/products/' . $product->shopify_id . '/images/' . $image->shopify_id . '.json');
                    }
                    $image->delete();
                    $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Image Deleted');


                    return response()->json([
                        'success' => 'ok'
                    ]);
                }

                if ($request->input('request_type') == 'existing-product-image-add') {
                    if ($request->hasFile('images')) {
                        foreach ($request->file('images') as $image) {
                            $destinationPath = 'images/';
                            $filename = now()->format('YmdHi') . str_replace(' ', '-', $image->getClientOriginalName());
                            $image->move($destinationPath, $filename);
                            $image = new RetailerImage();
                            $image->isV = 0;
                            $image->product_id = $product->id;
                            $image->image = $filename;
                            $image->save();
                            if ($product->toShopify == 1) {
                                $imageData = [
                                    'image' => [
                                        'src' => asset('images') . '/' . $image->image,
                                    ]
                                ];
                                $imageResponse = $shop->api()->rest('POST', '/admin/api/2019-10/products/' . $product->shopify_id . '/images.json', $imageData);
                                $image->shopify_id = $imageResponse->body->image->id;
                                $image->save();
                                $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Image Added');

                            }
                        }
                    }
                    $product->save();
                }

            }
        }
    }


    public function update_woocommerce_product(Request $request, $id)
    {
        $product = RetailerProduct::find($id);
        $shop =$this->helper->getCurrentWooShop();
        $woocommerce = $this->helper->getWooShop();

        if ($product != null) {
            if ($request->has('request_type')) {

                /*Product Basic Update Shopify and Database*/
                if ($request->input('request_type') == 'basic-info') {
                    $product->title = $request->title;

                    if($request->tags)
                        $product->tags()->sync($request->tags);

                    $product->save();
                    if ($product->to_woocommerce == 1) {

                        /*Updating Tags on Woocommerce */
                        $tags_array = [];
                        foreach ($product->tags()->get() as $tag) {
                            array_push($tags_array, [
                                'id' => $tag->woocommerce_id,
                            ]);
                        }


                        $productdata = [
                            "product" => [
                                "name" => $request->title,
                                "tags" => $tags_array
                            ]
                        ];

                        /*Updating Product On Woocommerce*/
                        $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);

                        $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Basic Information Updated');

                    }
                }

                /*Single Variant Update Shopify and Database*/
                if ($request->input('request_type') == 'single-variant-update') {
                    $variant = RetailerProductVariant::find($request->variant_id);
                    $variant->price = $request->input('price');
                    $variant->barcode = $request->input('barcode');
                    $variant->product_id = $id;
                    $variant->save();

                    if($product->to_woocommerce == 1){

                        $attributes_array = $this->attributes_template_array($product);

                        $productdata = [
                            'attributes' => $attributes_array
                        ];

                        $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);

                        $variants_array = $this->woocommerce_variants_template_array_for_updation($product, $response->attributes);

                        $variantdata = [
                            'update' => $variants_array
                        ];


                        /*Updating Product Variations On Woocommerce*/
                        $response = $woocommerce->post("products/" . $product->woocommerce_id . "/variations/batch", $variantdata);

                        $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Variant Updated');

                    }

                }

                /*Default Variant Update*/
                if ($request->input('request_type') == 'default-variant-update') {

                    $product->price = $request->input('price');
                    $product->quantity = $request->input('quantity');
                    $product->barcode = $request->input('barcode');
                    $product->save();

                    if($product->to_woocommerce == 1){

                        $productdata = [
                            "regular_price" => $product->price,
                            "weight" => $product->weight,
                        ];

                        $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);

                        $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Variant Updated');
                    }

                }


                if ($request->input('request_type') == 'description') {
                    $product->description = $request->description;
                    $product->save();
                    if ($product->toShopify == 1) {
                        $productdata = [
                            "description" => $product->description,
                            "short_description" => $product->short_description,
                        ];

                        $response = $woocommerce->put('products/'. $product->woocommerce_id, $productdata);
                        $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Description Updated');
                    }
                }


                if ($request->input('request_type') == 'variant-image-update') {
//                    dd($request);
                    $variant = RetailerProductVariant::find($request->variant_id);
                    if ($request->hasFile('varaint_src')) {
                        $image = $request->file('varaint_src');
                        $destinationPath = 'images/variants/';
                        $filename = now()->format('YmdHi') . str_replace([' ','(',')'], '-', $image->getClientOriginalName());
                        $image->move($destinationPath, $filename);
                        $image = new RetailerImage();
                        $image->isV = 1;
                        $image->product_id = $product->id;
                        $image->image = $filename;
                        $image->save();
                        $variant->image = $image->id;
                        $variant->save();
                        if ($product->toShopify == 1) {
                            $imageData = [
                                'image' => [
                                    'src' => asset('images/variants') . '/' . $image->image,
                                    'variant_ids' => [$variant->shopify_id]
                                ]
                            ];
                            $imageResponse = $shop->api()->rest('POST', '/admin/api/2019-10/products/' . $product->shopify_id . '/images.json', $imageData);
                            $image->shopify_id = $imageResponse->body->image->id;
                            $image->save();

                        }
                    }
                    $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Variant Image Updated');

                    return redirect()->back();

                }

                if ($request->input('request_type') == 'existing-product-image-delete') {
                    $image =  RetailerImage::find($request->input('file'));
                    $image->delete();

                    if ($product->to_woocommerce == 1) {
                        $images_array = [];

                        foreach ($product->has_images()->limit(10)->get() as $index => $image) {
                            if ($image->isV == 0) {
                                $src = asset('images') . '/' . $image->image;
                            }
                            else {
                                $src = asset('images/variants') . '/' . $image->image;
                            }

                            array_push($images_array, [
                                'alt' => $product->title . '_' . $index,
                                'name' => $product->title . '_' . $index,
                                'src' => $src,
                            ]);
                        }

                        if($product->to_woocommerce == 1) {
                            $productdata = [
                                "images" => $images_array,
                            ];

                            /*Updating Product On Woocommerce*/
                            $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);

                            $woocommerce_images = $response->images;

                            if (count($woocommerce_images) == count($product->has_images()->limit(10)->get())) {
                                foreach ($product->has_images()->limit(10)->get() as $index => $image) {
                                    $image->woocommerce_id = $woocommerce_images[$index]->id;
                                    $image->save();
                                }
                            }
                        }
                    }


                    $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Image Deleted');

                    return response()->json([
                        'success' => 'ok'
                    ]);
                }

                if ($request->input('request_type') == 'existing-product-image-add') {
                    if ($request->hasFile('images')) {
                        foreach ($request->file('images') as $image) {
                            $destinationPath = 'images/';
                            $filename = now()->format('YmdHi') . str_replace(' ', '-', $image->getClientOriginalName());
                            $image->move($destinationPath, $filename);
                            $image = new RetailerImage();
                            $image->isV = 0;
                            $image->product_id = $product->id;
                            $image->image = $filename;
                            $image->save();
                            if ($product->toShopify == 1) {
                                $imageData = [
                                    'image' => [
                                        'src' => asset('images') . '/' . $image->image,
                                    ]
                                ];
                                $imageResponse = $shop->api()->rest('POST', '/admin/api/2019-10/products/' . $product->shopify_id . '/images.json', $imageData);
                                $image->shopify_id = $imageResponse->body->image->id;
                                $image->save();
                                $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Image Added');

                            }
                        }
                    }
                    $product->save();
                }

            }
        }
    }


    public function import_list(Request $request){
        $productQuery = RetailerProduct::where('toShopify',0)->where('shop_id',$this->helper->getLocalShop()->id)->newQuery();
        if($request->has('search')){
            $productQuery->where('title','LIKE','%'.$request->input('search').'%');
        }
        if($request->has('source')){
            if($request->input('source') != 'all'){
                $productQuery->where('fulfilled_by',$request->input('source'));
            }

        }
        $products = $productQuery->paginate(12);
        $shop = $this->helper->getLocalShop();
        $warehouses = WareHouse::all();
        return view('single-store.products.import_list')->with([
            'products' => $products,
            'shop' => $shop,
            'warehouses' => $warehouses,
            'search' => $request->input('search'),
            'source' => $request->input('source'),

        ]);
    }


    public function woocommerce_import_list(Request $request){
        $productQuery = RetailerProduct::where('to_woocommerce',0)->where('woocommerce_shop_id',$this->helper->getCurrentWooShop()->id)->newQuery();
        if($request->has('search')){
            $productQuery->where('title','LIKE','%'.$request->input('search').'%');
        }
        if($request->has('source')){
            if($request->input('source') != 'all'){
                $productQuery->where('fulfilled_by',$request->input('source'));
            }

        }
        $products = $productQuery->paginate(12);
        $shop = $this->helper->getCurrentWooShop();
        $warehouses = WareHouse::all();
        return view('woocommerce-store.products.import_list')->with([
            'products' => $products,
            'shop' => $shop,
            'warehouses' => $warehouses,
            'search' => $request->input('search'),
            'source' => $request->input('source'),

        ]);
    }

    public function delete($id)
    {
        $product = RetailerProduct::find($id);
        $shop = $this->helper->getShop();
        if($product->toShopify == 1 && $product->import_from_shopify == 0){
            $shop->api()->rest('DELETE', '/admin/api/2019-10/products/'.$product->shopify_id.'.json');
        }

        $variants = RetailerProductVariant::where('product_id', $id)->get();
        foreach ($product->hasVariants as $variant) {
            $variant->delete();
        }
        foreach ($product->has_images as $image){
            $image->delete();
        }
        $product->has_categories()->detach();
        $product->has_subcategories()->detach();

        $shop = Shop::find($shop->id);
        $shop->has_imported()->detach([$product->linked_product_id]);
        if(count($shop->has_user) > 0){
            $shop->has_user[0]->has_imported()->detach([$product->linked_product_id]);
        }
        //$this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Deleted');

        $product->delete();
        return redirect()->back()->with('success', 'Product Deleted with Variants Successfully');
    }


    public function delete_woocommerce_product($id)
    {
        $product = RetailerProduct::find($id);
        $shop = $this->helper->getCurrentWooShop();
        $woocommerce = $this->helper->getWooShop();

        if($product->to_woocommerce == 1)  // In future also check for wishlist product
            $woocommerce->delete('products/'.$product->woocommerce_id, ['force' => true]);


        $variants = RetailerProductVariant::where('product_id', $id)->get();
        foreach ($product->hasVariants as $variant) {
            $variant->delete();
        }
        foreach ($product->has_images as $image){
            $image->delete();
        }
        $product->has_categories()->detach();
        $product->has_subcategories()->detach();


        $shop->has_imported_woocommerce_products()->detach([$product->linked_product_id]);
        if(count($shop->has_owner) > 0){
            $shop->has_owner[0]->has_imported()->detach([$product->linked_product_id]);
        }

        $product->delete();
        return redirect()->back()->with('success', 'Product Deleted with Variants Successfully');
    }


    public function my_products(Request $request){
        $productQuery = RetailerProduct::with('has_images')->where('toShopify',1)->where('shop_id',$this->helper->getLocalShop()->id)->newQuery();
        if($request->has('search')){
            $productQuery->where('title','LIKE','%'.$request->input('search').'%');
        }
        if($request->has('source')){
            if($request->input('source') != 'all'){
                $productQuery->Where('fulfilled_by',$request->input('source'));
            }

        }
        $products = $productQuery->paginate(12);
        $shop = $this->helper->getLocalShop();
        return view('single-store.products.my_products')->with([
            'products' => $products,
            'shop' => $shop,
            'search' => $request->input('search'),
            'source' => $request->input('source'),
        ]);
    }

    public function my_woocommerce_products(Request $request){
        $productQuery = RetailerProduct::with('has_images')->where('to_woocommerce',1)->where('woocommerce_shop_id',$this->helper->getCurrentWooShop()->id)->newQuery();
        if($request->has('search')){
            $productQuery->where('title','LIKE','%'.$request->input('search').'%');
        }
        if($request->has('source')){
            if($request->input('source') != 'all'){
                $productQuery->Where('fulfilled_by',$request->input('source'));
            }

        }
        $products = $productQuery->paginate(12);
        $shop = $this->helper->getCurrentWooShop();
        return view('woocommerce-store.products.my_products')->with([
            'products' => $products,
            'shop' => $shop,
            'search' => $request->input('search'),
            'source' => $request->input('source'),
        ]);
    }


    public function my_dropship_products(Request $request){
        $productQuery = RetailerProduct::with('has_images')->where('shop_id',$this->helper->getLocalShop()->id)->where('is_dropship_product', 1)->newQuery();
        if($request->has('search')){
            $productQuery->where('title','LIKE','%'.$request->input('search').'%');
        }
        $products = $productQuery->paginate(12);
        $shop = $this->helper->getLocalShop();
        return view('single-store.products.my_dropship_products')->with([
            'products' => $products,
            'shop' => $shop,
            'search' => $request->input('search'),
        ]);
    }


    public function edit_my_product($id){
        $product = RetailerProduct::find($id);
        $shop= $this->helper->getLocalShop();
        return view('single-store.products.edit_my_product')->with([
            'product' => $product,
            'shop' => $shop
        ]);
    }

    public function edit_my_woocommerce_product($id){
        $product = RetailerProduct::find($id);
        $shop= $this->helper->getCurrentWooShop();
        return view('woocommerce-store.products.edit_my_product')->with([
            'product' => $product,
            'shop' => $shop
        ]);
    }

    public function import_to_shopify(Request $request)
    {

        $product = RetailerProduct::find($request->id);

//        if($request->inventory_status) {
//            $product->manage_inventory = 1;
//            $product->save();
//        }

        if ($product != null && $product->toShopify != 1) {
            $variants_array = [];
            $options_array = [];
            $images_array = [];
            //converting variants into shopify api format
            $variants_array =  $this->variants_template_array($product,$variants_array);
            /*Product Options*/
            $options_array = $this->options_template_array($product,$options_array);
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
            $shop = $this->helper->getShop();
            /*Categories and Subcategories*/
            $tags = $product->tags;
            if(count($product->has_categories) > 0){
                $categories = implode(',',$product->has_categories->pluck('title')->toArray());
                $tags = $tags.','.$categories;
            }
            if(count($product->has_subcategories) > 0){
                $subcategories = implode(',',$product->has_subcategories->pluck('title')->toArray());
                $tags = $tags.','.$subcategories;
            }
            if($product->status == 1){
                $published = true;
            }
            else{
                $published = false;
            }

            if($product->type != null){
                $product_type = $product->type;
            }
            else{
                $product_type = 'AwarenessDropshipping';
            }

            $productdata = [
                "product" => [
                    "title" => $product->title,
                    "body_html" => $product->description,
                    "vendor" => $product->vendor,
                    "tags" => $tags,
                    "product_type" => $product_type,
                    "variants" => $variants_array,
                    "options" => $options_array,
                    "images" => $images_array,
                    "published"=>  $published
                ]
            ];
//            dd($product->has_platforms);

            $response = $shop->api()->rest('POST', '/admin/api/2019-10/products.json', $productdata);

            if($response->errors){
                dd($response);
                return redirect()->back()->with('success','Something Went Wrong!');
            }
            $product_shopify_id =  $response->body->product->id;
            $product->shopify_id = $product_shopify_id;
            $price = $product->price;
            $product->toShopify = 1;
            $product->save();

            $shopifyImages = $response->body->product->images;
            $shopifyVariants = $response->body->product->variants;
            if(count($product->hasVariants) == 0){
                $variant_id = $shopifyVariants[0]->id;
                $product->inventory_item_id =$shopifyVariants[0]->inventory_item_id;
                $i = [
                    'variant' => [
                        'price' =>$price,
                        'sku' =>  $product->sku,
                        'grams' => $product->weight * 1000,
                        'weight' => $product->weight,
                        'weight_unit' => 'kg',
                        'barcode' => $product->barcode,
                        "fulfillment_service" => "AwarenessDropshipping",
//                        'inventory_quantity' => $product->quantity,
                        'inventory_management' => 'AwarenessDropshipping',
                    ]
                ];
                $shop->api()->rest('PUT', '/admin/api/2019-10/variants/' . $variant_id .'.json', $i);

                $data = [
                    "inventory_item" => [
                        'id' => $product->inventory_item_id,
                        "tracked" => true
                    ]

                ];
                $resp = $shop->api()->rest('PUT', '/admin/api/2020-07/inventory_items/' . $product->inventory_item_id . '.json', $data);
                /*Connect to Wefullfill*/
                $data = [
                    'location_id' => $shop->location_id,
                    'inventory_item_id' => $product->inventory_item_id,
                    'relocate_if_necessary' => true
                ];
                $res = $shop->api()->rest('POST', '/admin/api/2020-07/inventory_levels/connect.json', $data);
                /*Set Quantity*/

                $data = [
                    'location_id' => $shop->location_id,
                    'inventory_item_id' => $product->inventory_item_id,
                    'available' => $product->quantity,

                ];

                $res = $shop->api()->rest('POST', '/admin/api/2020-07/inventory_levels/set.json', $data);

            }
            foreach ($product->hasVariants as $index => $v){
                $v->shopify_id = $shopifyVariants[$index]->id;
                $v->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                $v->save();
            }
            if(count($shopifyImages) == count($product->has_images)){
                foreach ($product->has_images as $index => $image){
                    $image->shopify_id = $shopifyImages[$index]->id;
                    $image->save();
                }
            }

            foreach ($product->hasVariants as $index => $v){
                if($v->has_image != null){
                    $i = [
                        'image' => [
                            'id' => $v->has_image->shopify_id,
                            'variant_ids' => [$v->shopify_id]
                        ]
                    ];
                    $imagesResponse = $shop->api()->rest('PUT', '/admin/api/2019-10/products/' . $product_shopify_id . '/images/' . $v->has_image->shopify_id . '.json', $i);
                }
            }
            $this->log->store($product->user_id, 'RetailerProduct', $product->id, $product->title, 'Product Imported To Shopify');
            return redirect()->back()->with('success','Product Push to Store Successfully!');
        }
        else{
            echo 'imported already';
        }
    }

    public function import_to_woocommerce(Request $request)
    {
        $product = RetailerProduct::find($request->id);
        $woocommerce = $this->helper->getWooShop();

        if ($product != null ) {

            /*Product Attributes*/
            $attributes_array = $this->attributes_template_array($product);


            /*Product Dimensions*/
            $dimension_array = array(
                'width' => is_null($product->width) ? "0" : $product->width,
                'height' => is_null($product->height) ? "0" : $product->height,
                'length' => is_null($product->length) ? "0" : $product->length
            );

            /*Product Images*/
            $images_array = [];
            foreach ($product->has_images()->limit(10)->get() as $index => $image) {
                if ($image->isV == 0) {
                    $src = asset('images') . '/' . $image->image;
                } else {
                    $src = asset('images/variants') . '/' . $image->image;
                }
                array_push($images_array, [
                    'alt' => $product->title . '_' . $index,
                    'name' => $product->title . '_' . $index,
                    'position' => $index + 1,
                    'src' => $src,
                ]);
            }

            /*Tags*/
            $tags_array = [];
            if($product->tags()->count() > 0) {
                foreach ($product->tags()->get() as $tag) {
                    array_push($tags_array, [
                        'id' => $tag->woocommerce_id,
                    ]);
                }
            }

            /*Categories*/
            $categories_array = [];

            if(count($product->has_categories) > 0){
                $product_categories = $product->has_categories->pluck('woocommerce_id')->toArray();

                foreach ($product_categories as $category) {
                    array_push($categories_array, [
                        'id' => $category,
                    ]);
                }
            }

            /*SubCategories*/
            if(count($product->has_subcategories) > 0) {
                $product_sub_categories = $product->has_subcategories->pluck('woocommerce_id')->toArray();
                foreach ($product_sub_categories as $category) {
                    array_push($categories_array, [
                        'id' => $category,
                    ]);
                }
            }


            if($product->status == 1)
                $published = 'publish';
            else
                $published = 'draft';


            $attributes = null;
            if($product->variants == 1)
            {
                $product_type = 'variable';
                $attributes = $attributes_array;
            }
            else
                $product_type = 'simple';


            $productdata = [
                "name" => $product->title,
                "description" => $product->description,
                "short_description" => $product->short_description,
                "slug" => $product->slug,
                "tags" => $tags_array,
                "type" => $product_type,
                "images" => $images_array,
                "published"=>  $published,
                "regular_price" => $product->price,
                "sku" => $product->sku,
                "weight" => $product->weight,
                "manage_stock" => true,
                "stock_quantity" => $product->quantity,
                "dimensions" => $dimension_array,
                "categories" => $categories_array,
                "attributes" => $attributes
            ];


            /*Creating Product On Woocommerce*/
            $response = $woocommerce->post('products', $productdata);

            $product_woocommerce_id =  $response->id;
            $product->woocommerce_id = $product_woocommerce_id;
            $product->to_woocommerce = 1;
            $product->save();

            $woocommerce_images = $response->images;


            if (count($woocommerce_images) == count($product->has_images()->limit(10)->get())) {
                foreach ($product->has_images()->limit(10)->get() as $index => $image) {
                    $image->woocommerce_id = $woocommerce_images[$index]->id;
                    $image->save();
                }
            }

            if($product->variants == 1) {
                $variants_array =  $this->woocommerce_variants_template_array($product, $response->attributes);

                $variantdata = [
                    'create' => $variants_array
                ];


                /*Creating Product Variations On Woocommerce*/
                $response = $woocommerce->post("products/".$product_woocommerce_id."/variations/batch", $variantdata);

                $woocommerce_variants = $response->create;
                foreach ($product->hasVariants as $index => $v){
                    $v->woocommerce_id = $woocommerce_variants[$index]->id;
                    $v->save();
                }
            }

            $this->log->store(0, 'Retailer Product', $product->id, $product->title, 'Product Imported To Woocommerce');

            return redirect()->back()->with('success','Product Push to Store Successfully!');
        }
        else{
            echo 'imported already';
        }
    }

    public function attributes_template_array($product){

        $product = RetailerProduct::find($product->id);

        $attributes_array = [];
        $option1_array = [];
        $option2_array = [];
        $option3_array = [];
        if (count($product->option1($product)) > 0) {
            foreach ($product->option1($product) as $a) {
                array_push($option1_array, $a);
            }
        }
        if (count($product->option2($product)) > 0) {
            foreach ($product->option2($product) as $a) {
                array_push($option2_array, $a);
            }
        }
        if (count($product->option3($product)) > 0) {
            foreach ($product->option3($product) as $a) {
                array_push($option3_array, $a);
            }
        }

        if(count($option1_array)>0) {
            array_push($attributes_array, [
                'name' => $product->attribute1,
                'position' => 0,
                'visible' => true,
                'variation' => true,
                'options' => $option1_array
            ]);
        }

        if(count($option2_array)>0) {
            array_push($attributes_array, [
                'name' => $product->attribute2,
                'position' => 1,
                'visible' => true,
                'variation' => true,
                'options' => $option2_array

            ]);
        }
        if(count($option3_array)>0) {
            array_push($attributes_array, [
                'name' => $product->attribute3,
                'position' => 2,
                'visible' => true,
                'variation' => true,
                'options' => $option3_array
            ]);
        }
        return $attributes_array;
    }

    public function woocommerce_variants_template_array($product){
        $product = RetailerProduct::find($product->id);

        if(is_null($product->weight)) {
            $weight = 0.0;
        }
        else {
            $weight = $product->weight;
        }

        $variants_array = [];
        foreach ($product->hasVariants as $index => $varaint) {
            $array_item = [];
            $array_item['attributes'] = [];
            $array_item['image'] = [];

            $array_item['regular_price'] = $varaint->price;
            //$array_item['sale_price'] = $varaint->cost;
            $array_item['sku'] = $varaint->sku;
            $array_item['stock_quantity'] = $varaint->quantity;
            $array_item['manage_stock'] = true;
            $array_item['weight'] = $weight;

            if($varaint->option1 !== null) {
                array_push($array_item['attributes'], [
                    'option' => $varaint->option1,
                    'name' => $product->attribute1,
                ]);
            }
            if($varaint->option2 !== null) {
                array_push($array_item['attributes'], [
                    'option' => $varaint->option2,
                    'name' => $product->attribute2,
                ]);
            }
            if($varaint->option3 !== null) {
                array_push($array_item['attributes'], [
                    'option' => $varaint->option3,
                    'name' => $product->attribute3,

                ]);
            }

            if($varaint->has_image != null && $varaint->has_image->woocommerce_id != null){
                $array_item['image']['id'] = $varaint->has_image->woocommerce_id;
            }
            else {
                $array_item['image'] = null;
            }

            array_push($variants_array, $array_item);
        }

        return $variants_array;
    }

    public function variants_template_array($product){
        if(is_null($product->weight)) {
            $weight = 0.0;
        }
        else {
            $weight = $product->weight;
        }

        $variants_array = [];
        if($product->hasVariants()->count()) {
            foreach ($product->hasVariants as $index => $varaint) {
                array_push($variants_array, [
                    'title' => $varaint->title,
                    'sku' => $varaint->sku,
                    'option1' => $varaint->option1,
                    'option2' => $varaint->option2,
                    'option3' => $varaint->option3,
                    'inventory_quantity' => $varaint->quantity,
                    "fulfillment_service" => "AwarenessDropshipping",
                    'inventory_management' => 'AwarenessDropshipping',
                    'grams' => $weight * 1000,
                    'weight' => $weight,
                    'weight_unit' => 'kg',
                    'barcode' => $varaint->barcode,
                    'price' => $varaint->price,
                    'cost' => $varaint->cost,
                ]);
            }
        }
        else {
            array_push($variants_array, [
                'title' => "Default Title",
                'sku' => $product->sku,
                'option1' => "Default Title",
                'option2' => null,
                'option3' => null,
                'inventory_quantity' => $product->quantity,
                "fulfillment_service" => "AwarenessDropshipping",
                'inventory_management' => 'AwarenessDropshipping',
                'grams' => $weight * 1000,
                'weight' => $weight,
                'weight_unit' => 'kg',
                'price' =>$product->price,
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

    public function syncWithAdminProduct($id) {
        $retailerProduct = RetailerProduct::find($id);
        $product = $retailerProduct->linked_product;
        $shop = $this->helper->getShop();


        if($retailerProduct && $product) {
            if(count($product->hasVariants) > 0){
                foreach ($product->hasVariants as $variant){
                    foreach ($retailerProduct->hasVariants as $retailer_variant) {
                        if($variant->title !== $retailer_variant->title) {

                            $retailerProduct->variants = $product->variants;
                            $retailerProduct->save();


                            $retailerProductVariant = new RetailerProductVariant();
                            $retailerProductVariant->title = $variant->title;
                            $retailerProductVariant->option1 = $variant->option1;
                            $retailerProductVariant->option2 = $variant->option2;
                            $retailerProductVariant->option3 = $variant->option3;
                            $retailerProductVariant->price = $variant->price;
                            $retailerProductVariant->cost = $variant->price;
                            $retailerProductVariant->quantity = $variant->quantity;
                            $retailerProductVariant->sku = $variant->sku;
                            $retailerProductVariant->barcode = $variant->barcode;

                            $retailerProductVariant->product_id = $retailerProduct->id;
                            $retailerProductVariant->shop_id =  $retailerProduct->shop_id;
                            $retailerProductVariant->user_id =  $retailerProduct->user_id;

                            if($variant->has_image != null){
                                $image_linked = $retailerProduct->has_images()->where('image',$variant->has_image->image)->first();
                                $retailerProductVariant->image =$image_linked->id;
                            }

                            $retailerProductVariant->save();

                            $variants_array =  $this->variants_template_array($retailerProduct);

                            dump($variants_array);

                            $productdata = [
                                "product" => [
                                    "options" => $this->options_update_template_array($retailerProduct),
                                    "variants" => $variants_array,
                                ]
                            ];

                            dump($productdata);

                            $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$retailerProduct->shopify_id.'.json',$productdata);
                            dump($resp);
                            $shopifyVariants = $resp->body->product->variants;
                            foreach ($retailerProduct->hasVariants as $index => $v){
                                $v->shopify_id = $shopifyVariants[$index]->id;
                                $v->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                                $v->save();
                            }

                        }
                    }
                }
            }

            dd('done');
            return redirect()->back()->with('success', 'Varaints Update Successfully!');
        }
    }

    public function updateProductVariants(Request $request, $id)
    {
        $product = Product::find($id);
        $shop = $this->helper->getShop();

        if ($product) {
            $retailerProduct = RetailerProduct::where('shop_id', $shop->id)->where('linked_product_id', $product->id)->first();

            if ($retailerProduct) {
                $retailerProduct->variants = $product->variants;
                $retailerProduct->save();

                foreach ($request->varaint_id as $index => $v) {
                    $real_variant = ProductVariant::find($v);

                    if(!RetailerProductVariant::where('product_id', $retailerProduct->id)->where('option1', $real_variant->option1)->where('option2', $real_variant->option2)->where('option3', $real_variant->option3)->exists()) {

                        $retailerProductVariant = new RetailerProductVariant();
                        $retailerProductVariant->title = $real_variant->title;
                        $retailerProductVariant->option1 = $real_variant->option1;
                        $retailerProductVariant->option2 = $real_variant->option2;
                        $retailerProductVariant->option3 = $real_variant->option3;
                        $retailerProductVariant->price = $request->price[$index];
                        $retailerProductVariant->cost =  $real_variant->price;
                        $retailerProductVariant->quantity = $real_variant->quantity;
                        $retailerProductVariant->sku = $real_variant->sku;
                        $retailerProductVariant->barcode = $request->barcode[$index];

                        $retailerProductVariant->product_id = $retailerProduct->id;
                        $retailerProductVariant->shop_id =  $retailerProduct->shop_id;
                        $retailerProductVariant->user_id =  $retailerProduct->user_id;

                        if($real_variant->has_image != null){
                            $image_linked = $retailerProduct->has_images()->where('image',$real_variant->has_image->image)->first();
                            $retailerProductVariant->image =$image_linked->id;
                        }

                        $retailerProductVariant->save();

                        $variants_array =  $this->variants_template_array($retailerProduct);

                        $productdata = [
                            "product" => [
                                "options" => $this->options_update_template_array($retailerProduct),
                                "variants" => $variants_array,
                            ]
                        ];

                        $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$retailerProduct->shopify_id.'.json',$productdata);
                        $shopifyVariants = $resp->body->product->variants;
                        foreach ($retailerProduct->hasVariants as $i => $var){
                            $var->shopify_id = $shopifyVariants[$i]->id;
                            $var->inventory_item_id = $shopifyVariants[$i]->inventory_item_id;
                            $var->save();
                        }
                    }
                }

                return redirect()->back()->with('success', 'Varaints Updated Successfully!');
            }
            else {
                return redirect()->back()->with('error', 'Retailer Product Do not exists');
            }
        }
        else {
            return redirect()->back()->with('error', 'Admin Product Do not exists');
        }
    }

    public function options_update_template_array($product){
        $options_array = [];
        if (count($product->option1($product)) > 0) {
            $temp = [];
            foreach ($product->option1($product) as $a) {
                array_push($temp, $a);
            }
            array_push($options_array, [
                'name' => 'Option1',
                'position' => '1',
                'values' => $temp,
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
                'values' => $temp,
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
                'values' => $temp,
            ]);
        }
        return $options_array;
    }

    public function woocommerce_variants_template_array_for_updation($product, $attributes){
        if(is_null($product->weight)) {
            $weight = 0.0;
        }
        else {
            $weight = $product->weight;
        }

        $variants_array = [];
        foreach ($product->hasVariants as $index => $varaint) {
            $array_item = [];
            $array_item['attributes'] = [];
            $array_item['image'] = [];
            $array_item['id'] = $varaint->woocommerce_id;
            $array_item['regular_price'] = $varaint->price;
            //$array_item['sale_price'] = $varaint->cost;
            $array_item['sku'] = $varaint->sku;
            $array_item['barcode'] = $varaint->barcode;
            $array_item['stock_quantity'] = $varaint->quantity;
            $array_item['manage_stock'] = true;
            $array_item['weight'] = $weight;

            if($varaint->option1 !== null) {
                array_push($array_item['attributes'], [
                    'option' => $varaint->option1,
                    'name' => $product->attribute1,
                ]);
            }
            if($varaint->option2 !== null) {
                array_push($array_item['attributes'], [
                    'option' => $varaint->option2,
                    'name' => $product->attribute2,
                ]);
            }
            if($varaint->option3 !== null) {
                array_push($array_item['attributes'], [
                    'option' => $varaint->option3,
                    'name' => $product->attribute3,

                ]);
            }

            array_push($variants_array, $array_item);

        }

        return $variants_array;
    }

}
