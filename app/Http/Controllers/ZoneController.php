<?php

namespace App\Http\Controllers;

use App\Country;
use App\ShippingRate;
use App\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
   public function index(){
      $countries = Country::all();
       $zones =  Zone::all();
       return view('setttings.zones.index')->with([
           'zones'=>$zones,
           'countries' =>$countries
       ]);
   }

   public function create(Request $request){
//       dd($request);
       $zone = Zone::create([
          'name' => $request->input('name')
       ]);
       $zone->has_countries()->attach($request->input('countries'));
       return redirect()->back()->with('success','Zone Successfully Generated!');

   }
    public function rate_create(Request $request){
        ShippingRate::create($request->all());
        return redirect()->back()->with('success','Rate Successfully Generated!');
    }
    public function update(Request $request){
//       dd($request);
        $zone = Zone::find($request->id);
        $zone->name = $request->input('name');
        $zone->save();
        $zone->has_countries()->sync($request->input('countries'));
        return redirect()->back()->with('success','Zone Successfully Updated!');
    }
    public function delete(Request $request){
        $zone = Zone::find($request->id);
        $zone->has_countries()->detach();
        if(count($zone->has_rate) > 0) {
            foreach ($zone->has_rate as $rate) {
                $rate->delete();
            }
        }
        $zone->delete();
        return redirect()->back()->with('success','Zone Successfully Deleted!');

    }
    public function rate_update(Request $request){
        ShippingRate::find($request->id)->update($request->all());
        return redirect()->back()->with('success','Rate Successfully Updated!');
    }
    public function rate_delete(Request $request){
        ShippingRate::find($request->id)->delete();
        return redirect()->back()->with('success','Rate Successfully Deleted!');
    }

}
