<?php

namespace App\Http\Controllers;

use App\InflowProduct;
use App\Product;
use App\ProductVariant;
use App\RetailerOrder;
use App\WarehouseInventory;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

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
                'callback_url' => 'https://app.wefullfill.com',
                "inventory_management"=> true,
                "tracking_support"=>false,
                "requires_shipping_method"=> false,
                "format"=>"json"
            ]
        ];


        $shop = $this->helper->getShop();
        if($shop->location == null){
            $resp =  $shop->api()->rest('POST', '/admin/api/2020-04/fulfillment_services.json',$data);
            if(!$resp->errors){
                $data = $resp->body->fulfillment_service;
                $shop->location_id =$data->location_id;
                $shop->save();
            }
        }

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
            //sleep(2);


        }
    }

    public function single_inventory_sync(){
        $shop = $this->helper->getAdminShop();
        $product = Product::whereNotNull('inventory_item_id')->get();
        foreach ($product as $p){
            $this->process_connect($p, $shop);
            //sleep(2);
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


    public function OrderQuantityUpdate(RetailerOrder $order, $type){

            foreach ($order->line_items as $item){
                $variant = ProductVariant::where('sku',$item->sku)->first();
                if($variant != null){
                    $variant_warehouse_inventory = WarehouseInventory::where('warehouse_id', $item->selected_warehouse)->where('product_variant_id', $variant->id)->first();

                    if($type == 'new') {
                        $variant->quantity = $variant->quantity - $item->quantity;
                        if($variant_warehouse_inventory)
                            $variant_warehouse_inventory->quantity = $variant_warehouse_inventory->quantity - $item->quantity;
                    }
                    else{
                        $variant->quantity = $variant->quantity + $item->quantity;
                        if($variant_warehouse_inventory)
                            $variant_warehouse_inventory->quantity = $variant_warehouse_inventory->quantity + $item->quantity;
                    }
                    $variant->save();
                    if($variant_warehouse_inventory)
                        $variant_warehouse_inventory->save();

                    $variant->linked_product->quantity = $variant->linked_product->varaint_count($variant->linked_product);
                    $variant->linked_product->save();

                    if($type == 'new')
                        $this->deductProductInventory($variant->linked_product, $item->quantity);

                    Artisan::call('app:sku-quantity-change',['product_id'=> $variant->product_id]);
                }
                else{
                    $product = Product::where('sku',$item->sku)->first();
                    if($product != null){
                        $product_warehouse_inventory = WarehouseInventory::where('warehouse_id', $item->selected_warehouse)->where('product_id', $product->id)->first();

                        if($type == 'new') {
                            $product->quantity = $product->quantity - $item->quantity;
                            if($product_warehouse_inventory)
                                $product_warehouse_inventory->quantity = $product_warehouse_inventory->quantity - $item->quantity;
                        }
                        else{
                            $product->quantity = $product->quantity + $item->quantity;
                            if($product_warehouse_inventory)
                                $product_warehouse_inventory->quantity = $product_warehouse_inventory->quantity + $item->quantity;

                        }
                        $product->save();
                        if($product_warehouse_inventory)
                            $product_warehouse_inventory->save();

                        if($type == 'new')
                            $this->deductProductInventory($product, $item->quantity);

                        Artisan::call('app:sku-quantity-change',['product_id'=> $product->id]);
                    }
                }
            }
        }


    public function syncInflowProducts() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cloudapi.inflowinventory.com/c56df956-12ae-42f6-a237-0f184b484d87/products?include=inventoryLines',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json;version=2021-04-26',
                'Authorization: Bearer m7O7-bVkW5Qg-AFXyYj0twUzP2CR2ADO7ei3KMBUc3s'
            ),
        ));

        $response = curl_exec($curl);

        dd($response);

        curl_close($curl);

        $products = json_decode($response);

        dd($products);

        foreach ($products as $product) {
            $inflow_product = new InflowProduct();
            $inflow_product->product_id = $product->productId;
            $inflow_product->sku = $product->sku;
            $inflow_product->save();
        }
    }

    public function addInflowIds() {
        $inflow_products = InflowProduct::all();

        foreach ($inflow_products as $inflow_product) {
            $variant = ProductVariant::where('sku', $inflow_product->sku)->first();

            if($variant) {
                $local_product = $variant->linked_product;

                if($local_product) {
                    $local_product->inflow_id = $inflow_product->product_id;
                    $local_product->save();
                }
            }
        }
    }

    public function deductProductInventory($product, $quantity) {

        if($product->inflow_id != null) {

            $payload = [
                "stockAdjustmentId" => (string) Str::uuid(),
                "adjustmentNumber" => Str::random(),
                "locationId" => "d2bc5676-c298-4edb-9ddb-20c8fc135fc5",
                "lines" => [
                    [
                        "stockAdjustmentLineId" => (string) Str::uuid(),
                        "description" => "Testing",
                        "productId" => $product->inflow_id,
                        "quantity" => [
                            "standardQuantity" => "-".$quantity,
                            "uomQuantity" => "-".$quantity,
                            "serialNumbers" => [
                            ]
                        ],
                        "timestamp" => Carbon::now()->timestamp
                    ]
                ]
            ];


            $payload = json_encode($payload);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://cloudapi.inflowinventory.com/6bc5998f-eb23-4761-bbbb-2fe8f3f5b5bc/stock-adjustments?include=lines',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json;version=2021-04-26',
                    'Authorization: Bearer 117TXC5I_fH4jCwKo2ajz9nIGdUDAWixMGg46Uue-Qc'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

        }

    }

    public function syncProductInventory($product) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cloudapi.inflowinventory.com/6bc5998f-eb23-4761-bbbb-2fe8f3f5b5bc/products/'.$product->inflow_id.'?include=inventoryLines',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json;version=2021-04-26',
                'Authorization: Bearer 117TXC5I_fH4jCwKo2ajz9nIGdUDAWixMGg46Uue-Qc'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $inflow_product = json_decode($response);

        $variant = $product->hasVariants()->where('sku', $inflow_product->sku)->first();

        if($variant)
        {
            $variant->quantity = (int) $inflow_product->totalQuantityOnHand;
            $variant->save();
        }

        $product->quantity = (int) $inflow_product->totalQuantityOnHand;
        $product->save();

    }
}

