<?php

namespace App\Http\Controllers;

use App\RetailerOrder;
use App\Ticket;
use App\User;
use App\Wallet;
use App\Wishlist;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('setttings.email.index');
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
        switch ($id) {
            case "1":
                return view('emails.new_user')->with('user', User::find(1));
                break;
            case "2":
                return view('emails.new_shopify_user')->with('user', User::find(1));
                break;
            case "3":
                return view('emails.order_place')->with('user', User::find(1))->with('retail_order', RetailerOrder::find(1));
                break;
            case "4":
                return view('emails.order_status')->with('user', User::find(1))->with('order', RetailerOrder::find(1));
                break;
            case "5":
                return view('emails.wishlist_request')->with('wishlist', Wishlist::find(1));
                break;
            case "6":
                return view('emails.wallet_reqeust')->with('wallet', Wallet::find(1));
                break;
            case "7":
                return view('emails.refund_request')->with('ticket', Ticket::find(1));
                break;
            default:
                echo "Error";
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
        //
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
        //
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
}
