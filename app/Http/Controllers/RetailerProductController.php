<?php

namespace App\Http\Controllers;

use App\Product;
use App\RetailerImage;
use App\RetailerProduct;
use App\RetailerProductVariant;
use App\Shop;
use Illuminate\Http\Request;

class RetailerProductController extends Controller
{
    private $helper;

    /**
     * RetailerProductController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
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
                $shop = $this->helper->getSpecificShop();
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
                    $variant->quantity = $request->input('quantity');
                    $variant->sku = $request->input('sku');
                    $variant->barcode = $request->input('barcode');
                    $variant->product_id = $id;
                    $variant->save();

                    if($product->toShopify == 1){
                        $productdata = [
                            "variant" => [
                                'title' => $variant->title,
                                'sku' => $variant->sku,
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
                        $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'/variants/'.$variant->shopify_id.'.json',$productdata);
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
                    return redirect()->back();

                }

                if ($request->input('request_type') == 'existing-product-image-delete') {
                    $image =  RetailerImage::find($request->input('file'));
                    if ($product->toShopify == 1) {
                        $shop->api()->rest('DELETE', '/admin/api/2019-10/products/' . $product->shopify_id . '/images/' . $image->shopify_id . '.json');
                    }
                    $image->delete();

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
        return view('single-store.products.import_list')->with([
            'products' => $products,
            'shop' => $shop,
            'search' => $request->input('search'),
            'source' => $request->input('source'),

        ]);
    }

    public function delete($id)
    {
        $product = RetailerProduct::find($id);
        $shop = $this->helper->getShop();
        if($product->toShopify == 1){
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
        $product->delete();
        return redirect()->back()->with('success', 'Product Deleted with Variants Successfully');
    }

    public function my_products(Request $request){
        $productQuery = RetailerProduct::where('toShopify',1)->where('shop_id',$this->helper->getLocalShop()->id)->newQuery();
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
    public function edit_my_product($id){
        $product = RetailerProduct::find($id);
        $shop= $this->helper->getLocalShop();
        return view('single-store.products.edit_my_product')->with([
            'product' => $product,
            'shop' => $shop
        ]);
    }

    public function import_to_shopify(Request $request)
    {
        $product = RetailerProduct::find($request->id);
//        dd($product);
        if ($product != null && $product->toShopify != 1) {
            $variants_array = [];
            $options_array = [];
            $images_array = [];
            //converting variants into shopify api format
            $variants_array =  $this->variants_template_array($product,$variants_array);
            /*Product Options*/
            $options_array = $this->options_template_array($product,$options_array);
            /*Product Images*/
//            dd($variants_array,$options_array);

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
                    "published"=>  $published
                ]
            ];
//            dd($product->has_platforms);

            $response = $shop->api()->rest('POST', '/admin/api/2019-10/products.json', $productdata);

            $product_shopify_id =  $response->body->product->id;
            $product->shopify_id = $product_shopify_id;
            $price = $product->price;
            $product->toShopify = 1;
            $product->save();

            $shopifyImages = $response->body->product->images;
            $shopifyVariants = $response->body->product->variants;
            if(count($product->hasVariants) == 0){
                $variant_id = $shopifyVariants[0]->id;
                $i = [
                    'variant' => [
                        'price' =>$price
                    ]
                ];
                $shop->api()->rest('PUT', '/admin/api/2019-10/variants/' . $variant_id .'.json', $i);
            }
            foreach ($product->hasVariants as $index => $v){
                $v->shopify_id = $shopifyVariants[$index]->id;
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
            return redirect()->back()->with('success','Product Push to Store Successfully!');
        }
        else{
            echo 'imported already';
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
}
