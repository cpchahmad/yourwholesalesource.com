<?php

namespace App\Http\Controllers;

use App\Country;
use App\WareHouse;
use Illuminate\Http\Request;

class WareHouseController extends Controller
{
    public function index(Request $request) {
        $warehouses = WareHouse::paginate(30);
        $countries = Country::all();

        return view('setttings.warehouses.index')->with([
            'warehouses' => $warehouses,
            'countries' => $countries
        ]);
    }
}
