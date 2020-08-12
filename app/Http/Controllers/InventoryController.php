<?php

namespace App\Http\Controllers;

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
                'handle' => 'wefullfill-inventorty',
                'callback_url' => env('APP_URL'),
                "inventory_management"=> true,
                "tracking_support"=>false,
                "requires_shipping_method"=> false,
                "format"=>"json"
            ]
        ];

        $shop = $this->helper->getShop();
        $resp =  $shop->api()->rest('POST', '/admin/api/2020-04/fulfillment_services.json',$data);
        dd($resp);

    }

}
