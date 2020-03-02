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
        $products = Product::latest()->get();
        return view('products.all')->with([
            'products' => $products
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
        if ($request->category){
            $product->category = json_encode($request->category);
        }
        if ($request->sub_cat){
            $product->sub_category = json_encode($request->sub_cat);
        }
        if ($request->variants){
            $product->variants = $request->variants;
        }
        $product->save();

        if ($request->variants) {
        $data = $request;
            for ($i=0; $i<count($data->variant_title); $i++){
                $options = explode('/', $data->variant_title[$i]);
                $variants  = ProductVariant::find($request->variant_id[$i]);
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
                $variants->sku = $data->variant_sku[$i];
                $variants->barcode = $data->variant_barcode[$i];
                $variants->product_id = $id;
                $variants->save();
            }
        }
        return redirect()->route('product.all')->with('success','Item Updated successfully!');
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
                $filename = str_replace(' ','-',$image->getClientOriginalName());
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
        if ($request->category){
            $product->category = json_encode($request->category);
        }
        if ($request->sub_cat){
            $product->sub_category = json_encode($request->sub_cat);
        }
        if ($request->variants){
            $product->variants = $request->variants;
        }
        $product->save();
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
}
