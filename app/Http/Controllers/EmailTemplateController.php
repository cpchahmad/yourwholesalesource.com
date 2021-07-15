<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\EmailTemplate;
use App\Jobs\SendNewsEmailJob;
use App\Mail\NewProductsMail;
use App\Mail\NewsEmail;
use App\Product;
use App\RetailerOrder;
use App\Ticket;
use App\User;
use App\Wallet;
use App\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
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
        switch ($id){
            case 1:
                return view('emails.new_user')->with('template', EmailTemplate::find(1));
            case 2:
                return view('emails.new_shopify_user')->with('template', EmailTemplate::find(2));
            case 3:
                return view('emails.order_place')->with('template', EmailTemplate::find(3))->with('order', RetailerOrder::latest()->first());
            case 4:
                return view('emails.order_status')->with('template', EmailTemplate::find(4))->with('order', RetailerOrder::latest()->first());
            case 5:
                return view('emails.wishlist_approve')->with('template', EmailTemplate::find(5))->with('wishlist', Wishlist::find(1));
            case 6:
                return view('emails.wallet_reqeust')->with('template', EmailTemplate::find(6))->with('wallet', Wallet::find(1));
            case 7:
                return view('emails.refund_request')->with('template', EmailTemplate::find(7))->with('ticket', Ticket::find(1));
            case 8:
                return view('emails.wishlist_approve')->with('template', EmailTemplate::find(8))->with('wishlist', Wishlist::find(1));
            case 9:
                return view('emails.wishlist_reject')->with('template', EmailTemplate::find(9))->with('wishlist', Wishlist::find(1));
            case 10:
                return view('emails.wallet_approve')->with('template', EmailTemplate::find(10))->with('wallet', Wallet::find(1));
            case 11:
                return view('emails.ticket_reply')->with('template', EmailTemplate::find(11))->with('ticket', Ticket::find(1));
            case 12:
                return view('emails.wishlist_complete')->with('template', EmailTemplate::find(12))->with('wishlist', Wishlist::find(1));
            case 13:
                return view('emails.top_products_new')->with('template', EmailTemplate::find(13))->with('top_products_stores', Product::all());
            case 14:
                return view('emails.new_products')->with('template', EmailTemplate::find(14))->with('new_products', Product::all());
            case 15:
                return view('emails.product_stock')->with('template', EmailTemplate::find(15))->with('product', Product::latest()->first());
            case 16:
                return view('emails.product_stock')->with('template', EmailTemplate::find(16))->with('product', Product::latest()->first());
            case 17:
                return view('emails.variant_stock')->with('template', EmailTemplate::find(17))->with('product', Product::latest()->first());
            case 18:
                return view('emails.news-email')->with('template', EmailTemplate::find(18));
            case 19:
                return view('emails.wallet_balance')->with('template', EmailTemplate::find(19))->with('wallet', Wallet::find(1));
            case 20:
                return view('emails.news_products')->with('template', EmailTemplate::find(20))->with('top_products_stores', Product::all());
            case 21:
                return view('emails.integration')->with('template', EmailTemplate::find(21));
            case 22:
                return view('emails.resources-email')->with('template', EmailTemplate::find(22));
            default:
                return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('setttings.email.show')->with('template', EmailTemplate::find($id))->with('edit', 1)->with('order', RetailerOrder::first())->with('products', Product::all())->with('temp_product', Product::first());
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

        if($template->id == 18 || $template->id == 20)
        {
            $campaign = new Campaign();
            $campaign->name = $request->campaign_name;
            $campaign->time = $request->time;
            $campaign->status = 'pending';
            $campaign->template_id = $template->id;
            $campaign->save();

            $users_id = [];

            if($request->users)
                $users_id = array_merge($users_id, $request->users);
            if($request->shopify_users)
                $users_id = array_merge($users_id, $request->shopify_users);
            if($request->shopify_users_with_orders)
                $users_id = array_merge($users_id, $request->shopify_users_with_orders);
            if($request->non_shopify_users_with_orders)
                $users_id = array_merge($users_id, $request->non_shopify_users_with_orders);
            if($request->users_with_products)
                $users_id = array_merge($users_id, $request->users_with_products);
            if($request->users_without_products)
                $users_id = array_merge($users_id, $request->users_without_products);

            foreach (array_unique($users_id) as $id) {
                $user = User::find($id);
                $user->campaigns()->attach($campaign->id);
            }
        }

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
