<?php

namespace App\Http\Controllers;

use App\AdditionalTab;
use App\Category;
use App\Image;
use App\Product;
use App\ProductVariant;
use App\WarnedPlatform;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $helper;

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function index()
    {
        $categories = Category::latest()->get();
        $platforms = WarnedPlatform::all();
        return view('products.create')->with([
            'categories' => $categories,
            'platforms' => $platforms
        ]);
    }

    public function all()
    {
        $products = Product::all();
        return view('products.all')->with([
            'products' => $products
        ]);
    }

    public function view($id)
    {
        $product = Product::find($id);
        return view('products.product')->with([
            'product' => $product
        ]);
    }

    public function Edit($id)
    {

        $categories = Category::latest()->get();
        $product = Product::find($id);
        $platforms = WarnedPlatform::all();

        return view('products.edit')->with([
            'categories' => $categories,
            'platforms' => $platforms,
            'product' => $product
        ]);
    }

    public function update(Request $request, $id)
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
                        $v->save();
                    }
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
                    $variant->cost = $request->input('cost');
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


                }

                if ($request->input('type') == 'fulfilled') {
                    $product->fulfilled_by = $request->input('fulfilled-by');
                    $product->save();

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
                }

                if ($request->input('type') == 'organization') {
                    $product->type = $request->type;
                    $product->vendor = $request->vendor;
                    $product->tags = $request->tags;
                    $product->save();

                    $productdata = [
                        "product" => [
                            "vendor" => $request->vendor,
                            "product_type" => $request->type,
                        ]
                    ];
                    $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'.json',$productdata);

                }

                if ($request->input('type') == 'more-details') {
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
                        $resp =  $shop->api()->rest('POST', '/admin/api/2019-10/products/'.$product->shopify_id.'/metafields.json',$productdata);
                    }

                     $this->product_status_change($request, $product, $shop);

                }
                if($request->input('type') == 'status_update'){
                    $this->product_status_change($request, $product, $shop);
                }

                if ($request->input('type') == 'variant-image-update') {
//                    dd($request);
                    $variant = ProductVariant::find($request->variant_id);
                    if ($request->hasFile('varaint_src')) {
                        $image = $request->file('varaint_src');
                        $destinationPath = 'images/variants/';
                        $filename = now()->format('YmdHi') . str_replace(' ', '-', $image->getClientOriginalName());
                        $image->move($destinationPath, $filename);
                        $image = new Image();
                        $image->isV = 1;
                        $image->product_id = $product->id;
                        $image->image = $filename;
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
                        $image->shopify_id = $imageResponse->body->image->id;
                        $image->save();
                        return redirect()->back();
                    }

                }

                if ($request->input('type') == 'existing-product-image-delete') {
                    $image =  Image::find($request->input('file'));
                    $shop->api()->rest('DELETE', '/admin/api/2019-10/products/' . $product->shopify_id . '/images/'.$image->shopify_id.'.json');
                    $image->delete();

                    return response()->json([
                        'success' => 'ok'
                    ]);
                }

                if ($request->input('type') == 'existing-product-image-add') {
                    if ($request->hasFile('images')) {
                        foreach ($request->file('images') as $image) {
                            $destinationPath = 'images/';
                            $filename = now()->format('YmdHi') . str_replace(' ', '-', $image->getClientOriginalName());
                            $image->move($destinationPath, $filename);
                            $image = new Image();
                            $image->isV = 0;
                            $image->product_id = $product->id;
                            $image->image = $filename;
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
                    $resp =  $shop->api()->rest('PUT', '/admin/api/2019-10/products/'.$product->shopify_id.'/metafields/'.$additional_tab->shopify_id.'.json',$productdata);
                    return redirect()->back()->with('success','Additional Tabs Added Successfully');
                }
            }
        }
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
                $filename = now()->format('YmdHi') . str_replace(' ', '-', $image->getClientOriginalName());
                $image->move($destinationPath, $filename);
//                array_push($images, $filename);
                $image = new Image();
                $image->isV = 0;
                $image->product_id = $product->id;
                $image->image = $filename;
                $image->save();
            }

        }
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
            $variants->cost = $data->variant_cost[$i];
            $variants->sku = $data->variant_sku[$i];
            $variants->barcode = $data->variant_barcode[$i];
            $variants->product_id = $id;
            $variants->save();
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);
        $product->delete();
        $variants = ProductVariant::where('product_id', $id)->get();
        foreach ($variants as $variant) {
            $variant->delete();
        }
        return redirect()->back()->with('error', 'Product Deleted with Variants!');
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

            $response = $shop->api()->rest('POST', '/admin/api/2019-10/products.json', $productdata);
//            dd($response);
            $product_shopify_id =  $response->body->product->id;
            $product->shopify_id = $product_shopify_id;
            $product->save();

            $shopifyImages = $response->body->product->images;
            $shopifyVariants = $response->body->product->variants;
            foreach ($product->hasVariants as $index => $v){
                $v->shopify_id = $shopifyVariants[$index]->id;
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
            foreach ($product->has_images as $index => $image){
                $image->shopify_id = $shopifyImages[$index]->id;
                $image->save();
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
}
