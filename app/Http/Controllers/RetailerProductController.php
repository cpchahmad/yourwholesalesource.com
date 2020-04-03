<?php

namespace App\Http\Controllers;

use App\Product;
use App\RetailerImage;
use App\RetailerProduct;
use App\RetailerProductVariant;
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
                    foreach ($product->has_images as $image){
                        $retailerProductImage = new RetailerImage();
                        $retailerProductImage->isV = $image->isV;
                        $retailerProductImage->product_id = $retailerProduct->id;
                        $retailerProductImage->shop_id =  $retailerProduct->shop_id;
                        $retailerProductImage->user_id =  $retailerProduct->user_id;
                        $retailerProductImage->image = $image->image;
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
                            $retailerProductVariant->cost = $variant->cost;
                            $retailerProductVariant->quantity = $variant->quantity;

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
                    if(!in_array($retailerProduct->id,$shop->has_imported->pluck('id')->toArray())){
                        $shop->has_imported()->attach([$retailerProduct->id]);
                    }
                }
                /*Shop-User Import Relation*/
                if(count($this->helper->getLocalShop()->has_user) > 0){
                    $user = $this->helper->getLocalShop()->has_user[0];
                    if(!in_array($retailerProduct->id,$user->has_imported->pluck('id')->toArray())){
                        $user->has_imported()->attach([$retailerProduct->id]);
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

    public function import_list(Request $request){
        $productQuery = RetailerProduct::where('toShopify',0)->where('shop_id',$this->helper->getLocalShop()->id)->newQuery();
       $products = $productQuery->paginate(12);
       $shop = $this->helper->getLocalShop();
        return view('single-store.products.import_list')->with([
            'products' => $products,
            'shop' => $shop
        ]);
    }
}
