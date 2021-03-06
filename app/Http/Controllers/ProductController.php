<?php

namespace App\Http\Controllers;

use App\AdditionalTab;
use App\Category;
use App\Console\Commands\AppChangeQuantitySku;
use App\Csv;
use App\DropshipRequest;
use App\ErrorLog;
use App\Exports\ProductsExport;
use App\Exports\ProductVariantExport;
use App\Exports\RetailerOrderExport;
use App\Image;
use App\Jobs\SyncInflowInventory;
use App\Mail\ProductDeleteMail;
use App\Mail\ProductStockOutMail;
use App\Mail\VariantStockOutMail;
use App\Product;
use App\ProductVariant;
use App\RetailerImage;
use App\RetailerOrder;
use App\RetailerProduct;
use App\RetailerProductVariant;
use App\SubCategory;
use App\Tag;
use App\TieredPrice;
use App\User;
use App\WareHouse;
use App\WarehouseInventory;
use App\WarnedPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use OhMyBrew\ShopifyApp\Models\Shop;
use Session;
use App\Allproductcsv;




class ProductController extends Controller
{
    private $helper;
    private $notify;
    private $log;
    private $inventory;

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->helper = new HelperController();
        $this->notify = new NotificationController();
        $this->log = new ActivityLogController();
        $this->inventory = new InventoryController();
    }

    public function index()
    {
        $categories = Category::latest()->get();
        $platforms = WarnedPlatform::all();
        $tags = Tag::all();
        $shops = Shop::whereNotIn('shopify_domain',['wefullfill.myshopify.com'])->get();
        return view('products.create')->with([
            'categories' => $categories,
            'platforms' => $platforms,
            'shops' => $shops,
            'tags' => $tags
        ]);
    }

    public function view_dropship_products_listing(Request $request) {
        $productQ = Product::whereNotNull('is_dropship_product')->newQuery();
        if($request->has('search')){
            $productQ->where('title','LIKE','%'.$request->input('search').'%')->orWhereHas('hasVariants', function($q) use ($request) {
                $q->where('sku', 'LIKE', '%' . $request->input('search') . '%');
            });
        }

        return view('products.dropship-products-listing')->with([
            'products' => $productQ
                ->select('id', 'to_woocommerce','title', 'price', 'quantity', 'status', 'woocommerce_id')
                ->with(['has_images:id,position,image,product_id', 'hasVariants:id,price,product_id'])
                ->orderBy('created_at','DESC')->paginate(20),
            'search' =>$request->input('search'),
        ]);

    }

    public function all(Request $request)
    {
        $categories = Category::latest()->get();
        $shops = Shop::latest()->get();

        $productQ = Product::whereNull('is_dropship_product')->newQuery();
        if($request->has('search')){
            $productQ->where('title','LIKE','%'.$request->input('search').'%')->orWhereHas('hasVariants', function($q) use ($request) {
                $q->where('sku', 'LIKE', '%' . $request->input('search') . '%');
            });
        }

        if($request->filled('parent_category') && !$request->filled('child_category')) {
            $productQ->whereHas('has_categories', function($q) use ($request){
                $q->where('title',$request->input('parent_category'));
            });

        }

        if($request->filled('parent_category') && $request->filled('child_category') && !$request->filled('sub_sub_category') ) {

            $productQ->whereHas('has_categories', function($q) use ($request){
                $q->where('title',$request->input('parent_category'));
            })
            ->orWhereHas('has_subcategories', function($q) use ($request){
                $q->where('title',$request->input('child_category'));
            });
        }

        if($request->filled('parent_category') && $request->filled('child_category') && $request->filled('sub_sub_category') ) {

            $productQ->whereHas('has_categories', function($q) use ($request){
                $q->where('title',$request->input('parent_category'));
            })
            ->orWhereHas('has_subcategories', function($q) use ($request){
                $q->where('title',$request->input('child_category'));
            })
            ->orWhereHas('has_sub_sub_categories', function($q) use ($request){
                $q->where('title',$request->input('sub_sub_category'));
            });
        }



        return view('products.all')->with([
            'products' => $productQ
                ->select('id', 'to_woocommerce','title', 'price', 'quantity', 'status', 'woocommerce_id')
                ->with(['has_images:id,position,image,product_id', 'hasVariants:id,price,product_id'])
                ->orderBy('created_at','DESC')->paginate(20),

            'search' =>$request->input('search'),
            'parent_category' =>$request->input('parent_category'),
            'child_category' =>$request->input('child_category'),
            'sub_sub_category' =>$request->input('sub_sub_category'),
            'categories' => $categories,
            'shops' => $shops,
        ]);
    }

    public function view($id)
    {
        $product = Product::with(['has_images', 'hasVariants','has_platforms','has_categories','has_subcategories'])->find($id);
        return view('products.product')->with([
            'product' => $product
        ]);
    }

    public function view_single_dropship_product($id)
    {
        $product = Product::with(['has_images', 'hasVariants'])->find($id);
        return view('products.dropship-product')->with([
            'product' => $product
        ]);
    }
    public function retailer_view($id)
    {
        $product = RetailerProduct::find($id);
        return view('products.product')->with([
            'product' => $product
        ]);
    }

    public function Edit($id)
    {
        $categories = Category::latest()->get();
        $product = Product::with(['has_images', 'hasVariants.has_image','has_platforms','has_categories','has_subcategories', 'has_preferences', 'has_non_shopify_user_preferences', 'has_tiered_prices', 'has_warehouse_inventory'])
            ->find($id);
        $platforms = WarnedPlatform::all();
        $shops = Shop::whereNotIn('shopify_domain',['wefullfill.myshopify.com'])->get();
        $tags = Tag::all();
        $warehouses = WareHouse::all();


        $users = User::role('non-shopify-users')
                ->whereNotIn('email', ['admin@wefullfill.com', 'super_admin@wefullfill.com'])
                ->orderBy('created_at','DESC')
                ->get();


        return view('products.edit')->with([
            'categories' => $categories,
            'platforms' => $platforms,
            'product' => $product,
            'shops' => $shops,
            'non_shopify_users' => $users,
            'tags' => $tags,
            'warehouses' => $warehouses,
        ]);
    }

    public function showImportPage($id) {
        $categories = Category::latest()->get();
        $product = Product::with(['has_images', 'hasVariants','has_platforms','has_categories','has_subcategories'])->find($id);
        $platforms = WarnedPlatform::all();
        $shops = Shop::whereNotIn('shopify_domain',['wefullfill.myshopify.com'])->get();
        $tags = Tag::all();


        $users = User::role('non-shopify-users')
            ->whereNotIn('email', ['admin@wefullfill.com', 'super_admin@wefullfill.com'])
            ->orderBy('created_at','DESC')
            ->get();


        return view('products.import')->with([
            'categories' => $categories,
            'platforms' => $platforms,
            'product' => $product,
            'shops' => $shops,
            'non_shopify_users' => $users,
            'tags' => $tags
        ]);
    }

    public function importToWoocommerce($id) {
        $product = Product::find($id);

        if($product != null) {
           DB::beginTransaction();
           try{
               $shop = $this->helper->getAdminShop();
               $response = $shop->api()->rest('GET', '/admin/api/2019-10/products/'.$product->shopify_id.'.json');

               $attributes = $response->body->product->options;
               if (!empty($attributes[0])) {
                   $product->attribute1 = $attributes[0]->name;
               }
               if (!empty($attributes[1])) {
                   $product->attribute2 = $attributes[1]->name;
               }
               if (!empty($attributes[2])) {
                   $product->attribute3 = $attributes[2]->name;
               }

               $product->save();

               return $this->import_old_product_to_woocommerce($product->id);
           }
           catch(\Exception $e) {
               DB::rollBack();
               return redirect()->back()->with('error', $e->getMessage());
           }
        }
        else {
            return redirect()->back()->with('error', 'Product Not Found');
        }
    }

    public function addTieredPrice(Request $request, $id) {
        $variants = $request->variant_id;
        $product = Product::find($id);

        foreach ($variants as $variant) {
            if(TieredPrice::where('product_variant_id', $variant)->where('product_id', $id)->exists()) {
                TieredPrice::where('product_variant_id', $variant)->where('product_id', $id)->delete();
            }
            for($i=0; $i< count($request->input('min_qty'.$variant)); $i++) {

                if($request->input('min_qty'.$variant)[$i] != null) {
                    $item = new TieredPrice();
                    $item->product_variant_id = $variant;
                    $item->product_id = $id;
                    $item->min_qty = $request->input('min_qty'.$variant)[$i];
                    if($request->input('max_qty'.$variant)[$i] == null) {
                        $item->max_qty = $product->quantity;
                    }
                    else {
                        $item->max_qty = $request->input('max_qty'.$variant)[$i];
                    }
                    $item->type = $request->input('type'.$variant)[$i];
                    $item->price = $request->input('tiered_price'.$variant)[$i];
                    $item->save();
                }

            }
        }

        return redirect()->back()->with('success', 'Tiered Prices Added Successfully!');
    }

    public function addTieredPriceForProductWithoutVariant(Request $request, $id) {
        $product = Product::find($id);

        if(TieredPrice::where('product_id', $id)->exists()) {
            TieredPrice::where('product_id', $id)->delete();
        }

        for($i=0; $i< count($request->input('min_qty')); $i++) {

            if($request->input('min_qty')[$i] != null) {
                $item = new TieredPrice();
                $item->product_variant_id = null;
                $item->product_id = $id;
                $item->min_qty = $request->input('min_qty')[$i];
                if($request->input('max_qty')[$i] == null) {
                    $item->max_qty = $product->quantity;
                }
                else {
                    $item->max_qty = $request->input('max_qty')[$i];
                }
                $item->type = $request->input('type')[$i];
                $item->price = $request->input('tiered_price')[$i];
                $item->save();
            }

        }

        return redirect()->back()->with('success', 'Tiered Prices Added Successfully!');


    }

    public function removeTieredPrice($id) {
        $item = TieredPrice::find($id);
        $item->delete();

        return response()->json(['data'=> 'success']);
    }

    public function productAddImages(Request $request, $id) {

        $product = Product::find($id);
        $woocommerce =$this->helper->getWooCommerceAdminShop();
        if($product != null) {
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $destinationPath = 'images/';
                    $filename = now()->format('YmdHi') . str_replace([' ','(',')'], '-', $image->getClientOriginalName());
                    $image->move($destinationPath, $filename);
                    $image = new Image();
                    $image->isV = 0;
                    $image->product_id = $product->id;
                    $image->image = $filename;
                    $image->position = count($product->has_images) + $index+1;
                    $image->save();
                }


                if($product->to_woocommerce == 1) {
                    $images_array = [];
                    $product = Product::find($id);
                    foreach ($product->has_images as $index => $image) {
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

                    $productdata = [
                        "images" => $images_array,
                    ];

                    /*Updating Product On Woocommerce*/
                    $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);

                    $woocommerce_images = $response->images;

                    if (count($woocommerce_images) == count($product->has_images)) {
                        foreach ($product->has_images as $index => $image) {
                            $image->woocommerce_id = $woocommerce_images[$index]->id;
                            $image->save();
                        }
                    }
                }

            }
            $product->save();
            $this->log->store(0, 'Product', $product->id, $product->title,'Product Image Added');
            return redirect()->back()->with('success', 'Product Updated Successfully');
        }
    }

    public function updateProductStatus(Request $request, $id) {
        $product = Product::find($id);
//        $shop =$this->helper->getShop();

        $this->product_status_change($request, $product);
        $this->log->store(0, 'Product', $product->id, $product->title,'Product Status Updated');

    }

    public function update_old(Request $request, $id)
    {
        $product = Product::find($id);
        $shop =$this->helper->getShop();
        if ($product != null) {
            foreach($request->type as $type) {
                if ($type == 'basic-info') {
                    $product->title = $request->title;
                    $product->description = $request->description;
                    $product->save();
                    $productdata = [
                        "product" => [
                            "title" => $request->title,
                            "body_html" => $request->description,
                        ]
                    ];
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Basic Information Updated');

                    $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'.json',$productdata);
                }

                else if ($type == 'pricing') {
                    $product->price = $request->price;
                    $product->compare_price = $request->compare_price;
                    $product->cost = $request->cost;
                    $product->quantity = $request->quantity;
                    $product->weight = $request->weight;
                    $product->sku = $request->sku;
                    $product->barcode = $request->barcode;
                    $product->save();

                    if($product->quantity == 0) {
                        // Sending Notification Emails To all Concerned Retailer Stores
                        if(count($product->has_retailer_products) > 0) {
                            $users_temp = [];
                            foreach ($product->has_retailer_products as $retailer_product) {
                                array_push($users_temp, User::where('id', $retailer_product->user_id)->first()->email);
                            }

                            if(count($users_temp)> 0) {
                                foreach($users_temp as $key => $user){
                                    try{
                                        Mail::to($user)->send(new ProductStockOutMail($product));
                                    }
                                    catch (\Exception $e){
                                    }
                                }
                            }
                        }

                        $this->notify->generate('Product','Product Out Of Stock',$product->title.' is running out of stock, kindly update the stock on your store',$product);
                    }

                    if (count($product->hasVariants) == 0) {
                        $response = $shop->api()->rest('GET', '/admin/api/2019-10/products/' . $product->shopify_id .'.json');
                        if(!$response->errors){
                            $shopifyVariants = $response->body->product->variants;
                            $variant_id = $shopifyVariants[0]->id;
                            $i = [
                                'variant' => [
                                    'price' =>$product->price,
                                    'sku' =>  $product->sku,
                                    'grams' => $product->weight * 1000,
                                    'weight' => $product->weight,
                                    'weight_unit' => 'kg',
                                    'barcode' => $product->barcode,

                                ]
                            ];
                            $this->log->store(0, 'Product', $product->id, $product->title,'Product Pricing Updated');

                            $shop->api()->rest('PUT', '/admin/api/2019-10/variants/' . $variant_id .'.json', $i);
                            Artisan::call('app:sku-quantity-change',['product_id'=> $product->id]);

                        }

                    }

                }

                else if ($type == 'pricing-for-variant') {
                    $product->price = $request->price;
                    $product->quantity = $request->quantity;
                    $product->weight = $request->weight;
                    $product->save();

                    if($product->quantity == 0) {
                        // Sending Notification Emails To all Concerned Retailer Stores
                        if(count($product->has_retailer_products) > 0) {
                            $users_temp = [];
                            foreach ($product->has_retailer_products as $retailer_product) {
                                array_push($users_temp, User::where('id', $retailer_product->user_id)->first()->email);
                            }

                            if(count($users_temp)> 0) {
                                $users = [];
                                foreach($users_temp as $user){
                                    try{
                                        Mail::to($user)->send(new ProductStockOutMail($product));
                                    }
                                    catch (\Exception $e){
                                    }
                                }
                            }
                        }

                        $this->notify->generate('Product','Product Out Of Stock',$product->title.' is running out of stock, kindly update the stock on your store',$product);
                    }

                    if (count($product->hasVariants) == 0) {
                        $response = $shop->api()->rest('GET', '/admin/api/2019-10/products/' . $product->shopify_id .'.json');
                        if(!$response->errors){
                            $shopifyVariants = $response->body->product->variants;
                            $variant_id = $shopifyVariants[0]->id;
                            $i = [
                                'variant' => [
                                    'price' =>$product->price,
                                    'sku' =>  $product->sku,
                                    'grams' => $product->weight * 1000,
                                    'weight' => $product->weight,
                                    'weight_unit' => 'kg',
                                    'barcode' => $product->barcode,

                                ]
                            ];
                            $this->log->store(0, 'Product', $product->id, $product->title,'Product Pricing Updated');

                            $shop->api()->rest('PUT', '/admin/api/2019-10/variants/' . $variant_id .'.json', $i);
                            //Artisan::call('app:sku-quantity-change',['product_id'=> $product->id]);

                        }

                    }

                }

                else if ($type == 'single-variant-update') {
                    foreach ($request->variant_id as $id) {
                        $variant = ProductVariant::find($id);
                        $variant->title = $request->input('option1-'.$id) . '/' . $request->input('option2-'.$id) . '/' . $request->input('option3-'.$id);
                        $variant->option1 = $request->input('option1-'.$id);
                        $variant->option2 = $request->input('option2-'.$id);
                        $variant->option3 = $request->input('option3-'.$id);
                        $variant->price = $request->input('single-var-price-'.$id);
                        $variant->cost = $request->input('single-var-cost-'.$id);
                        //$variant->compare_price = $request->input('compare_price');
                        $variant->quantity = $request->input('single-var-quantity-'.$id);
                        $variant->sku = $request->input('single-var-sku-'.$id);
                        $variant->barcode = $request->input('single-var-barcode-'.$id);

                        if($variant->quantity == 0) {
                            // Sending Notification Emails To all Concerned Retailer Stores
                            if(count($product->has_retailer_products) > 0) {
                                $users_temp = [];
                                foreach ($product->has_retailer_products as $retailer_product) {
                                    array_push($users_temp, User::where('id', $retailer_product->user_id)->first()->email);
                                }

                                if(count($users_temp)> 0) {
                                    foreach($users_temp as $ut){
                                        try{
                                            Mail::to($ut)->send(new VariantStockOutMail($product));
                                        }
                                        catch (\Exception $e){
                                        }
                                    }
                                }
                            }
                            $this->notify->generate('Product','Variant Out Of Stock',$variant->title.' of ' . $product->title . ' is running out of stock, kindly update the stock on your store',$product);
                        }

                        $variant->product_id = $product->id;
                        $variant->save();

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
                        $this->log->store(0, 'Product', $product->id, $product->title,'Variant Updated');
                    }
                    //Artisan::call('app:sku-quantity-change',['product_id'=> $product->id]);

                }

                else if ($type == 'add-additional-tab'){
                    $additional_tab = new AdditionalTab();
                    $additional_tab->title = $request->input('tab-title');
                    $additional_tab->description = $request->input('tab-description');
                    $additional_tab->product_id = $product->id;
                    $additional_tab->save();

                    $productdata = [
                        "metafield" => [
                            "key" => $additional_tab->title,
                            "value"=> $additional_tab->description,
                            "value_type"=> "string",
                            "namespace"=> "tabs"
                        ]
                    ];
                    $resp =  $shop->api()->rest('POST', '/admin/api/2019-10/products/'.$product->shopify_id.'/metafields.json',$productdata);
                    if($resp->errors){
                    }
                    else{
                        $additional_tab->shopify_id = $resp->body->metafield->id;
                        $additional_tab->save();
                        $this->log->store(0, 'Product', $product->id, $product->title,'Product Tab Added');
                    }
                }

//                else if ($type == 'edit-additional-tab'){
////                    dd($request);
//                    $additional_tab = AdditionalTab::find($request->input('tab_id'));
//                    $additional_tab->title = $request->input('title');
//                    $additional_tab->description = $request->input('description');
//                    $additional_tab->product_id = $product->id;
//                    $additional_tab->save();
//
//                    $productdata = [
//                        "metafield" => [
//                            "key" => $additional_tab->title,
//                            "value"=> $additional_tab->description,
//                            "value_type"=> "string",
//                            "namespace"=> "tabs"
//                        ]
//                    ];
//
//                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Tab Updated');
//
//                    $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'/metafields/'.$additional_tab->shopify_id.'.json',$productdata);
//                }

                else if ($type == 'fulfilled') {
                    $product->fulfilled_by = $request->input('fulfilled-by');
                    $product->sortBy = $request->input('sortBy');
                    $product->save();
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Basic Information Updated');
                }

                else if($type == 'marketing_video_update'){
                    $product->marketing_video = $request->input('marketing_video');
                    $product->save();
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Marketing Video Updated');
                }

                else if ($type == 'category') {
                    if ($request->category) {
                        $product->has_categories()->sync($request->category);
                    }
                    if ($request->sub_cat) {
                        $product->has_subcategories()->sync($request->sub_cat);
                    }
                    $product->save();

                    $tags = $product->tags;
                    if(count($product->has_categories) > 0){
                        $categories = implode(',',$product->has_categories->pluck('title')->toArray());
                        $tags = $tags.','.$categories;
                    }
                    if(count($product->has_subcategories) > 0){
                        $subcategories = implode(',',$product->has_subcategories->pluck('title')->toArray());
                        $tags = $tags.','.$subcategories;
                    }
                    $productdata = [
                        "product" => [
                            "tags" => $tags,
                        ]
                    ];
                    $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'.json',$productdata);

                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Category Updated');

                }

                else if ($type == 'organization') {
                    $product->type = $request->product_type;
                    $product->vendor = $request->vendor;
                    $product->tags = $request->tags;
                    $product->save();

                    $productdata = [
                        "product" => [
                            "vendor" => $request->vendor,
                            "product_type" => $request->product_type,
                        ]
                    ];
                    $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'.json',$productdata);
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Vendor Updated');
                }

                else if ($type == 'more-details') {
                    if($request->input('processing_time') != null){
                        $product->processing_time = $request->input('processing_time');
                    }
                    if ($request->platforms) {
                        $product->has_platforms()->sync($request->platforms);
                    }
                    $product->save();
                    $metafields = [];

                    $resp =  $shop->api()->rest('GET', '/admin/api/2019-10/products/'.$product->shopify_id.'/metafields.json');
                    if(count($resp->body->metafields) > 0){
                        foreach ($resp->body->metafields as $m){
                            if($m->namespace == 'platform'){
                                $shop->api()->rest('DELETE', '/admin/api/2019-10/products/'.$product->shopify_id.'/metafields/'.$m->id.'.json');
                            }
                        }
                    }
                    foreach ($product->has_platforms as $index => $platform){
                        $index = $index+1;
                        $productdata = [
                            "metafield" => [
                                "key" => "warned_platform".$index,
                                "value"=> $platform->name,
                                "value_type"=> "string",
                                "namespace"=> "platform"
                            ]
                        ];
                        $this->log->store(0, 'Product', $product->id, $product->title,'Product Basic Information Updated');

                        $resp =  $shop->api()->rest('POST', '/admin/api/2019-10/products/'.$product->shopify_id.'/metafields.json',$productdata);
                    }

                    $this->product_status_change($request, $product);
                }

                else if ($type == 'shop-preferences'){
                    $product->global = $request->input('global');
                    $product->save();
                    if($request->input('global') == 0 && $request->has('shops') && count($request->input('shops')) > 0){
                        $product->has_preferences()->sync($request->input('shops'));
                    }
                    if($request->input('global') == 0 && $request->has('non_shopify_users') && count($request->input('non_shopify_users')) > 0){
                        $product->has_non_shopify_user_preferences()->sync($request->input('non_shopify_users'));
                    }
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Shop Preferences Updated');

                }

//                else if ($type == 'existing-product-image-delete') {
//                    $image =  Image::find($request->input('file'));
//                    $shop->api()->rest('DELETE', '/admin/api/2019-10/products/' . $product->shopify_id . '/images/'.$image->shopify_id.'.json');
//                    $image->delete();
//                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Image Deleted');
//                }

                else if ($type == 'tiered-pricing') {
                    $variants = $request->variant_id;
                    if($variants != null) {
                        foreach ($variants as $variant) {
                            if(TieredPrice::where('product_variant_id', $variant)->where('product_id', $id)->exists()) {
                                TieredPrice::where('product_variant_id', $variant)->where('product_id', $id)->delete();
                            }
                            for($i=0; $i< count($request->input('min_qty'.$variant)); $i++) {

                                if($request->input('min_qty'.$variant)[$i] != null) {
                                    $item = new TieredPrice();
                                    $item->product_variant_id = $variant;
                                    $item->product_id = $id;
                                    $item->min_qty = $request->input('min_qty'.$variant)[$i];
                                    if($request->input('max_qty'.$variant)[$i] == null) {
                                        $item->max_qty = $product->quantity;
                                    }
                                    else {
                                        $item->max_qty = $request->input('max_qty'.$variant)[$i];
                                    }
                                    $item->type = $request->input('type'.$variant)[$i];
                                    $item->price = $request->input('tiered_price'.$variant)[$i];
                                    $item->save();
                                }

                            }
                        }
                    }
                }

                else if($type == 'single-variant-tiered-pricing') {
                    if(TieredPrice::where('product_id', $id)->exists()) {
                        TieredPrice::where('product_id', $id)->delete();
                    }

                    for($i=0; $i< count($request->input('min_qty')); $i++) {

                        if($request->input('min_qty')[$i] != null) {
                            $item = new TieredPrice();
                            $item->product_variant_id = null;
                            $item->product_id = $id;
                            $item->min_qty = $request->input('min_qty')[$i];
                            if($request->input('max_qty')[$i] == null) {
                                $item->max_qty = $product->quantity;
                            }
                            else {
                                $item->max_qty = $request->input('max_qty')[$i];
                            }
                            $item->type = $request->input('type')[$i];
                            $item->price = $request->input('tiered_price')[$i];
                            $item->save();
                        }

                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Product Updated Successfully');
    }

    public function updateDropshipProduct(Request $request, $id)
    {
        $product = Product::find($id);

        foreach ($product->hasVariants as $index => $variant)
        {
            $variant->sku = $request->variant_sku[$index];
            $variant->cost = $request->variant_cost[$index];
            $variant->price = $request->variant_price[$index];
            $variant->is_dropship_variant = 1;
            $variant->save();
        }

        return redirect()->back()->with('success', 'Product updated Successfully!');

    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $woocommerce = $this->helper->getWooCommerceAdminShop();


        $this->validate($request, [
            'sku' => 'required|unique:products,sku,'.$product->id,
            'title' => 'required|unique:products,title,'.$product->id
        ]);

        DB::beginTransaction();
        try{
            if ($product != null) {
                foreach($request->type as $type) {
                    if ($type == 'basic-info') {
                        $product->title = $request->title;
                        $product->description = $request->description;
                        $product->short_description = $request->short_description;
                        $product->save();

                        if($product->to_woocommerce == 1) {
                            $productdata = [
                                "name" => $product->title,
                                "description" => $product->description,
                                "short_description" => $product->short_description,
                            ];

                            $response = $woocommerce->put('products/'. $product->woocommerce_id, $productdata);
                        }

                        $this->log->store(0, 'Product', $product->id, $product->title,'Product Basic Information Updated');
                    }

                    else if ($type == 'pricing') {
                        $product->price = $request->price;
                        $product->compare_price = $request->compare_price;
                        $product->recommended_price = $request->recommended_price;
                        $product->cost = $request->cost;
                        $product->quantity = $request->quantity;
                        $product->weight = $request->weight;
                        $product->sku = $request->sku;
                        $product->barcode = $request->barcode;
                        $product->length = $request->length;
                        $product->width = $request->width;
                        $product->height = $request->height;
                        $product->save();

                        if($product->to_woocommerce == 1) {

                            $dimension_array = array(
                                'width' => is_null($product->width) ? "0" : $product->width,
                                'height' => is_null($product->height) ? "0" : $product->height,
                                'length' => is_null($product->length) ? "0" : $product->length
                            );

                            $productdata = [
                                //"sale_price" => $product->price,
                                "regular_price" => $product->price,
                                "sku" => $product->sku,
                                "weight" => $product->weight,
                                "stock_quantity" => $product->quantity,
                                "dimensions" => $dimension_array,
                            ];

                            $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);
                        }

                        if($product->quantity == 0) {
                            // Sending Notification Emails To all Concerned Retailer Stores
                            if(count($product->has_retailer_products) > 0) {
                                $users_temp = [];
                                foreach ($product->has_retailer_products as $retailer_product) {
                                    array_push($users_temp, User::where('id', $retailer_product->user_id)->first()->email);
                                }

                                if(count($users_temp)> 0) {
                                    $users = [];
                                    foreach($users_temp as $user){
                                        try{
                                            Mail::to($user)->send(new ProductStockOutMail($product));
                                        }
                                        catch (\Exception $e){
                                        }
                                    }
                                }
                            }

                            $this->notify->generate('Product','Product Out Of Stock',$product->title.' is running out of stock, kindly update the stock on your store',$product);
                        }

                        //Artisan::call('app:sku-quantity-change',['product_id'=> $product->id]);

                    }

                    else if ($type == 'pricing-for-variant') {
                        $product->price = $request->price;
                        $product->quantity = $request->quantity;
                        $product->recommended_price = $request->recommended_price;
                        $product->weight = $request->weight;
                        $product->length = $request->length;
                        $product->width = $request->width;
                        $product->height = $request->height;
                        $product->save();

                        if($product->to_woocommerce == 1) {

                            $dimension_array = array(
                                'width' => is_null($product->width) ? "0" : $product->width,
                                'height' => is_null($product->height) ? "0" : $product->height,
                                'length' => is_null($product->length) ? "0" : $product->length
                            );

                            $productdata = [
                                //"sale_price" => $product->price,
                                "regular_price" => $product->price,
                                "sku" => $product->sku,
                                "weight" => $product->weight,
                                "stock_quantity" => $product->quantity,
                                "dimensions" => $dimension_array,
                            ];

                            $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);
                        }

                        if($product->quantity == 0) {
                            // Sending Notification Emails To all Concerned Retailer Stores
                            if(count($product->has_retailer_products) > 0) {
                                $users_temp = [];
                                foreach ($product->has_retailer_products as $retailer_product) {
                                    array_push($users_temp, User::where('id', $retailer_product->user_id)->first()->email);
                                }

                                if(count($users_temp)> 0) {
                                    foreach($users_temp as $ut){
                                        if($ut != null) {
                                            try{
                                                Mail::to($ut)->send(new ProductStockOutMail($product));
                                            }
                                            catch (\Exception $e){
                                            }
                                        }
                                    }
                                }
                            }

                            $this->notify->generate('Product','Product Out Of Stock',$product->title.' is running out of stock, kindly update the stock on your store',$product);
                        }

                        //Artisan::call('app:sku-quantity-change',['product_id'=> $product->id]);
                    }

                    else if ($type == 'single-variant-update') {
                        foreach ($request->variant_id as $id) {
                            $variant = ProductVariant::find($id);
                            $variant->title = $request->input('option1-'.$id) . '/' . $request->input('option2-'.$id) . '/' . $request->input('option3-'.$id);
                            $variant->option1 = $request->input('option1-'.$id);
                            $variant->option2 = $request->input('option2-'.$id);
                            $variant->option3 = $request->input('option3-'.$id);
                            $variant->price = $request->input('single-var-price-'.$id);
                            $variant->cost = $request->input('single-var-cost-'.$id);
                            $variant->quantity = $request->input('single-var-quantity-'.$id);
                            $variant->sku = $request->input('single-var-sku-'.$id);
                            $variant->barcode = $request->input('single-var-barcode-'.$id);

                            if($variant->quantity == 0) {
                                // Sending Notification Emails To all Concerned Retailer Stores
                                if(count($product->has_retailer_products) > 0) {
                                    $users_temp = [];
                                    foreach ($product->has_retailer_products as $retailer_product) {
                                        array_push($users_temp, User::where('id', $retailer_product->user_id)->first()->email);
                                    }

                                    if(count($users_temp)> 0) {
                                        foreach($users_temp as $user){
                                            try{
                                                Mail::to($user)->send(new VariantStockOutMail($product));
                                            }
                                            catch (\Exception $e){
                                            }
                                        }
                                    }
                                }
                                $this->notify->generate('Product','Variant Out Of Stock',$variant->title.' of ' . $product->title . ' is running out of stock, kindly update the stock on your store',$product);
                            }

                            $variant->product_id = $product->id;
                            $variant->save();
                        }

                        if($product->to_woocommerce == 1) {

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
                        }

                        $this->log->store(0, 'Product', $product->id, $product->title,'Variant Updated');

                        //Artisan::call('app:sku-quantity-change',['product_id'=> $product->id]);

                    }

//                    else if ($type == 'add-additional-tab'){
//                        $additional_tab = new AdditionalTab();
//                        $additional_tab->title = $request->input('tab-title');
//                        $additional_tab->description = $request->input('tab-description');
//                        $additional_tab->product_id = $product->id;
//                        $additional_tab->save();
//
//
////                        $resp =  $woocommerce->get('products/'.$product->woocommerce_id);
////                        if(count($resp->meta_data) > 0){
////                            $resp =  $woocommerce->put('products/'.$product->woocommerce_id, ["meta_data" => null]);
////                        }
//
//                        $meta_data_array = [];
//                        array_push($meta_data_array,[
//                            "key" => $additional_tab->title,
//                            "value"=> $additional_tab->description,
//                        ]);
//
//                        $productdata = [
//                            "meta_data" => $meta_data_array
//                        ];
//
//                        $resp =  $woocommerce->put('products/'.$product->woocommerce_id, $productdata);
//                    }

                    else if ($type == 'fulfilled') {
                        $product->fulfilled_by = $request->input('fulfilled-by');
                        $product->sortBy = $request->input('sortBy');
                        $product->save();
                        $this->log->store(0, 'Product', $product->id, $product->title,'Product Basic Information Updated');
                    }

                    else if($type == 'marketing_video_update'){
                        $product->marketing_video = $request->input('marketing_video');
                        $product->save();
                        $this->log->store(0, 'Product', $product->id, $product->title,'Product Marketing Video Updated');
                    }

                    else if ($type == 'category') {
                        if ($request->category) {
                            $product->has_categories()->sync($request->category);
                        }
                        if ($request->sub_cat) {
                            $product->has_subcategories()->sync($request->sub_cat);
                        }
                        if ($request->sub_sub_cat) {
                            $product->has_sub_sub_categories()->sync($request->sub_sub_cat);
                        }
                        $product->save();

                        if($product->to_woocommerce == 1) {
                            /*Updating Categories on Woocommerce and getting there id's so that we can pass them to products array*/
                            if (count($product->has_categories) > 0) {
                                $product_categories = $product->has_categories->pluck('woocommerce_id')->toArray();
                                $categories_id_array = [];

                                foreach ($product_categories as $item) {
                                    array_push($categories_id_array, [
                                        'id' => $item,
                                    ]);
                                }

                                $productdata = [
                                    "categories" => $categories_id_array
                                ];

                                /*Updating Product On Woocommerce*/
                                $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);
                            }
                        }

                        $this->log->store(0, 'Product', $product->id, $product->title,'Product Category Updated');
                    }

                    else if ($type == 'organization') {
                        $product->type = $request->product_type;
                        $product->vendor = $request->vendor;

                        if($request->tags)
                            $product->tags()->sync($request->tags);

                        $product->save();

                        if($product->to_woocommerce == 1) {

                            /*Updating Tags on Woocommerce */
                            $tags_array = [];
                            foreach ($product->tags()->get() as $tag) {
                                array_push($tags_array, [
                                    'id' => $tag->woocommerce_id,
                                ]);
                            }

                            /*Updating Product On Woocommerce*/
                            $response = $woocommerce->put('products/' . $product->woocommerce_id, ["tags" => $tags_array]);
                        }

                        $this->log->store(0, 'Product', $product->id, $product->title,'Product Vendor Updated');
                    }

                    else if ($type == 'more-details') {
                        if($request->input('processing_time') != null){
                            $product->processing_time = $request->input('processing_time');
                        }
                        if ($request->platforms) {
                            $product->has_platforms()->sync($request->platforms);
                        }
                        $product->save();

                        if($product->to_woocommerce == 1) {

                            $resp = $woocommerce->get('products/' . $product->woocommerce_id);
                            if (count($resp->meta_data) > 0) {
                                $resp = $woocommerce->put('products/' . $product->woocommerce_id, ["meta_data" => null]);
                            }

                            $meta_data_array = [];
                            if (count($product->has_platforms) > 0) {
                                $platforms = '';
                                foreach ($product->has_platforms as $index => $platform) {
                                    $platforms = $platforms . $platform->name . ',';
                                }

                                array_push($meta_data_array, [
                                    "key" => "warned_platform",
                                    "value" => $platforms,
                                ]);

                                $productdata = [
                                    "meta_data" => $meta_data_array
                                ];

                                $resp = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);
                            }

                        }

                        $this->log->store(0, 'Product', $product->id, $product->title,'Product Basic Information Updated');

                        $this->product_status_change($request, $product);
                    }

                    else if ($type == 'shop-preferences'){
                        $product->global = $request->input('global');
                        $product->save();
                        if($request->input('global') == 0 && $request->has('shops') && count($request->input('shops')) > 0){
                            $product->has_preferences()->sync($request->input('shops'));
                        }
                        if($request->input('global') == 0 && $request->has('non_shopify_users') && count($request->input('non_shopify_users')) > 0){
                            $product->has_non_shopify_user_preferences()->sync($request->input('non_shopify_users'));
                        }
                        $this->log->store(0, 'Product', $product->id, $product->title,'Product Shop Preferences Updated');

                    }

                    else if ($type == 'tiered-pricing') {
                        $variants = $request->variant_id;
                        if($variants != null) {
                            foreach ($variants as $variant) {
                                if(TieredPrice::where('product_variant_id', $variant)->where('product_id', $id)->exists()) {
                                    TieredPrice::where('product_variant_id', $variant)->where('product_id', $id)->delete();
                                }
                                for($i=0; $i< count($request->input('min_qty'.$variant)); $i++) {

                                    if($request->input('min_qty'.$variant)[$i] != null) {
                                        $item = new TieredPrice();
                                        $item->product_variant_id = $variant;
                                        $item->product_id = $id;
                                        $item->min_qty = $request->input('min_qty'.$variant)[$i];
                                        if($request->input('max_qty'.$variant)[$i] == null) {
                                            $item->max_qty = $product->quantity;
                                        }
                                        else {
                                            $item->max_qty = $request->input('max_qty'.$variant)[$i];
                                        }
                                        $item->type = $request->input('type'.$variant)[$i];
                                        $item->price = $request->input('tiered_price'.$variant)[$i];
                                        $item->save();
                                    }

                                }
                            }
                        }
                    }

                    else if($type == 'single-variant-tiered-pricing') {
                        if(TieredPrice::where('product_id', $id)->exists()) {
                            TieredPrice::where('product_id', $id)->delete();
                        }

                        for($i=0; $i< count($request->input('min_qty')); $i++) {

                            if($request->input('min_qty')[$i] != null) {
                                $item = new TieredPrice();
                                $item->product_variant_id = null;
                                $item->product_id = $id;
                                $item->min_qty = $request->input('min_qty')[$i];
                                if($request->input('max_qty')[$i] == null) {
                                    $item->max_qty = $product->quantity;
                                }
                                else {
                                    $item->max_qty = $request->input('max_qty')[$i];
                                }
                                $item->type = $request->input('type')[$i];
                                $item->price = $request->input('tiered_price')[$i];
                                $item->save();
                            }

                        }
                    }

                    else if ($type == "single-variant-warehouse-inventory") {
                        foreach ($request->war_id as $counter => $warhouse_id) {
                            if(WarehouseInventory::where('product_id', $product->id)->where('warehouse_id', $warhouse_id)->exists()){
                                $inventory = WarehouseInventory::where('product_id', $product->id)->where('warehouse_id', $warhouse_id)->first();
                            }
                            else{
                                $inventory = new WarehouseInventory();
                            }

                            $inventory->product_id = $product->id;
                            $inventory->warehouse_id = $warhouse_id;
                            $inventory->quantity = $request->war_qty_for_single_variant[$counter];
                            $inventory->save();
                        }
                    }

                    else if ($type == "multi-variant-warehouse-inventory") {
                        $variants = $request->variant_ids_for_warehouse;
                        $warehouses = array_unique($request->war_id);
                        if($variants) {
                            foreach ($variants as $variant_id) {
                                for($i=0; $i< count($warehouses); $i++) {
                                    if(WarehouseInventory::where('product_variant_id', $variant_id)->where('warehouse_id', $warehouses[$i])->exists()) {
                                        $inventory = WarehouseInventory::where('product_variant_id', $variant_id)->where('warehouse_id', $warehouses[$i])->first();
                                    }
                                    else{
                                        $inventory = new WarehouseInventory();
                                    }

                                    $inventory->product_variant_id = $variant_id;
                                    $inventory->warehouse_id = $warehouses[$i];
                                    $inventory->quantity = $request->input('war_qty_for_multi_variant_'.$variant_id)[$i];
                                    $inventory->save();
                                }
                            }
                        }
                    }
                }
            }
            DB::commit();
            Artisan::call('app:sku-quantity-change',['product_id'=> $product->id]);
            return redirect()->back()->with('success', 'Product Updated Successfully');
        }
        catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function editTabDetails(Request $request, $id) {

        $product = Product::find($id);
        $woocommerce = $this->helper->getWooCommerceAdminShop();

        $additional_tab = AdditionalTab::find($request->input('tab_id'));

        $resp =  $woocommerce->get('products/'.$product->woocommerce_id);
        if(count($resp->meta_data) > 0){
            $meta_data = $resp->meta_data;
            $updated_array = [];
            foreach ($meta_data as $data) {
                if($data->key == $additional_tab->title) {
                    array_push($updated_array, [
                       'key' =>  $request->input('tab-title'),
                       'value' => $request->input('tab-description')
                    ]);
                }
                else {
                    array_push($updated_array, [
                        'key' =>  $data->key,
                        'value' => $data->value
                    ]);
                }
            }

            $data = ['regular_price' => '89.54', 'meta_data' => (array) null, 'categories' => (array) null];
            $resp =  $woocommerce->put('products/'.$product->woocommerce_id, $data);

            $data = ['meta_data' => $updated_array];
            $resp =  $woocommerce->put('products/'.$product->woocommerce_id, $data);
        }


        $additional_tab->title = $request->input('tab-title');
        $additional_tab->description = $request->input('tab-description');
        $additional_tab->product_id = $product->id;
        $additional_tab->save();

        $this->log->store(0, 'Product', $product->id, $product->title,'Product Tab Updated');
    }

    public function updateExistingProductNewVariantsOld(Request $request, $id) {
        $product = Product::find($id);
        $shop = $this->helper->getShop();
        if($product != null) {
            if ($request->type == 'existing-product-new-variants') {
                    if ($request->variants) {
                        $product->variants = $request->variants;
                    }
                    $product->save();
                    $this->ProductVariants($request, $product->id);
                    $variants_array =  $this->variants_template_array($product);

                    $productdata = [
                        "product" => [
                            "options" => $this->options_update_template_array($product),
                            "variants" => $variants_array,
                        ]
                    ];
                    $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'.json',$productdata);
                    $shopifyVariants = $resp->body->product->variants;
                    foreach ($product->hasVariants as $index => $v){
                        $v->shopify_id = $shopifyVariants[$index]->id;
                        $v->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                        $v->save();
                    }

                    $this->notify->generate('Product','Product Variant Added','New Variants are added to '.$product->title,$product);
                    $this->log->store(0, 'Product', $product->id, $product->title,'New Variants Option Added');
                    return redirect()->route('product.edit', $product->id)->with('success', 'Product Variants Updated Successfully');

                }
            return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');

        }
        return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');

    }

    public function updateExistingProductNewVariants(Request $request, $id) {

        $product = Product::find($id);
        $woocommerce = $this->helper->getWooCommerceAdminShop();

        if($product != null) {
            if ($request->type == 'existing-product-new-variants') {
                $product->attribute1 = $request->attribute1;
                $product->attribute2 = $request->attribute2;
                $product->attribute3 = $request->attribute3;
                $product->save();

                if ($request->variants) {
                    $product->variants = $request->variants;
                }
                $product->save();
                $this->ProductVariants($request, $product->id);

                $attributes_array = $this->attributes_template_array($product);


                $productdata = [
                    "attributes" => $attributes_array,
                    "type" => "variable"
                ];

                /*Updating Product Attributes On Woocommerce*/
                if($product->to_woocommerce == 1) {
                    $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);

                    $variants_array = $this->woocommerce_variants_template_array($product, $response->attributes);

                    $variantdata = [
                        'create' => $variants_array
                    ];

                    /*Creating Product Variations On Woocommerce*/
                    $response = $woocommerce->post("products/" . $product->woocommerce_id . "/variations/batch", $variantdata);

                    $woocommerce_variants = $response->create;
                    foreach ($product->hasVariants as $index => $v) {
                        $v->woocommerce_id = $woocommerce_variants[$index]->id;
//                $v->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                        $v->save();
                    }
                }
                $this->notify->generate('Product','Product Variant Added','New Variants are added to '.$product->title,$product);
                $this->log->store(0, 'Product', $product->id, $product->title,'New Variants Option Added');

                return redirect()->route('product.edit', $product->id)->with('success', 'Product Variants Updated Successfully');

            }
            return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');

        }
        return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');

    }

    public function updateExistingProductOldVariantsOld(Request $request, $id) {
        $product = Product::find($id);
        $shop = $this->helper->getShop();
        if($product != null) {
            if($request->type == 'existing-product-update-variants') {

                    $product->variants = 1;
                    $product->save();
                    $variants_array = $this->ProductVariantsUpdate($request, $product->id, $product);

                    sleep(3);

                    $options_array = [];

                    $option1_array = [];
                    foreach ($variants_array as $index => $v) {
                        array_push($option1_array, $v['option1']);
                    }

                    $option1_array_unique = array_unique($option1_array);

                    if($option1_array_unique[0] != '') {
                        $temp = [];
                        foreach ($option1_array_unique as $a) {
                            array_push($temp, $a);
                        }
                        array_push($options_array, [
                            'name' => 'Option1',
                            'position' => '1',
                            'values' => $temp,
                        ]);

                    }


                    $option2_array = [];
                    foreach ($variants_array as $index => $v) {
                        array_push($option2_array, $v['option2']);
                    }

                    $option2_array_unique = array_unique($option2_array);

                    if($option2_array_unique[0] != '') {
                        $temp = [];
                        foreach ($option2_array_unique as $a) {
                            array_push($temp, $a);
                        }

                        array_push($options_array, [
                            'name' => 'Option2',
                            'position' => '2',
                            'values' => $temp,
                        ]);
                    }


                    $option3_array = [];
                    foreach ($variants_array as $index => $v) {
                        array_push($option3_array, $v['option3']);
                    }

                    $option3_array_unique = array_unique($option3_array);

                    if($option3_array_unique[0] != '') {
                        $temp = [];
                        foreach ($option3_array_unique as $a) {
                            array_push($temp, $a);
                        }

                        array_push($options_array, [
                            'name' => 'Option3',
                            'position' => '3',
                            'values' => $temp,
                        ]);
                    }

                    $productdata = [
                        "product" => [
                            "options" => $options_array,
                            "variants" => $variants_array,
                        ]
                    ];


                    $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'.json',$productdata);
                    $shopifyVariants = $resp->body->product->variants;

                    $product = Product::find($id);
                    foreach ($product->hasVariants as $index => $v){
                        $v->shopify_id = $shopifyVariants[$index]->id;
                        $v->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                        $v->save();
                    }

                    // Sending Notification To all Concerned Retailer Stores
                    $this->notify->generate('Product','Product Variant Added','New Variants are added to '.$product->title,$product);

                    $this->log->store(0, 'Product', $product->id, $product->title,'New Variants Option Updated');
                    return redirect()->route('product.edit', $product->id)->with('success', 'Product Variants Updated Successfully');

            }
            return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');
        }
        return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');

    }

    public function updateExistingProductOldVariants(Request $request, $id) {
        $product = Product::find($id);
        $woocommerce = $this->helper->getWooCommerceAdminShop();
        if($product != null) {
            if($request->type == 'existing-product-update-variants') {
                $product->attribute1 = $request->attribute1;
                $product->attribute2 = $request->attribute2;
                $product->attribute3 = $request->attribute3;
                $product->variants = 1;
                $product->save();

                $this->ProductVariantsUpdate($request, $product->id, $product);

                //sleep(5);


                if($product->to_woocommerce == 1) {
                    $attributes_array = $this->attributes_template_array($product);

                    $productdata = [
                        "attributes" => $attributes_array,
                        "type" => "variable"
                    ];

                    /*Updating Product Attributes On Woocommerce*/
                    $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);


                    $variants_array = $this->woocommerce_variants_template_array($product, $response->attributes);

                    $variantdata = [
                        'create' => $variants_array
                    ];

                    /*Creating Product Variations On Woocommerce*/
                    $response = $woocommerce->post("products/" . $product->woocommerce_id . "/variations/batch", $variantdata);

                    $woocommerce_variants = $response->create;
                    foreach ($product->hasVariants as $index => $v) {
                        $v->woocommerce_id = $woocommerce_variants[$index]->id;
                        //                $v->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                        $v->save();
                    }
                }

                // Sending Notification To all Concerned Retailer Stores
                $this->notify->generate('Product','Product Variant Added','New Variants are added to '.$product->title,$product);
                $this->log->store(0, 'Product', $product->id, $product->title,'New Variants Option Updated');
                return redirect()->route('product.edit', $product->id)->with('success', 'Product Variants Updated Successfully');

            }
            return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');
        }
        return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');

    }

    public function deleteExistingProductImageOld(Request $request, $id) {
        $product = Product::find($id);
        $shop =$this->helper->getShop();

        if($product != null) {
            $image =  Image::find($request->input('file'));
            $shop->api()->rest('DELETE', '/admin/api/2019-10/products/' . $product->shopify_id . '/images/'.$image->shopify_id.'.json');
            $image->delete();
            $this->log->store(0, 'Product', $product->id, $product->title,'Product Image Deleted');

            return response()->json([
                'success' => 'ok'
            ]);
        }

    }

    public function deleteExistingProductImage(Request $request, $id) {
        $product = Product::find($id);
        $woocommerce = $this->helper->getWooCommerceAdminShop();

        if($product != null) {
            $image =  Image::find($request->input('file'));
            $image->delete();
            $this->log->store(0, 'Product', $product->id, $product->title,'Product Image Deleted');

            $images_array = [];
            $product = Product::find($id);
            foreach ($product->has_images as $index => $image) {
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

                if (count($woocommerce_images) == count($product->has_images)) {
                    foreach ($product->has_images as $index => $image) {
                        $image->woocommerce_id = $woocommerce_images[$index]->id;
                        $image->save();
                    }
                }
            }


            return response()->json([
                'success' => 'ok'
            ]);
        }

    }

    public function save_old(Request $request)
    {
        if (Product::where('title', $request->title)->exists()) {
            $product = Product::where('title', $request->title)->first();
        } else {
            $product = new Product();
        }
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
        $product->status =  $request->input('status');
        $product->marketing_video =  $request->input('marketing_video');
        $product->processing_time = $request->input('processing_time');
        $product->sortBy = $request->input('sortBy');

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
            foreach ($request->file('images') as $image) {
                $destinationPath = 'images/';
                $filename = now()->format('YmdHi') . str_replace([' ','(',')'], '-', $image->getClientOriginalName());
                $image->move($destinationPath, $filename);
                $image = new Image();
                $image->isV = 0;
                $image->product_id = $product->id;
                $image->image = $filename;
                $image->save();
            }

        }

        $product->global = $request->input('global');
        $product->save();

        if($request->input('global') == 0 && $request->has('shops') && count($request->input('shops')) > 0){
            $product->has_preferences()->attach($request->input('shops'));
        }

        $this->log->store(0, 'Product', $product->id, $product->title,  'Created');

        return redirect()->route('import_to_shopify',$product->id);
    }

    public function save(Request $request)
    {

        $this->validate($request, [
           'sku' => 'required|unique:products',
           'title' => 'required|unique:products'
        ]);

        DB::beginTransaction();
        try{
            if (Product::where('title', $request->title)->exists()) {
                $product = Product::where('title', $request->title)->first();
            } else {
                $product = new Product();
            }
            $product->title = $request->title;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->slug = \Illuminate\Support\Str::slug($request->title, '-');
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->recommended_price = $request->recommended_price;
            $product->cost = $request->cost;
            $product->type = $request->product_type;
            $product->vendor = $request->vendor;
            //$product->tags = $request->tags;
            $product->quantity = $request->quantity;
            $product->weight = $request->weight;
            $product->length = $request->length;
            $product->width = $request->width;
            $product->height = $request->height;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->attribute1 = $request->attribute1;
            $product->attribute2 = $request->attribute2;
            $product->attribute3 = $request->attribute3;
            $product->fulfilled_by = $request->input('fulfilled-by');
            $product->status =  $request->input('status');
            $product->marketing_video =  $request->input('marketing_video');
            $product->processing_time = $request->input('processing_time');
            $product->sortBy = $request->input('sortBy');

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
            if ($request->sub_sub_cat) {
                $product->has_sub_sub_categories()->attach($request->sub_sub_cat);
            }
            if ($request->platforms) {
                $product->has_platforms()->attach($request->platforms);
            }
            if ($request->variants) {
                $this->ProductVariants($request, $product->id);
            }
            else {
                $inventory = new WarehouseInventory();
                $inventory->product_id = $product->id;
                $inventory->warehouse_id = 3;
                $inventory->quantity = $product->quantity;
                $inventory->save();
            }

            if($request->tags) {
                foreach ($request->tags as $tag) {
                    $product->tags()->attach($tag);
                }
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $destinationPath = 'images/';
                    $filename = now()->format('YmdHi') . str_replace([' ','(',')'], '-', $image->getClientOriginalName());
                    $image->move($destinationPath, $filename);
                    $image = new Image();
                    $image->isV = 0;
                    $image->product_id = $product->id;
                    $image->image = $filename;
                    $image->save();
                }
            }

            $product->global = $request->input('global');
            $product->save();

            if($request->input('global') == 0 && $request->has('shops') && count($request->input('shops')) > 0){
                $product->has_preferences()->attach($request->input('shops'));
            }

            $this->log->store(0, 'Product', $product->id, $product->title,  'Created');
            DB::commit();

            return redirect()->back()->with('success', 'Product Created Successfully');
            //return $this->import_to_woocommerce($product->id);
        }
        catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

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

            if($data->variant_cost[$i] == null) {
                $variants->cost = null;
            }
            else {
                $variants->cost = $data->variant_cost[$i];
            }

            $variants->sku = $data->variant_sku[$i];
            $variants->barcode = $data->variant_barcode[$i];
            $variants->product_id = $id;
            $variants->save();

            $inventory = new WarehouseInventory();
            $inventory->product_variant_id = $variants->id;
            $inventory->warehouse_id = 3;
            $inventory->quantity = $variants->quantity;
            $inventory->save();
        }
    }

    public function ProductVariantsUpdate($data, $id, $product)
    {
        $woocommerce = $this->helper->getAdminShop();

        $product = Product::find($id);
        if($product->to_woocommerce == 1) {
            foreach ($product->hasVariants as $v) {
                $res = $woocommerce->delete('products/' . $product->woocommerce_id . '/variations/' . $v->woocommerce_id, ['force' => true]);
                $v->delete();
            }
        }

        $product = Product::find($id);
        $product->hasVariants()->delete();

        for ($i = 0; $i < count($data->variant_title); $i++) {

            $variants = new ProductVariant();
            $options = explode('/', $data->variant_title[$i]);

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
            if($data->variant_cost[$i] == null) {
                $variants->cost = null;
            }
            else {
                $variants->cost = $data->variant_cost[$i];
            }
            $variants->sku = $data->variant_sku[$i];
            $variants->barcode = $data->variant_barcode[$i];
            $variants->product_id = $id;
            $variants->save();

        }
    }

    public function delete($id)
    {
        $product = Product::find($id);
        $woocommerce = $this->helper->getWooCommerceAdminShop();

        DB::beginTransaction();
        try{
            if($product->to_woocommerce == 1)
                $woocommerce->delete('products/'.$product->woocommerce_id, ['force' => true]);

            $variants = ProductVariant::where('product_id', $id)->get();
            foreach ($variants as $variant) {
                $variant->delete();
            }
            foreach ($product->has_images as $image){
                $image->delete();
            }
            $product->has_categories()->detach();
            $product->has_subcategories()->detach();

            $this->log->store(0, 'Product', $product->id, $product->title,'Deleted');


            $product->delete();

            // Sending Notification To all Concerned Retailer Stores
            $this->notify->generate('Product','Product Delete',$product->title.' has been deleted from Wefullfill, kindly remove this product from your store as well',$product);

            // Sending Notification Emails To all Concerned Retailer Stores
            if(count($product->has_retailer_products) > 0) {
                foreach ($product->has_retailer_products as $retailer_product) {
                    $u = User::find($retailer_product->user_id);
                    if($u){
                        try{
                            Mail::to($u)->send(new ProductDeleteMail($product));
                        }
                        catch (\Exception $e){
                        }
                    }
                }

            }

            DB::commit();
            return redirect()->back()->with('error', 'Product Deleted with Variants Successfully');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete_old($id)
    {
        $product = Product::find($id);
        $shop = $this->helper->getShop();
        $shop->api()->rest('DELETE', '/admin/api/2019-10/products/'.$product->shopify_id.'.json');
        $variants = ProductVariant::where('product_id', $id)->get();
        foreach ($variants as $variant) {
            $variant->delete();
        }
        foreach ($product->has_images as $image){
            $image->delete();
        }
        $product->has_categories()->detach();
        $product->has_subcategories()->detach();

        $this->log->store(0, 'Product', $product->id, $product->title,'Deleted');


        $product->delete();

        // Sending Notification To all Concerned Retailer Stores
        $this->notify->generate('Product','Product Delete',$product->title.' has been deleted from Wefullfill, kindly remove this product from your store as well',$product);

        // Sending Notification Emails To all Concerned Retailer Stores
        if(count($product->has_retailer_products) > 0) {
            $users_temp = [];
            foreach ($product->has_retailer_products as $retailer_product) {
                $u = User::find($retailer_product->user_id);
                if($u){
                    array_push($users_temp, $u->email);
                }
            }

            if(count($users_temp)> 0) {
                foreach($users_temp as $key => $ut){
                    try{
                        Mail::to($ut)->send(new ProductDeleteMail($product));
                    }
                    catch (\Exception $e){
                    }
                }


            }
        }

        return redirect()->back()->with('error', 'Product Deleted with Variants Successfully');
    }

    public function add_existing_product_new_variants(Request $request)
    {
        $product = Product::find($request->id);
        if ($product->varaints == 0) {
            return view('products.add_existing_product_new_variants')->with([
                'product' => $product
            ]);
        } else {
            return redirect('/products');
        }
    }

    public function update_existing_product_new_variants(Request $request)
    {
        $product = Product::find($request->id);
        if ($product->varaints !== 0) {
            return view('products.update_existing_product_new_variants')->with([
                'product' => $product
            ]);
        } else {
            return redirect('/products');
        }
    }

    public function import_to_shopify(Request $request)
    {
        $product = Product::find($request->id);
        if ($product != null) {
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
                $product_type = 'WeFullFill';
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
                    "statusd"=>  $published
                ]
            ];


            $response = $shop->api()->rest('POST', '/admin/api/2019-10/products.json', $productdata);
            $product_shopify_id =  $response->body->product->id;
            $product->shopify_id = $product_shopify_id;
            $price = $product->price;
            $product->save();

            $shopifyImages = $response->body->product->images;
            $shopifyVariants = $response->body->product->variants;

            if(count($product->hasVariants) == 0){

                $variant_id = $shopifyVariants[0]->id;
                $product->inventory_item_id =$shopifyVariants[0]->inventory_item_id;

                $product->save();
                $i = [
                    'variant' => [
                        'price' =>$price,
                        'sku' =>  $product->sku,
                        'grams' => $product->weight * 1000,
                        'weight' => $product->weight,
                        'weight_unit' => 'kg',
//                        "fulfillment_service" => "wefullfill",
                        'barcode' => $product->barcode,
//                        'inventory_quantity' => $product->quantity,
//                        'inventory_management' => 'wefullfill',
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
                    'location_id' => 46023344261,
                    'inventory_item_id' => $product->inventory_item_id,
                    'relocate_if_necessary' => true
                ];
                $res = $shop->api()->rest('POST', '/admin/api/2020-07/inventory_levels/connect.json', $data);
                /*Set Quantity*/

                $data = [
                    'location_id' => 46023344261,
                    'inventory_item_id' => $product->inventory_item_id,
                    'available' => $product->quantity,

                ];

                $res = $shop->api()->rest('POST', '/admin/api/2020-07/inventory_levels/set.json', $data);
            }
            foreach ($product->hasVariants as $index => $v){
                $v->shopify_id = $shopifyVariants[$index]->id;
                $v->inventory_item_id =$shopifyVariants[$index]->inventory_item_id;
                $v->save();
            }
            foreach ($product->has_platforms as $index => $platform){
                $index = $index+1;
                $productdata = [
                    "metafield" => [
                        "key" => "warned_platform".$index,
                        "value"=> $platform->name,
                        "value_type"=> "string",
                        "namespace"=> "platform"
                    ]
                ];
                $resp =  $shop->api()->rest('POST', '/admin/api/2019-10/products/'.$product_shopify_id.'/metafields.json',$productdata);
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

            $this->log->store(0, 'Product', $product->id, $product->title, 'Imported To Shopify');
            return redirect()->route('product.view',$product->id)->with('success','Product Generated and Push to Store Successfully!');
        }
    }

    public function delete_three_options_variants(Request $request, $product)
    {
        $deleted_variants = $product->hasVariants()->whereIn('option1', $request->input('delete_option1'))
            ->whereIn('option2', $request->input('delete_option2'))
            ->whereIn('option3', $request->input('delete_option3'))->get();
        $this->delete_variants($deleted_variants);
        return $deleted_variants;
    }

    public function delete_two_options_variants(Request $request, $product)
    {
        $deleted_variants = $product->hasVariants()->whereIn('option1', $request->input('delete_option1'))
            ->whereIn('option2', $request->input('delete_option2'))->get();
        $this->delete_variants($deleted_variants);
        return $deleted_variants;
    }

    public function delete_variants($variants){
        foreach ($variants as $variant){
            $variant->delete();
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
                'inventory_quantity' => $varaint->quantity,
                "fulfillment_service" => "wefullfill",
                'inventory_management' => 'wefullfill',
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

    public function delete_tab(Request $request){
        $tab = AdditionalTab::find($request->id);
        $product = Product::find($tab->product_id);
        $shop = $this->helper->getShop();
        $shop->api()->rest('DELETE', '/admin/api/2019-10/products/'.$product->shopify_id.'/metafields/'.$tab->shopify_id.'.json');
        $tab->delete();
        return redirect()->back()->with('success','Additional Tab Deleted Successfully!');
    }

    /**
     * @param Request $request
     * @param $product
     * @param $shop
     */
    public function product_status_change(Request $request, $product)
    {
        $product->status = $request->input('status');
        $product->save();
        if($product->status == 1)
            $published = 'publish';
        else
            $published = 'draft';

        if($product->to_woocommerce == 1) {
            $productdata = [
                "status"=>  $published,
            ];

            $woocommerce = $this->helper->getWooCommerceAdminShop();
            $response = $woocommerce->put('products/' . $product->woocommerce_id, $productdata);
        }
    }

    public function getExportFile(Request $request){
        $shopify_product_id = $request->input('product_id');
        $shop = Shop::where('shopify_domain',$request->input('shop'))->first();
        $productJson  = $shop->api()->rest('GET', '/admin/api/2019-10/products/' . $shopify_product_id . '.json');

        if($productJson->errors){
            return redirect()->back();
        }
        else{
            $productData = $productJson->body->product;
        }
        $variants = $productData->variants;
        $images = $productData->images;
        $options = $productData->options;
        $product = [];
        foreach ($variants as $index => $variant){
            $temp = [];
            $temp['handle'] = $productData->handle;
            if($index == 0){

                $temp['title'] = $productData->title;
                $temp['body_html'] = strip_tags($productData->body_html);
                $temp['vendor'] = $productData->vendor;
                $temp['product_type'] = $productData->product_type;
                $temp['tags'] = $productData->tags;
                if($productData->published_at != null){
                    $temp['published'] = 'TRUE';
                }
                else{
                    $temp['published'] = 'FALSE';
                }
                if(array_key_exists('0',$options)){
                    $temp['option1_name'] = $options[0]->name;
                }
                else{
                    $temp['option1_name'] = '';
                }
                if(array_key_exists('1',$options)){
                    $temp['option2_name'] = $options[1]->name;
                }
                else{
                    $temp['option2_name'] = '';
                }
                if(array_key_exists('2',$options)){
                    $temp['option3_name'] = $options[2]->name;
                }
                else{
                    $temp['option3_name'] = '';
                }

            }
            else{
                $temp['title'] = '';
                $temp['body_html'] = '';
                $temp['vendor'] ='';
                $temp['product_type'] = '';
                $temp['tags'] = '';
                $temp['published'] = '';
                $temp['option1_name'] = '';
                $temp['option2_name'] = '';
                $temp['option3_name'] = '';
            }
            $temp['variant_option1'] = $variant->option1;
            $temp['variant_option2'] = $variant->option2;
            $temp['variant_option3'] = $variant->option3;
            $temp['variant_sku'] = $variant->sku;
            $temp['variant_grams'] = $variant->grams;
            $temp['variant_inventory_tracker'] = $variant->inventory_management;
            $temp['variant_qty'] = $variant->inventory_quantity;
            $temp['variant_policy'] = $variant->inventory_policy;
            $temp['variant_fulfillment_service'] = $variant->fulfillment_service;
            $temp['variant_price'] = $variant->price;
            $temp['variant_compare_price'] = '';
            $temp['variant_shipping'] = $variant->requires_shipping;
            $temp['variant_taxable'] = $variant->taxable;
            $temp['variant_barcode'] = $variant->barcode;
            $temp['variant_weight_unit'] = $variant->weight_unit;
            $temp['variant_weight'] = $variant->weight;

            foreach ($images as $i){
                if($i->id == $variant->image_id){
                    $temp['variant_image'] = $i->src;
                    break;
                }
            }
            if(!array_key_exists('variant_image',$temp)){
                $temp['variant_image'] = '';
            }
            array_push($product,$temp);

        }

        foreach ($images as $index => $image){
            if(array_key_exists($index,$product)){
                $product[$index]['image_src'] = $image->src;
                $product[$index]['image_position'] = $image->position;
                $product[$index]['image_alt'] = $image->alt;
            }
            else{
                $temp = [];
                $temp['handle'] = $productData->handle;
                $temp['title'] = '';
                $temp['body_html'] = '';
                $temp['vendor'] ='';
                $temp['product_type'] = '';
                $temp['tags'] = '';
                $temp['published'] = '';
                $temp['option1_name'] = '';
                $temp['option2_name'] = '';
                $temp['option3_name'] = '';
                $temp['variant_option1'] = '';
                $temp['variant_option2'] = '';
                $temp['variant_option3'] = '';
                $temp['variant_sku'] = '';
                $temp['variant_grams'] ='';
                $temp['variant_inventory_tracker'] = '';
                $temp['variant_qty'] = '';
                $temp['variant_policy'] ='';
                $temp['variant_fulfillment_service'] = '';
                $temp['variant_price'] = '';
                $temp['variant_compare_price'] = '';
                $temp['variant_shipping'] = '';
                $temp['variant_taxable'] = '';
                $temp['variant_barcode'] = '';
                $temp['variant_weight_unit'] = '';
                $temp['variant_weight'] = '';
                $temp['variant_image'] = '';
                $temp['image_src'] = $image->src;
                $temp['image_position'] = $image->position;
                $temp['image_alt'] = $image->alt;
                array_push($product,$temp);
            }
        }
        return Excel::download(new ProductsExport($product), $productData->handle.'.csv');
    }

    public function change_image($id,$image_id,Request $request){
        if($request->input('type') == 'product'){
            //$shop = $this->helper->getWooCommerceAdminShop();
            $shop = null;
            $variant = ProductVariant::find($id);
            if($variant->linked_product != null) {
                if ($variant->linked_product->woocommerce_id != null) {
                    $image = Image::find($image_id);
                    return $this->woocommerce_image_selection($image_id, $image, $shop, $variant);
                }
                else if($variant->linked_product->tooWoocommerce == 0)
                {
                    $variant->image = $image_id;
                    $variant->save();

                    return response()->json([
                        'message' => 'success'
                    ]);
                }
                else{
                    return response()->json([
                        'message' => 'false'
                    ]);
                }
            }
            else{
                return response()->json([
                    'message' => 'false'
                ]);
            }
        }
        else if($request->input('type') == 'retailer-product') {
            $variant = RetailerProductVariant::find($id);
            $shop = $this->helper->getWooShop();
            if($variant->linked_product != null) {
                if ($variant->linked_product->woocommerce_id != null) {
                    $image = RetailerImage::find($image_id);
                    return $this->woocommerce_image_selection($image_id, $image, $shop, $variant);
                }
                else if($variant->linked_product->to_woocommerce == 0)
                {
                    $variant->image = $image_id;
                    $variant->save();
                }
                else{
                    return response()->json([
                        'message' => 'false'
                    ]);
                }
            }
            else{
                return response()->json([
                    'message' => 'false'
                ]);
            }
        }
        else{

            $variant = RetailerProductVariant::find($id);
            $shop = $this->helper->getSpecificShop($variant->shop_id);
            if($variant->linked_product != null){
                if($variant->linked_product->toShopify == 1){
                    $image = RetailerImage::find($image_id);
                    return $this->shopify_image_selection($image_id, $image, $shop, $variant);
                }
                else{
                    $variant->image = $image_id;
                    $variant->save();
                    return response()->json([
                        'message' => 'success'
                    ]);
                }
            }
            else{
                return response()->json([
                    'message' => 'false'
                ]);
            }
        }

    }

    public function shopify_image_selection($image_id, $image, $shop, $variant)
    {
        $variant_ids = [];
        foreach ($image->has_variants as $v) {
            array_push($variant_ids, $v->shopify_id);
        }
        array_push($variant_ids,$variant->shopify_id);
        $i = [
            'image' => [
                'id' => $image->shopify_id,
                'variant_ids' => $variant_ids
            ]
        ];
        $imagesResponse = $shop->api()->rest('PUT', '/admin/api/2019-10/products/' . $variant->linked_product->shopify_id . '/images/' . $image->shopify_id . '.json', $i);
        if (!$imagesResponse->errors) {
            $variant->image = $image_id;
            $variant->save();
            return response()->json([
                'message' => 'success'
            ]);
        } else {
            dd($imagesResponse);
            return response()->json([
                'message' => 'false'
            ]);
        }
    }

    public function woocommerce_image_selection($image_id, $image, $shop, $variant)
    {
        $variant_ids = [];
        foreach ($image->has_variants as $v) {
            array_push($variant_ids, $v->woocommerce_id);
        }
        array_push($variant_ids,$variant->woocommerce_id);
        $data = [
            'image' => [
                'id' => $image->woocommerce_id,
            ]
        ];


        $imagesResponse = $shop->put('products/'.$variant->linked_product->woocommerce_id.'/variations/'.$variant->woocommerce_id, $data);
        if ($imagesResponse->id) {
            $variant->image = $image_id;
            $variant->save();
            return response()->json([
                'message' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => 'false'
            ]);
        }
    }

    public function update_image_position(Request $request){
        $positions = $request->input('positions');
        $product = $request->input('product');
        $images_array = [];
        //$shop = $this->helper->getShop();
        foreach ($positions as $index => $position){
            $image = Image::where('product_id',$product)
                ->where('id',$position)->first();
            array_push($images_array, [
                'id' => $image->shopify_id,
                'position' => $index + 1,
            ]);
        }

        $related_product = Product::find($product);
        if($related_product != null){
            $data = [
                'product' => [
                    'images' => $images_array
                ]
            ];
            //$imagesResponse = $shop->api()->rest('PUT', '/admin/api/2019-10/products/' . $related_product->shopify_id .'.json', $data);
//            if(!$imagesResponse->errors){
                foreach ($positions as $index => $position){
                    $image = Image::where('product_id',$product)
                        ->where('id',$position)->first();
                    $image->position = $index + 1;
                    $image->save();
                }
                return response()->json([
                    'message' => 'success',
                ]);
//            }else{
//                return response()->json([
//                    'message' => 'error'
//                ]);
//            }

        }
        else{
            return response()->json([
                'message' => 'error'
            ]);
        }
    }

    public function product_notification(Request $request,$id){
        $product = Product::find($id);
        $this->notify->generate('Product','Product Update',$product->title.' Information Updated',$product);
        Artisan::call('app:sku-quantity-change',['product_id'=> $product->id]);
    }

    public function download_sku($id)
    {
        $product = Product::find($id);
        $variants = $product->hasVariants;
        return Excel::download(new ProductVariantExport($variants), 'Variants_SKU_list.csv');

    }

    public function import_to_woocommerce($id)
    {
        $product = Product::find($id);
        $woocommerce = $this->helper->getWooCommerceAdminShop();

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
            foreach ($product->has_images as $index => $image) {
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

            /*Platfroms*/
            $meta_data_array = [];
            $platforms = null;
            if(count($product->has_platforms) > 0) {
                foreach ($product->has_platforms as $index => $platform){
                    $platforms = $platforms . $platform->name . ',';
                }
            }

            array_push($meta_data_array,[
                "key" => "warned_platform",
                "value"=> $platforms,
            ]);



            if($product->status == 1)
                $published = 'publish';
            else
                $published = 'draft';


            if($product->variants == 1)
            {
                $product_type = 'variable';
                $productdata['attributes'] = $attributes_array;
            }
            else
            {
                $product_type = 'simple';
            }


            $productdata = [
                "name" => $product->title,
                "description" => $product->description,
                "short_description" => $product->short_description,
                "slug" => $product->slug,
                "tags" => $tags_array,
                "type" => $product_type,
                "images" => $images_array,
                "published"=>  $published,
                //"sale_price" => $product->price,
                "regular_price" => $product->price,
                "sku" => $product->sku,
                "weight" => $product->weight,
                "manage_stock" => true,
                "stock_quantity" => $product->quantity,
                "dimensions" => $dimension_array,
                "categories" => $categories_array,
                "meta_data" => $meta_data_array
            ];

            /*Creating Product On Woocommerce*/
            $response = $woocommerce->post('products', $productdata);

            $product_woocommerce_id =  $response->id;
            $product->woocommerce_id = $product_woocommerce_id;
            $product->to_woocommerce = 1;
            $product->save();

            $woocommerce_images = $response->images;

            if (count($woocommerce_images) == count($product->has_images)) {
                foreach ($product->has_images as $index => $image) {
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
//                $v->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                    $v->save();
                }
            }


            $this->log->store(0, 'Product', $product->id, $product->title, 'Product Imported To Woocommerce');

            return redirect()->back()->with('success','Product Push to Store Successfully!');
        }
        else{
            echo 'imported already';
        }
    }

    public function import_old_product_to_woocommerce($id)
    {
        $product = Product::find($id);
        $woocommerce = $this->helper->getWooCommerceAdminShop();

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
            foreach ($product->has_images()->orderBy('position', 'ASC')->get() as $index => $image) {
                if ($image->isV == 0) {
                    $src = asset('images') . '/' . $image->image;
                } else {
                    $src = asset('images/variants') . '/' . $image->image;
                }
                array_push($images_array, [
                    'alt' => $product->title . '_' . $index,
                    'name' => $product->title . '_' . $index,
                    'src' => $src,
                ]);
            }

            /*Tags*/
            $tags_array = [];
            $product = Product::find($id);
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

            /*Platfroms*/
            $meta_data_array = [];
            $platforms = null;
            if(count($product->has_platforms) > 0) {
                foreach ($product->has_platforms as $index => $platform){
                    $platforms = $platforms . $platform->name . ',';
                }
            }

            array_push($meta_data_array,[
                "key" => "warned_platform",
                "value"=> $platforms,
            ]);



            if($product->status == 1)
                $published = 'publish';
            else
                $published = 'draft';


            if($product->variants == 1)
                $product_type = 'variable';
            else
                $product_type = 'simple';


            $productdata = [
                "name" => $product->title,
                "description" => $product->description,
                "short_description" => $product->short_description,
                "slug" => $product->slug,
                "tags" => $tags_array,
                "type" => $product_type,
                "attributes" => $attributes_array,
                "images" => $images_array,
                "published"=>  $published,
                //"sale_price" => $product->price,
                "regular_price" => $product->price,
                "sku" => $product->sku,
                "weight" => $product->weight,
                "manage_stock" => true,
                "stock_quantity" => $product->quantity,
                "dimensions" => $dimension_array,
                "categories" => $categories_array,
                "meta_data" => $meta_data_array
            ];


            /*Creating Product On Woocommerce*/
            $response = $woocommerce->post('products', $productdata);

            $product_woocommerce_id =  $response->id;
            $product->woocommerce_id = $product_woocommerce_id;
            $product->to_woocommerce = 1;
            $product->save();

            $woocommerce_images = $response->images;

            if (count($woocommerce_images) == count($product->has_images)) {
                foreach ($product->has_images as $index => $image) {
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
//                $v->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                    $v->save();
                }
            }


            $this->log->store(0, 'Product', $product->id, $product->title, 'Product Imported To Woocommerce');
            DB::commit();

            return redirect()->back()->with('success','Product Push to Store Successfully!');
        }
        else{
            echo 'imported already';
        }
    }

    public function attributes_template_array($product){

        $product = Product::find($product->id);

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
        $product = Product::find($product->id);

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

            if($varaint->has_image != null){
                $array_item['image']['id'] = $varaint->has_image->woocommerce_id;
            }

            array_push($variants_array, $array_item);
        }

        return $variants_array;
    }

    public function woocommerce_variants_template_array_for_update_existing_function($product){
        $product = Product::find($product->id);

        if(is_null($product->weight)) {
            $weight = 0.0;
        }
        else {
            $weight = $product->weight;
        }

        $variants_array = [];
        foreach ($product->hasVariants()->where('woocommerce_id', null)->get() as $index => $varaint) {
            $array_item = [];
            $array_item['attributes'] = [];

            $array_item['regular_price'] = $varaint->price;
            //$array_item['sale_price'] = $varaint->cost;
            $array_item['sku'] = $varaint->sku;
            $array_item['stock_quantity'] = $varaint->quantity;
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

    public function getAdminProducts() {
        $products = Product::whereNull('is_dropship_product')->newQuery()
        ->select('id', 'title', 'price')
        ->with(['has_images:id,position,image,product_id', 'hasVariants:id,option1,option2,option3,price,product_id'])
        ->get();

        return view('non_shopify_users.orders.product-browse-section')->with([
            'products' => $products,
        ])->render();
    }

    public function getUserDropshipProducts() {
        $user = User::find(Auth::id());
        $dropship_requests = DropshipRequest::where('user_id', $user->id)->get();

        $dropship_products_id = [];

        foreach ($dropship_requests as $request) {
            foreach($request->dropship_products as $product){
                array_push($dropship_products_id, $product->id);
            }
        }

        $admin_products = Product::whereIn('dropship_product_id', $dropship_products_id)->get();

        return view('non_shopify_users.orders.dropship-product-browse-section')->with([
            'products' => $admin_products,
        ])->render();
    }

    public function syncInventoryWithInflow() {

        $this->dispatch(new SyncInflowInventory());

        return redirect()->back()->with('success', 'Inventory Synced Successfully!');
    }



    public function allproducts(){

        $id = Auth::id();
        $csv=Allproductcsv::where('user_id',$id)->get();
        return view('exportallproduct.exportallproducts',compact('csv'));
    }




    public function exportallproducts(Request $request){



        $products=Product::all();


        $fileName = 'product 12-01-2021.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Encoding" => "UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );


//For Images Column, comment for now
        // $columns = array('Product','Varientid', 'Product_Title','Description','Type','Vendor','Tags','Weight','SKU','Processing_time','Slug','Price','Compare_price','Cost','Quantity','Recommended_price','Option1 Name','Option1 Value','Option2 Name','Option2 Value','Option3 Name','Option3 Value','Category','Variant Barcode','Images');

        $columns = array('Product','Varientid', 'Product_Title','Description','Type','Vendor','Tags','Weight','SKU','Processing_time','Slug','Price','Compare_price','Cost','Quantity','Recommended_price','Option1 Name','Option1 Value','Option2 Name','Option2 Value','Option3 Name','Option3 Value','Category','Variant Barcode');

        $callback = function() use($products, $columns) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);


            foreach ($products as $getproduct) {
                $variant=ProductVariant::where('product_id',$getproduct->id)->get();
                $image=Image::where('product_id',$getproduct->id)->pluck('image')->toArray();


                if(count($variant)>0){
                    foreach ($variant as $key=> $get) {
                        $row['Product'] = $getproduct->id;
                        $row['Varientid'] = $get->id;


                        if($key==0) {
                            $row['Product_Title'] = "$getproduct->title";

//                        $row['Variant_Title'] = $get->title;

                            $row['Description'] = "$getproduct->description";
                            $row['Type'] = "$getproduct->type";
                            $row['Vendor'] = "$getproduct->vendor";

//                        $producttag=ProductTag::where('product_id',$getproduct->id)->get();
//
                            $gettag = $getproduct->tags()->pluck('name')->toArray();

                            $getalltag = implode(",", $gettag);
                            $row['Tags'] = "$getalltag";
                            $row['Weight'] = "$getproduct->weight";
                        }
                        else{
                            $row['Product_Title'] = '';
                            $row['Description'] = '';
                            $row['Type'] = '';
                            $row['Vendor'] = '';
                            $row['Tags'] = '';
                            $row['Weight'] = '';
                        }


                        $row['SKU'] = $get->sku;

                        if($key==0) {
                            $row['Processing_time'] = "$getproduct->processing_time";
                            $row['Slug'] = "$getproduct->slug";
                        }
                        else{
                            $row['Processing_time'] = '';
                            $row['Slug'] = '';
                        }
                        $row['Price'] = "$get->price";

                        $row['Compare_price'] = "$get->compare_price";

                        $row['Cost'] = "$get->cost";

                        $row['Quantity'] = "$get->quantity";


                        if($key==0) {
                            $row['Recommended_price'] = "$getproduct->recommended_price";
                        }

                        else{
                            $row['Recommended_price'] = '';
                        }

                        $row['Option1 Name']=$getproduct->attribute1;
                        $row['Option1 Value']=$get->option1;
                        $row['Option2 Name']=$getproduct->attribute2;
                        $row['Option2 Value']=$get->option2;
                        $row['Option3 Name']=$getproduct->attribute3;
                        $row['Option3 Value']=$get->option3;

                        if($key==0) {
                            $gettitle = $getproduct->categoriesget()->pluck('title')->toArray();
                            $getallttitle = implode(",", $gettitle);
                            $row['Category'] = "$getallttitle";
                        }
                        else{
                            $row['Category'] = '';
                        }

                        $row['Variant Barcode']=$get->barcode;
//                         if(count($image)>0){


//                             $getimage= implode(",",$image);
// //                            $getimage= json_encode( optional($image)->image);

//                             $row['Images'] =$getimage;



//                         }
//                         else{

//                             $row['Images']='';
//                         }



                        fputcsv($file, $row);
                    }
                }




                else {


                    $row['Product'] = $getproduct->id;
                    $row['Varientid'] = '';

                    $row['Product_Title'] = "$getproduct->title";

//                    $row['Variant_Title'] = '';

                    $row['Description']="$getproduct->description";
                    $row['Type']="$getproduct->type";
                    $row['Vendor']="$getproduct->vendor";

                    $gettag=$getproduct->tags()->pluck('name')->toArray();

                    $getalltag= implode(",",$gettag);
                    $row['Tags']="$getalltag";
                    $row['Weight']="$getproduct->weight";
                    $row['SKU'] = "$getproduct->sku";

                    $row['Processing_time']="$getproduct->processing_time";
                    $row['Slug']="$getproduct->slug";
                    $row['Price'] = "$getproduct->price";

                    $row['Compare_price'] = "$getproduct->compare_price";

                    $row['Cost'] = "$getproduct->cost";

                    $row['Quantity'] = "$getproduct->quantity";

                    $row['Recommended_price'] ="$getproduct->recommended_price";




                    $row['Option1 Name']=$getproduct->attribute1;
                    $row['Option1 Value']='';
                    $row['Option2 Name']=$getproduct->attribute2;
                    $row['Option2 Value']='';
                    $row['Option3 Name']=$getproduct->attribute3;
                    $row['Option3 Value']='';


                    $gettitle=$getproduct->categoriesget()->pluck('title')->toArray();
                    $getallttitle= implode(",",$gettitle);
                    $row['Category']="$getallttitle";

                    $row['Variant Barcode']='';
//                     if(count($image)>0){


//                         $getimage= implode(",",$image);
// //                            $getimage= json_encode( optional($image)->image);

//                         $row['Images'] =$getimage;
//                     }
//                     else{

//                         $row['Images']='';
//                     }


                    fputcsv($file, $row);
                }







            }

            fclose($file);
        };


        return response()->stream($callback, 200, $headers);



    }




    public function importallproducts(Request $request){


        $file = $request->file('file');


        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Valid File Extensions
        $valid_extension = array("csv");

        // 2MB in Bytes
        $maxFileSize = 3097152;

        // Check file extension
        if(in_array(strtolower($extension),$valid_extension)){


            // Check file size
            if($fileSize <= $maxFileSize){

                // File upload location
                $location = 'allproductsupload';

                // Upload file
                $file->move($location,$filename);

                // Import CSV to Database
                $filepath = public_path($location."/".$filename);

                // Reading file
                $file = fopen($filepath,"r");

                $importData_arr = array();
                $i = 0;

                while (($filedata = fgetcsv($file, 10000000, ",")) !== FALSE) {


                    $num = count($filedata );

                    // Skip first row (Remove below comment if you want to skip the first row)
                    if($i == 0){
                        $i++;
                        continue;
                    }
                    for ($c=0; $c < $num; $c++) {
                        $importData_arr[$i][] = $filedata [$c];
                    }
                    $i++;
                }


                fclose($file);



                $array_id=[];

//                dump($importData_arr);
                // Insert to MySQL database
                foreach($importData_arr as $key=> $importData){
//if($key==185){
//
//
//    dd($importData);
//}

//                    dump($importData[0]);
                    array_push($array_id,$importData[0]);

                    $get=Product::where('id',$importData[0])->first();

//                    dd($get);

                    if(!empty($get)){



                        $variant=ProductVariant::where('product_id',$importData[0])->get();

                        if(count($variant)>0){


                            if(isset($importData[2])) {
                            if($importData[2]!="") {
                                $get->title = $importData[2];
                            }
                            }

                            if(isset($importData[3])) {

                                if($importData[3]!="") {
                                $get->description = $importData[3];
                            }}
                            if(isset($importData[4])) {
                                if($importData[4]!="") {
                                $get->type = $importData[4];
                            }}

                            if(isset($importData[5])) {
                                if ($importData[5] != "") {
                                    $get->vendor = $importData[5];
                                }
                            }

                            if(isset($importData[6])) {
                                if($importData[6]!="") {
                                    $tagArray = explode(",", $importData[6]);


                                    foreach ($tagArray as $tags){


                                        $newtag= Tag::where('name',$tags)->first();
                                        if($newtag==null){
                                            $newtag=new Tag();
                                            $newtag->name=$tags;
                                            $newtag->save();
                                        }
                                    }

                                    $gettag = Tag::whereIn('name', $tagArray)->pluck('id')->toArray();


                                    $gettag1 = $get->tags()->sync($gettag);
                                }
                            }

                            if(isset($importData[7])) {
                                if($importData[7]!="") {
                                $get->weight = $importData[7];
                            }}

                            if(isset($importData[8])) {
                                $get->sku = $importData[8];
                            }


                            if(isset($importData[9])) {
                                if($importData[9]!="") {
                                $get->processing_time = $importData[9];

                            }}
                            if(isset($importData[10])) {
                                if($importData[10]!="") {
                                $get->slug = $importData[10];
                            }}

                            if(isset($importData[11])) {

                                $get->price = $importData[11];
                            }


                            if(isset($importData[12])) {

                                $get->compare_price = $importData[12];
                            }

                            if(isset($importData[13])) {

                                $get->cost = $importData[13];
                            }

                            if(isset($importData[14])) {

                                $get->quantity = $importData[14];
                            }
                            if(isset($importData[16])) {
                                if($importData[16]!="") {
                                $get->attribute1 = $importData[16];

                            }}
                         if(isset($importData[18])) {
                             if($importData[18]!="") {
                             $get->attribute2 = $importData[18];
                         }}

                            if(isset($importData[20])) {
                                if($importData[20]!="") {
                                $get->attribute3 = $importData[20];
                            }}

                            if(isset($importData[15])) {
                                if ($importData[15] != "") {
                                    $get->recommended_price = $importData[15];
                                }
                            }

                            if(isset($importData[1])) {
                                $variants = ProductVariant::where('id', $importData[1])->get();

                            }


                            if(isset($importData[22])) {
                                if($importData[22]!="") {
                                    $categoryArray = explode(",", $importData[22]);

                                    foreach ($categoryArray as $category){


                                        $newcat= Category::where('title',$category)->first();
                                        if($newcat==null){
                                            $newcat=new Category();
                                            $newcat->title=$category;
                                            $newcat->save();
                                        }
                                    }

                                    $getcategory = Category::whereIn('title', $categoryArray)->pluck('id')->toArray();


                                    $getcat1 = $get->categoriesget()->sync($getcategory);
                                }
                            }




                            foreach ($variants as $getvariant) {

                                if(isset($importData[8])) {
                                    $getvariant->sku = $importData[8];
                                }

                                if(isset($importData[11])) {
                                    $getvariant->price = $importData[11];
                                }

                                if(isset($importData[12])) {
                                    $getvariant->compare_price = $importData[12];
                                }

                                if(isset($importData[13])) {
                                    $getvariant->cost = $importData[13];
                                }

                                if(isset($importData[14])) {
                                    $getvariant->quantity = $importData[14];
                                }
                                if(isset($importData[17])) {
                                    $getvariant->option1 = $importData[17];
                                }

                                if(isset($importData[19])) {
                                    $getvariant->option2 = $importData[19];
                                }
                                if(isset($importData[21])) {
                                    $getvariant->option3 = $importData[21];
                                }

                                if(isset($importData[23])) {

                                    $getvariant->barcode = $importData[23];
                                }
                                $getvariant->update();


                            }

                            $get->update();



                        }

                        else {

                            if(isset($importData[2])) {
                                $get->title = $importData[2];
                            }
                            if(isset($importData[3])) {
                            $get->description=$importData[3];
                                }

                            if(isset($importData[4])) {
                                $get->type = $importData[4];
                            }
                            if(isset($importData[5])) {
                                $get->vendor = $importData[5];
                            }

                            if(isset($importData[6])) {
                                $tagArray = explode(",", $importData[6]);

//dd($tagArray);
                                foreach ($tagArray as $tags){


                                    $newtag= Tag::where('name',$tags)->first();
                                    if($newtag==null){
                                        $newtag=new Tag();
                                        $newtag->name=$tags;
                                        $newtag->save();
                                    }
                                }
                                $gettag=Tag::whereIn('name',$tagArray)->pluck('id')->toArray();


                                $gettag1=$get->tags()->sync($gettag);

                            }
                            if(isset($importData[7])) {
                                $get->weight = $importData[7];
                            }



                            if(isset($importData[9])) {
                                $get->processing_time = $importData[9];
                            }

                            if(isset($importData[10])) {
                                $get->slug = $importData[10];
                            }
                            if(isset($importData[16])) {
                                $get->attribute1 = $importData[16];
                            }
                            if(isset($importData[18])) {
                                $get->attribute2 = $importData[18];
                            }
                            if(isset($importData[20])) {
                                $get->attribute3 = $importData[20];
                            }
                            if(isset($importData[15])) {
                                $get->recommended_price = $importData[15];
                            }
                            if(isset($importData[8])) {
                                $get->sku = $importData[8];
                            }
                            if(isset($importData[11])) {
                                $get->price = $importData[11];
                            }
                            if(isset($importData[12])) {
                                $get->compare_price = $importData[12];
                            }
                            if(isset($importData[13])) {
                                $get->cost = $importData[13];
                            }
                            if(isset($importData[14])) {
                                $get->quantity = $importData[14];
                            }

                            if(isset($importData[22])) {
                                $categoryArray = explode(",", $importData[22]);

                                foreach ($categoryArray as $category){


                                    $newcat= Category::where('title',$category)->first();
                                    if($newcat==null){
                                        $newcat=new Category();
                                        $newcat->title=$category;
                                        $newcat->save();
                                    }
                                }

                                $getcategory=Category::whereIn('title',$categoryArray)->pluck('id')->toArray();


                                $getcat1=$get->categoriesget()->sync($getcategory);

                            }
                            if(isset($importData[23])) {
                                $get->barcode = $importData[23];
                            }
                            $get->update();
                        }
                    }

                    else{

//                        dd(1);

//                        dd($importData[2]);
//                            dump($importData[0],$key);
    //                         dd($key);
                            // Insert to MySQL database




                            if(isset($importData[2])) {
                                $title = $importData[2];
                            }
//                            dd($title);

                            if(isset($importData[3])) {
                                $description = $importData[3];
                            }

                            if(isset($importData[4])) {
                                $type = $importData[4];
                            }

                            if(isset($importData[5])) {
                                $vendor = $importData[5];
                            }

                        if(isset($importData[6])) {
                            $tagArray = explode(",", $importData[6]);


                            foreach ($tagArray as $tags){


                                $newtag= Tag::where('name',$tags)->first();
                                if($newtag==null){
                                    $newtag=new Tag();
                                    $newtag->name=$tags;
                                    $newtag->save();
                                }
                            }
                            $gettag=Tag::whereIn('name',$tagArray)->get();



                        }
                        if(isset($importData[7])) {
                            $weight = $importData[7];
                        }

                        if(isset($importData[8])) {
                            $sku = $importData[8];
                        }

                        if(isset($importData[9])) {
                            $processing_time = $importData[9];
                        }

                        if(isset($importData[10])) {
                            $slug = $importData[10];
                        }


                        if(isset($importData[11])) {
                            $price = $importData[11];
                        }


                        if(isset($importData[12])) {
                            $compare_price = $importData[12];
                        }


                        if(isset($importData[13])) {
                            $cost = $importData[13];
                        }


                        if(isset($importData[14])) {
                            $quantity = $importData[14];
                        }




                        if(isset($importData[15])) {
                            $recommended_price = $importData[15];
                        }


                        if(isset($importData[16])) {
                            $attribute1 = $importData[16];
                        }


                        if(isset($importData[17])) {
                            $option1 = $importData[17];
                        }

                        if(isset($importData[18])) {
                            $attribute2 = $importData[18];
                        }


                        if(isset($importData[19])) {
                            $option2 = $importData[19];
                        }

                        if(isset($importData[20])) {
                            $attribute3 = $importData[20];
                        }

                        if(isset($importData[21])) {
                            $option3 = $importData[21];
                        }


                        if(isset($importData[22])) {
                            $catArray = explode(",", $importData[22]);


                            foreach ($catArray as $category){


                                $newcat= Category::where('title',$category)->first();
                                if($newcat==null){
                                    $newcat=new Category();
                                    $newcat->title=$category;
                                    $newcat->save();
                                }
                            }
                            $getcate=Category::whereIn('title',$catArray)->get();



                        }



                        if(isset($importData[23])) {
                            $barcode = $importData[23];
                        }



                        if($title!='' || $title!=null) {
                            $product = new Product();
                            if ($importData[0] != null || $importData[0] != '') {
                                $product->id = $importData[0];
                            }
                            $product->title = $title;
                            $product->description = $description;
                            $product->type = $type;
                            $product->vendor = $vendor;
                            $product->price=$price;
                            $product->compare_price=$compare_price;
                            $product->cost=$cost;
                            $product->quantity=$quantity;
                            $product->weight = $weight;
                            $product->sku = $sku;
                            $product->processing_time = $processing_time;
                            $product->slug = $slug;
                            $product->recommended_price = $recommended_price;
                            $product->attribute1 = $attribute1;
                            $product->attribute2 = $attribute2;
                            $product->attribute3 = $attribute3;
                            $product->save();
                            $gettag1 = $product->tags()->sync($gettag);

                            $getcat1 = $product->categoriesget()->sync($getcate);
                        }
                                $p_product=Product::orderby('created_at','desc')->first();
                                $variantsadd=new ProductVariant();
                                $variantsadd->title=$title;
                                $variantsadd->price=$price;
                                $variantsadd->product_id=$p_product->id;
                                $variantsadd->compare_price=$compare_price;
                                $variantsadd->cost=$cost;
                                $variantsadd->quantity=$quantity;
                                $variantsadd->option1=$option1;
                                $variantsadd->option2=$option2;
                                $variantsadd->option3=$option3;
                                $variantsadd->barcode=$barcode;
                                $variantsadd->sku=$sku;

                                  $variantsadd->save();

                            array_push($array_id,$product->id);


                    }


                }

                ProductVariant::whereNotIn('product_id',$array_id)->delete();
            Product::whereNotIn('id',$array_id)->delete();


               $id = Auth::id();
               $csv=new Allproductcsv;
               $csv->user_id=$id;
               $csv->filename=$filename;
               $csv->save();



                return redirect()->back()->with('success', 'Products Updated Successfully');

            }else{
                Session::flash('message','File too large. File must be less than 2MB.');

            }

        }else{


            Session::flash('message','Invalid File Extension.');

        }
        return back();

    }




    // public function importallproducts(Request $request){

    //     if($request->has('file'))
    //     {
    //         $csv = file($request->file);

    //         //chunking file
    //         $chunks = array_chunk($csv,1000);
    //         $path = resource_path('temp');

    //         //convert chunk to new csv file
    //         foreach ($chunks as $key => $chunk) {
    //             $name = "/tmp{$key}.csv";
    //             file_put_contents($path.$name,$chunk);
    //         }

    //         //getting all the file inside the directories
    //         $files = glob("$path/*.csv");

    //         $header = [];

    //         foreach ($files as $key => $file) {

    //             $data = array_map('str_getcsv', file($file));

    //             if($key == 0){
    //                 $header = $data[0];
    //                 unset($data[0]);
    //             }

    //             foreach ($data as $sale) {
    //                 $sellData = array_combine($header,$sale);



    //                 $get=Product::where('id',$sale[0])->first();


    //                 if(!empty($get)){


    //                     $variant=ProductVariant::where('product_id',$sale[0])->get();

    //                     if(count($variant)>0){


    //                         if(isset($sale[2])) {
    //                             $get->title = $sale[2];
    //                         }

    //                         if(isset($sale[3])) {
    //                             $get->description = $sale[3];
    //                         }
    //                         if(isset($sale[4])) {
    //                             $get->type = $sale[4];
    //                         }

    //                         if(isset($sale[5])) {
    //                             $get->vendor = $sale[5];
    //                         }


    //                         if(isset($sale[6])) {
    //                             $tagArray = explode(",", $sale[6]);

    //                          $gettag=Tag::whereIn('name',$tagArray)->pluck('id')->toArray();


    //                             $gettag1=$get->tags()->sync($gettag);

    //                         }

    //                         if(isset($sale[7])) {
    //                             $get->weight = $sale[7];
    //                         }

    //                         if(isset($sale[9])) {
    //                             $get->processing_time = $sale[9];

    //                         }
    //                         if(isset($sale[10])) {
    //                             $get->slug = $sale[10];
    //                         }

    //                         if(isset($sale[16])) {
    //                             $get->attribute1 = $sale[16];

    //                         }
    //                      if(isset($sale[18])) {
    //                          $get->attribute2 = $sale[18];
    //                      }

    //                         if(isset($sale[20])) {
    //                             $get->attribute3 = $sale[20];
    //                         }

    //                         if(isset($sale[15])) {
    //                             $get->recommended_price = $sale[15];
    //                         }

    //                         if(isset($sale[1])) {
    //                             $variants = ProductVariant::where('id', $sale[1])->get();

    //                         }


    //                         if(isset($sale[22])) {
    //                             $categoryArray = explode(",", $sale[22]);

    //                             $getcategory=Category::whereIn('title',$categoryArray)->pluck('id')->toArray();


    //                             $getcat1=$get->categoriesget()->sync($getcategory);

    //                         }


    //                         // if(isset($importData[24])) {
    //                         //     $ImageArray = explode(",", $importData[24]);





    //                         //     // foreach ($get->has_images as $imagess){



    //                         //     //     foreach($ImageArray as $getimmg){


    //                         //     //         $imagess->image=$getimmg;
    //                         //     //         $imagess->update();
    //                         //     //     }


    //                         //     // }


    //                         //     // foreach ($get->has_images as $imagess){


    //                         //     //     if( !in_array( $imagess->image ,$ImageArray ) ){
    //                         //     //                 $imagess->delete();
    //                         //     //     }

    //                         //     // }




    //                         // }

    //                         foreach ($variants as $getvariant) {

    //                             if(isset($sale[8])) {
    //                                 $getvariant->sku = $sale[8];
    //                             }

    //                             if(isset($sale[11])) {
    //                                 $getvariant->price = $sale[11];
    //                             }

    //                             if(isset($sale[12])) {
    //                                 $getvariant->compare_price = $sale[12];
    //                             }

    //                             if(isset($sale[13])) {
    //                                 $getvariant->cost = $sale[13];
    //                             }

    //                             if(isset($sale[14])) {
    //                                 $getvariant->quantity = $sale[14];
    //                             }
    //                             if(isset($sale[17])) {
    //                                 $getvariant->option1 = $sale[17];
    //                             }

    //                             if(isset($sale[19])) {
    //                                 $getvariant->option2 = $sale[19];
    //                             }
    //                             if(isset($sale[21])) {
    //                                 $getvariant->option3 = $sale[21];
    //                             }

    //                             if(isset($sale[23])) {

    //                                 $getvariant->barcode = $sale[23];
    //                             }
    //                             $getvariant->update();


    //                         }

    //                         $get->update();



    //                     }

    //                     else {

    //                         if(isset($sale[2])) {
    //                             $get->title = $sale[2];
    //                         }
    //                         if(isset($sale[3])) {
    //                         $get->description=$sale[3];
    //                             }

    //                         if(isset($sale[4])) {
    //                             $get->type = $sale[4];
    //                         }
    //                         if(isset($sale[5])) {
    //                             $get->vendor = $sale[5];
    //                         }

    //                         if(isset($sale[6])) {
    //                             $tagArray = explode(",", $sale[6]);

    //                             $gettag=Tag::whereIn('name',$tagArray)->pluck('id')->toArray();


    //                             $gettag1=$get->tags()->sync($gettag);

    //                         }
    //                         if(isset($sale[7])) {
    //                             $get->weight = $sale[7];
    //                         }
    //                         if(isset($sale[9])) {
    //                             $get->processing_time = $sale[9];
    //                         }

    //                         if(isset($sale[10])) {
    //                             $get->slug = $sale[10];
    //                         }
    //                         if(isset($sale[16])) {
    //                             $get->attribute1 = $sale[16];
    //                         }
    //                         if(isset($sale[18])) {
    //                             $get->attribute2 = $sale[18];
    //                         }
    //                         if(isset($sale[20])) {
    //                             $get->attribute3 = $sale[20];
    //                         }
    //                         if(isset($sale[15])) {
    //                             $get->recommended_price = $sale[15];
    //                         }
    //                         if(isset($sale[8])) {
    //                             $get->sku = $sale[8];
    //                         }
    //                         if(isset($sale[11])) {
    //                             $get->price = $sale[11];
    //                         }
    //                         if(isset($sale[12])) {
    //                             $get->compare_price = $sale[12];
    //                         }
    //                         if(isset($sale[13])) {
    //                             $get->cost = $sale[13];
    //                         }
    //                         if(isset($sale[14])) {
    //                             $get->quantity = $sale[14];
    //                         }

    //                         if(isset($sale[22])) {
    //                             $categoryArray = explode(",", $sale[22]);

    //                             $getcategory=Category::whereIn('title',$categoryArray)->pluck('id')->toArray();


    //                             $getcat1=$get->categoriesget()->sync($getcategory);

    //                         }
    //                         if(isset($sale[23])) {
    //                             $get->barcode = $sale[23];
    //                         }
    //                         $get->update();
    //                     }
    //                 }

    //             }

    //             unlink($file);
    //         }

    //         return "stored";

    //     }
    //     return "please upload csv file";





    // }
}
