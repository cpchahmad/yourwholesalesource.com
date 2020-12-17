<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use App\Product;
use App\RetailerOrder;
use App\Ticket;
use App\User;
use App\Wallet;
use App\Wishlist;
use Illuminate\Http\Request;
use PharIo\Manifest\Email;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('setttings.email.index')->with('templates', EmailTemplate::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $date = \Carbon\Carbon::today()->subDays(7);
        $new_products = Product::where('created_at','>=',$date)->where('status', 1)->where('global', 1)->latest()->limit(6)->get();
        $temp_product = Product::first();

        return view('setttings.email.show')->with('template', EmailTemplate::find($id))
            ->with('order', RetailerOrder::find(1))
            ->with('top_products_stores', \App\Product::all())
            ->with('new_products', $new_products)
            ->with('temp_product', $temp_product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('setttings.email.show')->with('template', EmailTemplate::find($id))->with('edit', 1)->with('order', RetailerOrder::find(1))->with('products', Product::all())->with('temp_product', Product::first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $template = EmailTemplate::find($id);
        $template->subject = $request->subject;
        $template->body = $request->body;

        if($request->products) {
            $template->products = json_encode($request->products);
        }
        if($request->day) {
            $template->day = $request->day;
        }
        if($request->time) {
            $template->time = $request->time;
        }
        if($request->hasFile('banner')){
            $file = $request->file('banner');

            dd($file);

            $name =now()->format('YmdHi') . str_replace([' ','(',')'], '-', $file->getClientOriginalName());
            $attachement = date("mmYhisa_") . $name;
            $file->move(public_path() . '/ticket-attachments/', $attachement);
            $template->banner = $attachement;
        }

        $template->save();

        return redirect()->route('admin.emails.show',$template->id)->with('success','Email Template updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request) {
        $template = EmailTemplate::find($request->template);
        $template->status = $request->status;
        $template->save();
    }
}
