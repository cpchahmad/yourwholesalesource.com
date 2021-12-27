<?php

namespace App\Http\Controllers;

use App\AdminSetting;
use App\InflowProduct;
use App\Product;
use App\ProductVariant;
use App\RetailerOrder;
use App\WarehouseInventory;
use App\Csv;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Session;
use Illuminate\Support\Facades\Auth;

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
                'name' => 'AwarenessDropshipping',
                'callback_url' => 'https://app.yourwholesalesource.com',
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
        $admin_settings = AdminSetting::first();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cloudapi.inflowinventory.com/'.$admin_settings->inflow_company_id.'/products?include=inventoryLines&count=10000&skip=18000',
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

        curl_close($curl);

        $products = collect(json_decode($response));


        if(count($products))
        {
            foreach ($products as $product) {
                $inflow_product = new InflowProduct();
                $inflow_product->product_id = $product->productId;
                $inflow_product->sku = $product->sku;
                $inflow_product->save();
            }
        }
    }

    public function addInflowIds() {
        InflowProduct::where('status', 0)->chunk(2000, function($inflow_products) {
            foreach ($inflow_products as $inflow_product) {
                $variant = ProductVariant::where('sku', $inflow_product->sku)->first();

                if($variant) {
                    $local_product = $variant->linked_product;

                    if($local_product) {
                        $local_product->inflow_id = $inflow_product->product_id;
                        $local_product->save();
                        $inflow_product->status = 1;
                        $inflow_product->save();
                    }
                }
            }
        });
    }

    public function deductProductInventory($product, $quantity) {
        $admin_settings = AdminSetting::first();

        if($product->inflow_id != null) {

            $payload = [
                "stockAdjustmentId" => (string) Str::uuid(),
                "adjustmentNumber" => Str::random(),
                "locationId" => "d2dbc807-374c-4844-83c2-d5e5d8e20b14",
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
                CURLOPT_URL => 'https://cloudapi.inflowinventory.com/'.$admin_settings->inflow_company_id.'/stock-adjustments?include=lines',
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
                    'Authorization: Bearer m7O7-bVkW5Qg-AFXyYj0twUzP2CR2ADO7ei3KMBUc3s'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

        }

    }

    public function syncProductInventory($product) {
        $admin_settings = AdminSetting::first();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cloudapi.inflowinventory.com/'.$admin_settings->inflow_company_id.'/products/'.$product->inflow_id.'?include=inventoryLines',
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



    public function getinventory(){

        $id = Auth::id();
        $csv=Csv::where('user_id',$id)->get();

        return view('inventory.inventory',compact('csv'));

    }





    public function exportCsv(Request $request){

       $products=Product::all();


        $fileName = 'tasks.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Encoding" => "UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );



        $columns = array('Product','Varientid', 'ProductTitle','VariantTitle', 'ProductPrice','VariantPrice', 'Product-Compare-price','Variant-Compare-price', 'Product-Cost','Variant-Cost','Product-Quantity','Variant-Quantity','Product-SKU','Variant-SKU','Recommended-price');


        $callback = function() use($products, $columns) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);


            foreach ($products as $getproduct) {
                $variant=ProductVariant::where('product_id',$getproduct->id)->get();


        if(count($variant)>0){
            foreach ($variant as $get) {
        $row['Product'] = $getproduct->id;
        $row['Varientid'] = $get->id;
        $row['ProductTitle'] = $getproduct->title;
        $row['VariantTitle'] = $get->title;
        $row['ProductPrice'] = $getproduct->price;
        $row['VariantPrice'] = $get->price;
        $row['Product-Compare-price'] = $getproduct->compare_price;
        $row['Variant-Compare-price'] = $get->compare_price;
        $row['Product-Cost'] = $getproduct->cost;
        $row['Variant-Cost'] = $get->cost;
        $row['Product-Quantity'] = $getproduct->quantity;
        $row['Variant-Quantity'] = $get->quantity;
        $row['Product-SKU'] = $getproduct->sku;
        $row['Variant-SKU'] = $get->sku;
        $row['Recommended-price'] = $getproduct->recommended_price;

        //        $row['SKU'] = $getproduct->sku;
        fputcsv($file, $row);
    }
}
        else {


//    $row['Product'] = $getproduct->id;
//    $row['Title'] = $getproduct->title;
//    $row['Price'] = $getproduct->price;
//    $row['Compare-price'] = $getproduct->compare_price;;
//    $row['Cost'] = $getproduct->cost;
//    $row['Quantity'] = $getproduct->quantity;
//    $row['SKU'] = $getproduct->sku;
//    $row['Recommended-price'] = $getproduct->recommended_price;




            $row['Product'] = $getproduct->id;
            $row['Varientid'] = '';
            $row['ProductTitle'] = $getproduct->title;
            $row['VariantTitle'] = '';
            $row['ProductPrice'] = $getproduct->price;
            $row['VariantPrice'] = '';
            $row['Product-Compare-price'] = $getproduct->compare_price;
            $row['Variant-Compare-price'] = '';
            $row['Product-Cost'] = $getproduct->cost;
            $row['Variant-Cost'] = '';
            $row['Product-Quantity'] = $getproduct->quantity;
            $row['Variant-Quantity'] = '';
            $row['Product-SKU'] = $getproduct->sku;
            $row['Variant-SKU'] = '';
            $row['Recommended-price'] = $getproduct->recommended_price;


    fputcsv($file, $row);
}
            }

            fclose($file);
        };


        return response()->stream($callback, 200, $headers);

    }


    public function importCsv(Request $request){

        $file = $request->file('file');


        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // Valid File Extensions
        $valid_extension = array("csv");

        // 2MB in Bytes
        $maxFileSize = 2097152;

        // Check file extension
        if(in_array(strtolower($extension),$valid_extension)){

            // Check file size
            if($fileSize <= $maxFileSize){

                // File upload location
                $location = 'uploads';

                // Upload file
                $file->move($location,$filename);

                // Import CSV to Database
                $filepath = public_path($location."/".$filename);

                // Reading file
                $file = fopen($filepath,"r");

                $importData_arr = array();
                $i = 0;

                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {


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


                // Insert to MySQL database
                foreach($importData_arr as $importData){



                    $get=Product::where('id',$importData[0])->first();

                    if(!empty($get)){


                    $variant=ProductVariant::where('product_id',$importData[0])->get();

                        if(count($variant)>0){

                            $get->title = $importData[2];
                            $get->price = $importData[4];
                            $get->compare_price = $importData[6];
                            $get->cost = $importData[8];
                            $get->quantity = $importData[10];
                            $get->sku = $importData[12];
                            $get->recommended_price = $importData[14];

                            $variants=ProductVariant::where('id',$importData[1])->get();



                            foreach ($variants as $getvariant) {

                                $getvariant->title = $importData[3];
                                $getvariant->price = $importData[5];
                                $getvariant->compare_price = $importData[7];
                                $getvariant->cost = $importData[9];
                                $getvariant->quantity = $importData[11];
                                $getvariant->sku = $importData[13];


                                $getvariant->update();


                            }

                            $get->update();



                        }

                        else {

                            $get->title = $importData[2];
                            $get->price = $importData[4];
                            $get->compare_price = $importData[6];
                            $get->cost = $importData[8];
                            $get->quantity = $importData[10];
                            $get->sku = $importData[12];
                            $get->recommended_price = $importData[14];
                            $get->update();
                        }
                    }
//                    else {
//                        $insertData = array(
//                            "title"=>$importData[2],
//                            "price"=>$importData[4],
//                            "compare_price"=>$importData[6],
//                            "cost"=>$importData[8],
//                            "quantity"=>$importData[10],
//                            "sku"=>$importData[12],
//                            "recommended_price"=>$importData[14]
//
//                        );
//
//
//
//
//                        Product::create($insertData);
//                    }



                }

                $id = Auth::id();
                $csv=new Csv;
                $csv->user_id=$id;
                $csv->filename=$filename;
                $csv->save();



                Session::flash('message','Import Successful.');

            }else{
                Session::flash('message','File too large. File must be less than 2MB.');

            }

        }else{
            Session::flash('message','Invalid File Extension.');

        }
        return back();





    }
}

