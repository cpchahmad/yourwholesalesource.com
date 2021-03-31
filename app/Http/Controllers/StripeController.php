<?php

namespace App\Http\Controllers;

use App\RetailerOrder;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function processPayment(Request $request)
    {
        $order = RetailerOrder::find($request->order_id);

        Stripe::setApiKey(env('STRIPE_SECRET'));
        Charge::create ([
            "amount" => $request->amount_to_be_paid * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Order Payment for". $order->name
        ]);

        return redirect()->back()->with('success', 'Stripe Payment successful!');

    }
}
