<?php

namespace App\Http\Controllers;

use App\DefaultInfo;
use Illuminate\Http\Request;

class DefaultSettingsController extends Controller
{
    public function index()
    {
        $info = DefaultInfo::get()->first();
        return view('setttings.default.index')->with([
            'info' => $info
        ]);
    }

    public function save(Request $request)
    {
        $info = new DefaultInfo();
        $info->ship_info = $request->info;
        $info->processing_time = $request->time;
        $info->ship_price = $request->price;
        $info->warned_platform = $request->warnedplatform;
        $info->save();
        return redirect()->back()->with('success', 'Saved Sucessfully');
    }

    public function update(Request $request, $id)
    {
        $info = DefaultInfo::find($id);
        $info->ship_info = $request->info;
        $info->processing_time = $request->time;
        $info->ship_price = $request->price;
        $info->warned_platform = $request->warnedplatform;
        $info->save();
        return redirect()->back()->with('success', 'Updated Sucessfully');
    }
}
