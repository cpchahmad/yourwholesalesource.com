<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $categories = Category::latest()->get();
        return view('products.create')->with([
            'categories' => $categories
        ]);
    }
    public function all(){
        $products = Product::all();
        return view('products.all')->with([
            'products' => $products
        ]);
    }
    public function view($id){
        $product = Product::find($id);
        return view('products.product')->with([
            'product' => $product
        ]);
    }
    public function Edit($id){

        $categories = Category::latest()->get();
        $product = Product::find($id);

        return view('products.edit')->with([
            'categories' => $categories,
            'product' => $product
        ]);
    }
    public function update(Request $request, $id){
        $product = Product::find($id);
        if($product != null){
            if($request->has('type')){
                if($request->input('type') == 'variant-option-delete'){
                    if($request->has('delete_option1')){
                        if($request->has('delete_option2')){
                            if($request->has('delete_option3')){
                                $product->hasVariants()->whereIn('option1',$request->input('delete_option1'))
                                    ->whereIn('option2',$request->input('delete_option2'))
                                    ->whereIn('option3',$request->input('delete_option3'))->delete();
                            }
                            else{
                                $product->hasVariants()->whereIn('option1',$request->input('delete_option1'))
                                    ->whereIn('option2',$request->input('delete_option2'))->delete();
                            }
                        }
                        else{
                            $product->hasVariants()->whereIn('option1',$request->input('delete_option1'))->delete();
                        }

                    }
                    else if($request->has('delete_option2')){
                        if($request->has('delete_option1')){
                            if($request->has('delete_option3')){
                                $product->hasVariants()->whereIn('option1',$request->input('delete_option1'))
                                    ->whereIn('option2',$request->input('delete_option2'))
                                    ->whereIn('option3',$request->input('delete_option3'))->delete();
                            }
                            else{
                                $product->hasVariants()->whereIn('option1',$request->input('delete_option1'))
                                    ->whereIn('option2',$request->input('delete_option2'))->delete();
                            }
                        }
                        else{
                            $product->hasVariants()->whereIn('option2',$request->input('delete_option2'))->delete();
                        }
                    }
                    else{
                        if($request->has('delete_option2')){
                            if($request->has('delete_option1')){
                                $product->hasVariants()->whereIn('option1',$request->input('delete_option1'))
                                    ->whereIn('option2',$request->input('delete_option2'))
                                    ->whereIn('option3',$request->input('delete_option3'))->delete();
                            }
                            else{
                                $product->hasVariants()->whereIn('option1',$request->input('delete_option2'))
                                    ->whereIn('option2',$request->input('delete_option3'))->delete();
                            }
                        }
                        else{
                            $product->hasVariants()->whereIn('option3',$request->input('delete_option3'))->delete();
                        }
                    }
                    if(count($product->hasVariants) == 0){
                        $product->variants = 0;
                        $product->save();
                    }
                    return redirect()->back();
                }
                if($request->input('type') == 'existing-product-new-variants'){
                    if ($request->variants){
                        $product->variants = $request->variants;
                    }
                    $product->save();
                    $this->ProductVariants($request, $product->id);
                    return redirect()->route('product.edit',$product->id);
                }
                if($request->input('type') == 'new-option-add'){
                    foreach ($product->hasVariants as $v){
                        if($request->input('option') == 'option2'){
                            $v->option2 = $request->input('value');
                            $v->title = $v->title.$request->input('value').'/';
                        }
                        if($request->input('option') == 'option3'){
                            $v->option3 = $request->input('value');
                            $v->title = $v->title.$request->input('value');
                        }
                        $v->save();
                    }
                    return redirect()->back();
                }
                if($request->input('type') == 'single-variant-update'){
//                    dd($request->all());
                    $variant =  ProductVariant::find($request->variant_id);
                    $variant->title =$request->input('option1').'/'.$request->input('option2').'/'.$request->input('option3');
                    $variant->option1 = $request->input('option1');
                    $variant->option2 = $request->input('option2');
                    $variant->option3 = $request->input('option3');
                    $variant->price = $request->input('price');
                    $variant->compare_price = $request->input('compare_price');
                    $variant->quantity = $request->input('quantity');
                    $variant->sku = $request->input('sku');
                    $variant->barcode =  $request->input('barcode');
                    $variant->cost =  $request->input('cost');
                    $variant->product_id = $id;
                    $variant->save();

                }
                if($request->input('type') == 'basic-info'){
                    $product->title = $request->title;
                    $product->description = $request->description;
                    $product->save();
                    return redirect()->back();
                }
                if($request->input('type') == 'pricing'){
                    $product->price = $request->price;
                    $product->compare_price = $request->compare_price;
                    $product->cost = $request->cost;
                    $product->quantity = $request->quantity;
                    $product->weight = $request->weight;
                    $product->sku = $request->sku;
                    $product->barcode = $request->barcode;
                    $product->save();

                }
                if($request->input('type') == 'fulfilled') {
                    $product->fulfilled_by = $request->input('fulfilled-by');
                    $product->save();

                }
                if($request->input('type') == 'category') {
                    if ($request->category){
                        $product->has_categories()->sync($request->category);
                    }
                    if ($request->sub_cat){
                        $product->has_subcategories()->sync($request->sub_cat);
                    }
                    $product->save();
                }
                if($request->input('type') == 'organization') {
                    $product->type = $request->type;
                    $product->vendor = $request->vendor;
                    $product->tags = $request->tags;
                    $product->save();

                }
                if($request->input('type') == 'more-details') {
                    $product->ship_info = $request->ship_info;
                    $product->ship_processing_time = $request->ship_processing_time;
                    $product->ship_price = $request->ship_price;
                    $product->warned_platform = $request->warned_platform;
                    $product->save();

                }
                if($request->input('type') == 'variant-image-update'){
//                    dd($request);
                    $variant =  ProductVariant::find($request->variant_id);
                    if($request->hasFile('varaint_src'))
                    {
                        $image = $request->file('varaint_src');
                        $destinationPath = 'images/variants/';
                        $filename = now()->format('YmdHi').str_replace(' ','-',$image->getClientOriginalName());
                        $image->move($destinationPath, $filename);
                        $variant->image = $filename ;
                        $variant->save();
                        return redirect()->back();
                    }

                }
                if($request->input('type') == 'existing-product-image-delete'){
                    $images = json_decode($product->images);
                    $images = array_diff( $images, [$request->input('file')] );
                    $new_array = [];
                    foreach ($images as $image){
                        array_push($new_array,$image);
                    }
                    $product->images = json_encode($new_array);
                    $product->save();
                    return response()->json([
                        'success' => 'ok'
                    ]);
                }
                if($request->input('type') == 'existing-product-image-add'){
                    $images = json_decode($product->images);
                    if($request->hasFile('images'))
                    {
                        foreach($request->file('images') as $image)
                        {
                            $destinationPath = 'images/';
                            $filename = now()->format('YmdHi').str_replace(' ','-',$image->getClientOriginalName());
                            $image->move($destinationPath, $filename);
                            array_push($images, $filename);
                        }
                        $product->images = json_encode($images);
                    }
                    $product->save();
                }
            }
        }
//        else{
//            return redirect('/products');
//        }
//        return redirect()->route('product.all')->with('success','Item Updated successfully!');
    }
    public function save(Request $request){
//        dd($request);
        if (Product::where('title', $request->title)->exists()) {
            $product = Product::where('title', $request->title)->first();
        } else {
            $product = new Product();
        }
        if($request->hasFile('images'))
        {
            $images = [];
            foreach($request->file('images') as $image)
            {
                $destinationPath = 'images/';
                $filename = now()->format('YmdHi').str_replace(' ','-',$image->getClientOriginalName());
                $image->move($destinationPath, $filename);
                array_push($images, $filename);
            }
            $product->images = json_encode($images);
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
        $product->ship_info = $request->ship_info;
        $product->ship_processing_time = $request->ship_processing_time;
        $product->ship_price = $request->ship_price;
        $product->warned_platform = $request->warned_platform;
        $product->fulfilled_by = $request->input('fulfilled-by');

        if ($request->variants){
            $product->variants = $request->variants;
        }
        $product->save();
        if ($request->category){
            $product->has_categories()->attach($request->category);
        }
        if ($request->sub_cat){
            $product->has_subcategories()->attach($request->sub_cat);
        }
        if ($request->variants) {
            $this->ProductVariants($request, $product->id);
        }
        return redirect()->back()->with('success','Item created successfully!');
    }

    public function ProductVariants($data, $id){
        for ($i=0; $i<count($data->variant_title); $i++){
            $options = explode('/', $data->variant_title[$i]);
            $variants  = new  ProductVariant();
            if (!empty($options[0])){
                $variants->option1 = $options[0];
            }
            if (!empty($options[1])){
                $variants->option2 = $options[1];
            }
            if (!empty($options[2])){
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

    public function delete($id){
        $product = Product::find($id);
        $product->delete();
        $variants = ProductVariant::where('product_id', $id)->get();
        foreach ($variants as $variant){
            $variant->delete();
        }
        return redirect()->back()->with('error', 'Product Deleted with Variants!');
    }

    public function add_existing_product_new_variants(Request $request){
        $product = Product::find($request->id);
        if($product->varaints == 0){
            return view('products.add_existing_product_new_variants')->with([
                'product' => $product
            ]);
        }
        else{
            return redirect('/products');
        }
    }
}
