<?php

namespace App\Http\Controllers;

use App\AdditionalTab;
use App\Category;
use App\Console\Commands\AppChangeQuantitySku;
use App\Exports\ProductsExport;
use App\Exports\ProductVariantExport;
use App\Exports\RetailerOrderExport;
use App\Image;
use App\Product;
use App\ProductVariant;
use App\RetailerImage;
use App\RetailerOrder;
use App\RetailerProduct;
use App\RetailerProductVariant;
use App\TieredPrice;
use App\User;
use App\WarnedPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use OhMyBrew\ShopifyApp\Models\Shop;


class ProductController extends Controller
{
    private $helper;
    private $notify;
    private $log;

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->helper = new HelperController();
        $this->notify = new NotificationController();
        $this->log = new ActivityLogController();
    }

    public function index()
    {
        $categories = Category::latest()->get();
        $platforms = WarnedPlatform::all();
        $shops = Shop::whereNotIn('shopify_domain',['wefullfill.myshopify.com'])->get();
        return view('products.create')->with([
            'categories' => $categories,
            'platforms' => $platforms,
            'shops' => $shops
        ]);
    }

    public function all(Request $request)
    {
        $productQ = Product::query();
        if($request->has('search')){
            $productQ->where('title','LIKE','%'.$request->input('search').'%')->orWhereHas('hasVariants', function($q) use ($request) {
                $q->where('sku', 'LIKE', '%' . $request->input('search') . '%');
            });
        }
        return view('products.all')->with([
            'products' => $productQ->with(['has_images', 'hasVariants'])->orderBy('created_at','DESC')->paginate(20),
            'search' =>$request->input('search')
        ]);
    }

    public function view($id)
    {
        $product = Product::with(['has_images', 'hasVariants','has_platforms','has_categories','has_subcategories'])->find($id);
        return view('products.product')->with([
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
        $product = Product::with(['has_images', 'hasVariants','has_platforms','has_categories','has_subcategories'])->find($id);
        $platforms = WarnedPlatform::all();
        $shops = Shop::whereNotIn('shopify_domain',['wefullfill.myshopify.com'])->get();

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
        ]);
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

    public function update_original(Request $request, $id)
    {
        $product = Product::find($id);
        $shop =$this->helper->getShop();
        if ($product != null) {
            if ($request->has('type')) {

                /*Variants Option Delete from Shopify and Database*/
                if ($request->input('type') == 'variant-option-delete') {
                    $deleted_variants = null;
                    if ($request->has('delete_option1')) {
                        if ($request->has('delete_option2')) {
                            if ($request->has('delete_option3')) {
                                $deleted_variants = $this->delete_three_options_variants($request, $product);
                            } else {
                                $deleted_variants = $this->delete_two_options_variants($request, $product);
                            }
                        } else {
                            $deleted_variants = $product->hasVariants()->whereIn('option1', $request->input('delete_option1'))->get();
                            $this->delete_variants($deleted_variants);
                        }

                    } else if ($request->has('delete_option2')) {
                        if ($request->has('delete_option1')) {
                            if ($request->has('delete_option3')) {
                                $deleted_variants = $this->delete_three_options_variants($request, $product);

                            } else {
                                $deleted_variants = $this->delete_two_options_variants($request, $product);
                            }
                        } else {
                            $deleted_variants = $product->hasVariants()->whereIn('option2', $request->input('delete_option2'))->get();
                            $this->delete_variants($deleted_variants);
                        }
                    } else {
                        if ($request->has('delete_option2')) {
                            if ($request->has('delete_option1')) {
                                $deleted_variants = $this->delete_three_options_variants($request, $product);
                            } else {
                                $deleted_variants = $this->delete_two_options_variants($request, $product);
                            }
                        } else {
                            $deleted_variants = $product->hasVariants()->whereIn('option3', $request->input('delete_option3'))->get();
                            $this->delete_variants($deleted_variants);
                        }
                    }
                    if (count($product->hasVariants) == 0) {
                        $product->variants = 0;
                        $product->save();
                    }
                    /*Deleting Variants from shopify*/
                    foreach ($deleted_variants as $deleted){
                        $shop->api()->rest('DELETE', '/admin/api/2019-10/products/' .$product->shopify_id. '/variants/' .$deleted->shopify_id. '.json');
                    }

                    $this->log->store(0, 'Product', $product->id, $product->title,'Variant Option Deleted');

                    return redirect()->back()->with('success','Selected Options and Related Variants Deleted Successfully');
                }
                /*New Variants Option Add from Shopify and Database*/
                if ($request->input('type') == 'existing-product-new-variants') {
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

                    $this->log->store(0, 'Product', $product->id, $product->title,'New Variants Option Added');

                    return redirect()->route('product.edit', $product->id);
                }
                /*New Variants Option Update from Shopify and Database*/
                if ($request->input('type') == 'existing-product-update-variants') {

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

                    foreach ($variants_array as $index => $v){
                        $variant = ProductVariant::where('title', $v['title'])->first();
                        $variant->shopify_id = $shopifyVariants[$index]->id;
                        $variant->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                        $variant->save();
                    }

                    $this->log->store(0, 'Product', $product->id, $product->title,'New Variants Option Updated');

                    return redirect()->route('product.edit', $product->id);
                }
                /*old Option Update Shopify and Database*/
                if ($request->input('type') == 'old-option-update') {

                    $product->variants = 1;
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

                    $this->log->store(0, 'Product', $product->id, $product->title,'Old Option Updated');

                    return redirect()->route('product.edit', $product->id);

                }

                /*new Option Add Shopify and Database*/
                if ($request->input('type') == 'new-option-add') {
                    $variants_array = [];
                    foreach ($product->hasVariants as $v) {
                        if ($request->input('option') == 'option2') {
                            $v->option2 = $request->input('value');
                            $v->title = $v->title . $request->input('value') . '/';
                            array_push($variants_array,[
                                'id' => $v->shopify_id,
                                'option2' =>  $request->input('value')
                            ]);
                        }
                        if ($request->input('option') == 'option3') {
                            $v->option3 = $request->input('value');
                            $v->title = $v->title . $request->input('value');
                            array_push($variants_array,[
                                'id' => $v->shopify_id,
                                'option3' =>  $request->input('value')
                            ]);
                        }
                        $v->save();
                    }
                    $productdata = [
                        "product" => [
                            "options" => $this->options_update_template_array($product),
                            "variants" => $variants_array,
                        ]
                    ];

                    $this->log->store(0, 'Product', $product->id, $product->title,'New Option Added');

                    $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'.json',$productdata);
                    return redirect()->back();
                }
                /*Single Variant Update Shopify and Database*/
                if ($request->input('type') == 'single-variant-update') {
                    $variant = ProductVariant::find($request->variant_id);
                    $variant->title = $request->input('option1') . '/' . $request->input('option2') . '/' . $request->input('option3');
                    $variant->option1 = $request->input('option1');
                    $variant->option2 = $request->input('option2');
                    $variant->option3 = $request->input('option3');
                    $variant->price = $request->input('price');
                    $variant->compare_price = $request->input('compare_price');
                    $variant->quantity = $request->input('quantity');
                    $variant->sku = $request->input('sku');
                    $variant->barcode = $request->input('barcode');

                    if($request->input('cost') == null) {
                        $variant->cost = null;
                    }
                    else {
                        $res = str_ireplace( array( '$', '"',
                            ',' , ';', '<', '>' ), ' ', $request->input('cost'));
                        $variant->cost = trim($res);
                    }

                    $variant->product_id = $id;
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
                /*Product Basic Update Shopify and Database*/
                if ($request->input('type') == 'basic-info') {
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
                /*Pricing Update*/
                if ($request->input('type') == 'pricing') {
                    $product->price = $request->price;
                    $product->compare_price = $request->compare_price;
                    $product->cost = $request->cost;
                    $product->quantity = $request->quantity;
                    $product->weight = $request->weight;
                    $product->sku = $request->sku;
                    $product->barcode = $request->barcode;
                    $product->save();

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

                if ($request->input('type') == 'fulfilled') {
                    $product->fulfilled_by = $request->input('fulfilled-by');
                    $product->sortBy = $request->input('sortBy');
                    $product->save();
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Basic Information Updated');

                }



                if ($request->input('type') == 'category') {
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

                if ($request->input('type') == 'organization') {
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

                if ($request->input('type') == 'more-details') {
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

                    $this->product_status_change($request, $product, $shop);


                }

                if($request->input('type') == 'marketing_video_update'){
                    $product->marketing_video = $request->input('marketing_video');
                    $product->save();
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Marketing Video Updated');

                }

                if($request->input('type') == 'status_update'){
                    $this->product_status_change($request, $product, $shop);
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Status Updated');

                }

                if ($request->input('type') == 'variant-image-update') {
//                    dd($request);
                    $variant = ProductVariant::find($request->variant_id);
                    if ($request->hasFile('varaint_src')) {
                        $image = $request->file('varaint_src');
                        $destinationPath = 'images/variants/';
                        $filename = now()->format('YmdHi') . str_replace([' ','(',')'], '-', $image->getClientOriginalName());
                        $image->move($destinationPath, $filename);
                        $image = new Image();
                        $image->isV = 1;
                        $image->product_id = $product->id;
                        $image->image = $filename;
                        $image->position = count($product->has_images)+1;
                        $image->save();
                        $variant->image = $image->id;
                        $variant->save();

                        $imageData = [
                            'image' => [
                                'src' => asset('images/variants') . '/' . $image->image,
                                'variant_ids' => [$variant->shopify_id]
                            ]
                        ];
                        $imageResponse = $shop->api()->rest('POST', '/admin/api/2019-10/products/' . $product->shopify_id . '/images.json', $imageData);
                        if($imageResponse->errors){
                            return redirect()->back()->with('error','Product not found on your store');
                        }
                        else{
                            $image->shopify_id = $imageResponse->body->image->id;
                            $image->save();
                            $this->log->store(0, 'Product', $product->id, $product->title,'Product Varinat Image Updated');

                            return redirect()->back();
                        }


                    }

                }

                if ($request->input('type') == 'existing-product-image-delete') {
                    $image =  Image::find($request->input('file'));
                    $shop->api()->rest('DELETE', '/admin/api/2019-10/products/' . $product->shopify_id . '/images/'.$image->shopify_id.'.json');
                    $image->delete();
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Image Deleted');

                    return response()->json([
                        'success' => 'ok'
                    ]);
                }

                if ($request->input('type') == 'existing-product-image-add') {
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
                            $imageData = [
                                'image' => [
                                    'src' =>  asset('images') . '/' . $image->image,
                                ]
                            ];
                            $imageResponse = $shop->api()->rest('POST', '/admin/api/2019-10/products/' . $product->shopify_id . '/images.json', $imageData);
                            $image->shopify_id = $imageResponse->body->image->id;
                            $image->save();
                        }
                    }
                    $product->save();
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Image Added');

                }

                if ($request->input('type') == 'add-additional-tab'){
//                    dd($request);
                    $additional_tab = new AdditionalTab();
                    $additional_tab->title = $request->input('title');
                    $additional_tab->description = $request->input('description');
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
                    $additional_tab->shopify_id = $resp->body->metafield->id;
                    $additional_tab->save();
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Tab Added');

                    return redirect()->back()->with('success','Additional Tabs Added Successfully');
                }

                if ($request->input('type') == 'edit-additional-tab'){
//                    dd($request);
                    $additional_tab = AdditionalTab::find($request->input('tab_id'));
                    $additional_tab->title = $request->input('title');
                    $additional_tab->description = $request->input('description');
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

                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Tab Updated');

                    $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'/metafields/'.$additional_tab->shopify_id.'.json',$productdata);
                    return redirect()->back()->with('success','Additional Tabs Added Successfully');
                }

                if ($request->input('type') == 'shop-preferences'){
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

            }
        }

        return redirect()->back()->with('success', 'Product Updated Successfully');
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $shop =$this->helper->getShop();
        if ($product != null) {
            foreach($request->type as $type) {
                $type = $request->type[1];
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

                else if ($type == 'existing-product-image-add') {
                    if ($request->hasFile('images')) {
                        foreach ($request->file('images') as $index => $image) {
                            dd($image);
                            $destinationPath = 'images/';
                            $filename = now()->format('YmdHi') . str_replace([' ','(',')'], '-', $image->getClientOriginalName());
                            $image->move($destinationPath, $filename);
                            $image = new Image();
                            $image->isV = 0;
                            $image->product_id = $product->id;
                            $image->image = $filename;
                            $image->position = count($product->has_images) + $index+1;
                            $image->save();
                            $imageData = [
                                'image' => [
                                    'src' =>  asset('images') . '/' . $image->image,
                                ]
                            ];
                            $imageResponse = $shop->api()->rest('POST', '/admin/api/2019-10/products/' . $product->shopify_id . '/images.json', $imageData);
                            $image->shopify_id = $imageResponse->body->image->id;
                            $image->save();
                        }
                    }
                    $product->save();
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Image Added');

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

//                        if($request->input('single-var-cost-'.$id) == null) {
//                            $variant->cost = null;
//                        }
//                        else {
//                            $res = str_ireplace( array( '$', '"',
//                                ',' , ';', '<', '>' ), ' ', $request->input('single-var-cost-').$id);
//                            $variant->cost = trim($res);
//                        }

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

                    $this->product_status_change($request, $product, $shop);
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

                else if ($type == 'existing-product-image-delete') {
                    $image =  Image::find($request->input('file'));
                    $shop->api()->rest('DELETE', '/admin/api/2019-10/products/' . $product->shopify_id . '/images/'.$image->shopify_id.'.json');
                    $image->delete();
                    $this->log->store(0, 'Product', $product->id, $product->title,'Product Image Deleted');
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
            }
        }

        return redirect()->back()->with('success', 'Product Updated Successfully');
    }


    public function updateExistingProductNewVariants(Request $request, $id) {
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

                    $this->log->store(0, 'Product', $product->id, $product->title,'New Variants Option Added');
                    return redirect()->route('product.edit', $product->id)->with('success', 'Product Variants Updated Successfully');

                }
            return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');

        }
        return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');

    }


    public function updateExistingProductOldVariants(Request $request, $id) {
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

                    foreach ($variants_array as $index => $v){
                        $variant = ProductVariant::where('title', $v['title'])->first();
                        $variant->shopify_id = $shopifyVariants[$index]->id;
                        $variant->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                        $variant->save();
                    }

                    $this->log->store(0, 'Product', $product->id, $product->title,'New Variants Option Updated');
                    return redirect()->route('product.edit', $product->id)->with('success', 'Product Variants Updated Successfully');

            }
            return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');
        }
        return redirect()->route('product.edit', $product->id)->with('error', 'Something went wrong');

    }

    public function save(Request $request)
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
//            $images = [];
            foreach ($request->file('images') as $image) {
                $destinationPath = 'images/';
                $filename = now()->format('YmdHi') . str_replace([' ','(',')'], '-', $image->getClientOriginalName());
                $image->move($destinationPath, $filename);
//                array_push($images, $filename);
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
                $res = str_ireplace( array( '$', '"',
                    ',' , ';', '<', '>' ), ' ', $data->variant_cost[$i]);
                $variants->cost = trim($res);
            }


            $variants->sku = $data->variant_sku[$i];
            $variants->barcode = $data->variant_barcode[$i];
            $variants->product_id = $id;
            $variants->save();
        }
    }

    public function ProductVariantsUpdate($data, $id, $product)
    {

        foreach ($product->hasVariants as $v){
            $v->delete();
        }
        $variants_array = [];

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
                $res = str_ireplace( array( '$', '"',
                    ',' , ';', '<', '>' ), ' ', $data->variant_cost[$i]);
                $variants->cost = trim($res);
            }
            $variants->sku = $data->variant_sku[$i];
            $variants->barcode = $data->variant_barcode[$i];
            $variants->product_id = $id;
            $variants->save();


            array_push($variants_array, [
                'title' => $variants->title,
                'sku' => $variants->sku,
                'option1' => $variants->option1,
                'option2' => $variants->option2,
                'option3' => $variants->option3,
                'inventory_quantity' => $variants->quantity,
                "fulfillment_service" => "wefullfill",
                'inventory_management' => 'wefullfill',
                'grams' => $product->weight * 1000,
                'weight' => $product->weight,
                'weight_unit' => 'kg',
                'barcode' => $variants->barcode,
                'price' => $variants->price,
                'cost' => $variants->cost,
            ]);


        }

        return $variants_array;

    }


    public function delete($id)
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
                    "published"=>  $published
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
    public function product_status_change(Request $request, $product, $shop)
    {
        $product->status = $request->input('status');
        $product->save();
        if ($product->status == 1) {
            $published = true;
        } else {
            $published = false;
        }
        $productData = [
            'product' => [
                'published' => $published
            ]
        ];
        $resp = $shop->api()->rest('PUT', '/admin/api/2019-10/products/' . $product->shopify_id . '.json', $productData);
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
            $shop = $this->helper->getShop();
            $variant = ProductVariant::find($id);
            if($variant->linked_product != null) {
                if ($variant->linked_product->shopify_id != null) {
                    $image = Image::find($image_id);
                    return $this->shopify_image_selection($image_id, $image, $shop, $variant);
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

    public function update_image_position(Request $request){
        $positions = $request->input('positions');
        $product = $request->input('product');
        $images_array = [];
        $shop = $this->helper->getShop();
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
            $imagesResponse = $shop->api()->rest('PUT', '/admin/api/2019-10/products/' . $related_product->shopify_id .'.json', $data);
            if(!$imagesResponse->errors){
                foreach ($positions as $index => $position){
                    $image = Image::where('product_id',$product)
                        ->where('id',$position)->first();
                    $image->position = $index + 1;
                    $image->save();
                }
                return response()->json([
                    'message' => 'success',
                ]);
            }else{
                return response()->json([
                    'message' => 'error'
                ]);
            }

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


}
