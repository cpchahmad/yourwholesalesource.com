<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductVariant;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    private $helper;

    /**
     * InventoryController constructor.
     * @param $helper
     */
    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function create_service(){
        $data = [
            'fulfillment_service' => [
                'name' => 'WeFullFill',
                'callback_url' => env('APP_URL'),
                "inventory_management"=> true,
                "tracking_support"=>false,
                "requires_shipping_method"=> false,
                "format"=>"json"
            ]
        ];

        $shop = $this->helper->getShop();
        $resp =  $shop->api()->rest('POST', '/admin/api/2020-04/fulfillment_services.json',$data);

    }

    public function FetchQuantity(Request $request){
        if($request->has('sku')){
           $variant = ProductVariant::where('sku',$request->input('sku'))->first();
           if($variant != null){
               return response()->json([
                   $request->input('sku') => $variant->quantity
               ]);
           }
           else{
               $product = Product::where('sku',$request->input('sku'))->first();
               if($product != null){
                   return response()->json([
                       $request->input('sku') => $product->quantity
                   ]);
               }
           }

        }
        else{
           $products = Product::all();
           $json = [];
           foreach ($products as $product){
               if(count($products->hasVariants) > 0){
                   foreach ($products->hasVariants as $variant){
                       $json[$variant->sku] = $variant->quantity;
                   }
               }
               else{
                   $json[$product->sku] = $product->quantity;

               }
           }

        }
        return response()->json($json);
    }

}
