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
                if(count($product->hasVariants) > 0){
                    foreach ($product->hasVariants as $variant){
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

    public function inventory_connect(){
//        $this->single_inventory_sync();
        $shop = $this->helper->getAdminShop();

        $products = Product::whereNotNull('shopify_id')->get();
        foreach ($products as $product){

            $response =   $shop->api()->rest('GET', '/admin/api/2019-10/products/'. $product->shopify_id .'.json');
            if(!$response->errors){
                $shopifyVariants = $response->body->product->variants;
                if(count($product->hasVariants) == 0){
                    $product->inventory_item_id = $shopifyVariants[0]->inventory_item_id;
                    $product->save();
                    $this->process_connect($product, $shop);
                }
                else{
                    foreach ($product->hasVariants as $index => $variant){
                        $variant->inventory_item_id = $shopifyVariants[$index]->inventory_item_id;
                        $variant->save();
                        $this->process_connect($variant, $shop);
                    }

                }
            }
            sleep(2);


        }
    }

    public function single_inventory_sync(){
        $shop = $this->helper->getAdminShop();
        $product = Product::whereNotNull('inventory_item_id')->get();
        foreach ($product as $p){
            $this->process_connect($p, $shop);
            sleep(2);
        }


    }

    /**
     * @param $product
     * @param $shop
     */
    public function process_connect($product, $shop): void
    {
        /*Track Enable*/
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
//        dd($res);
    }
}
