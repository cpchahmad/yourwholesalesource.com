@php
    $total_discount = 0;
    $user = \App\User::find($order->user_id);
    $settings = \App\AdminSetting::all()->first();
    $is_monthly_discount = false;
    $n = $order->line_items->where('fulfilled_by', '!=', 'store')->sum('quantity');
    $line_item_count = count($order->line_items);
    $admin_setting_for_monthly_discount = \App\MonthlyDiscountSetting::first();


    if($admin_setting_for_monthly_discount && $admin_setting_for_monthly_discount->enable){
        if(\App\MonthlyDiscountPreference::where('user_id', $order->user_id)->exists() && \App\MonthlyDiscountPreference::where('user_id', $order->user_id)->first()->enable)
            $is_monthly_discount = true;
    }
    else {
        $is_monthly_discount = false;
    }

    if($order->line_items->where('fulfilled_by', '!=', 'store')->count() >=2){
        $is_general_discount = true;
    }
    else {
        $is_general_discount = false;
    }

    if(\App\GeneralDiscountPreferences::first()->global == 1) {
        $is_applied_for_general_dsiscount = true;
    }
    else {
        $users = \App\GeneralDiscountPreferences::first()->users_id;
        $users_array= json_decode($users);
        if(in_array($user->id, $users_array)) { $is_applied_for_general_dsiscount = true; } else { $is_applied_for_general_dsiscount = false; }
    }

    if(\App\GeneralFixedPricePreferences::first()->global == 1) {
        $is_applied_for_general_fixed = true;
    }
    else {
        $users = \App\GeneralFixedPricePreferences::first()->users_id;
        $users_array= json_decode($users);
        if(in_array($user->id, $users_array)) { $is_applied_for_general_fixed = true; } else { $is_applied_for_general_fixed = false; }
    }

    if(\App\TieredPricingPrefrences::first()->global == 1) {
        $is_applied = true;
    }
    else {
        $users = \App\TieredPricingPrefrences::first()->users_id;
        $users_array= json_decode($users);
        if(in_array($user->id, $users_array)) { $is_applied = true; } else { $is_applied = false; }
    }

@endphp


@foreach($order->line_items as $item)
    @if($item->fulfilled_by != 'store')
        @php
            $variant = $item->linked_real_variant;
            $real_variant = null;


            if($variant) {
                $real_variant = \App\ProductVariant::where('sku', $variant->sku)->first();
            }
            else{
                $retailer_product = $item->linked_real_product;
                if($retailer_product != null) {
                    $real_variant = \App\Product::where('title', $retailer_product->title)->first();
                }
            }
        @endphp
        @if($real_variant != null && $is_applied && !($is_general_discount) && !($is_monthly_discount))
            @if(count($real_variant->has_tiered_prices) > 0)
                @foreach($real_variant->has_tiered_prices as $var_price)
                    @php
                        $price = null;

                        $qty = (int) $item->quantity;
                        if(($var_price->min_qty <= $qty) && ($qty <= $var_price->max_qty)) {
                            if($var_price->type == 'fixed') {
                                $price = $var_price->price;
                                $price = $var_price->price * ($qty -1);
                                $price = number_format($price, 2);
                                $total_discount = $total_discount + $price;
                                $price = $price . " USD";
                            }
                            else if($var_price->type == 'discount') {
                                $discount = (double) $var_price->price;
                                $price = $item->price - ($item->price * $discount / 100);
                                $price = $price * ($qty -1);
                                $price = number_format($price, 2);
                                $total_discount = $total_discount + $price;
                                $price = $price . " USD";
                            }
                        }
                        else {
                            $price = '';
                        }
                    @endphp
                @endforeach
            @else

            @endif
        @else

        @endif

    @endif
@endforeach

@if($status == 'success')
    <tr>
        <td>
            Subtotal ({{count($order->line_items)}} items)
        </td>
        <td align="right">
            {{number_format($order->cost_to_pay - $order->shipping_price,2)}} USD
        </td>
    </tr>
    <tr>
        <td>
            Total Discount
        </td>
        <td align="right">
            @php
                if($is_general_discount && $is_applied_for_general_dsiscount) {
                    $discount = (double) \App\GeneralDiscountPreferences::first()->discount_amount;
                    $price = $order->cost_to_pay - ($order->cost_to_pay * $discount / 100);
                    $price = number_format($price, 2);
                    $total_discount = $total_discount + $price;
                    $total_discount = $order->cost_to_pay - $total_discount;
                }

                if($is_general_discount && $is_applied_for_general_fixed) {
                    $total_discount = (double) \App\GeneralFixedPricePreferences::first()->fixed_amount * ($n - 1);
                 }

                if($is_monthly_discount) {
                    $discount = (double) \App\MonthlyDiscountSetting::first()->discount;
                    $price = $order->cost_to_pay - ($order->cost_to_pay * $discount / 100);
                    $price = number_format($price, 2);
                    $total_discount = $total_discount + $price;
                    $total_discount = $order->cost_to_pay - $total_discount;
                }
            @endphp
            {{ number_format($total_discount,2) }} USD
        </td>
    </tr>

    <tr>
        <td>
            Shipping Price
        </td>
        <td align="right" class="shipping_price_text">
            {{$shipping}}
        </td>
    </tr>

    <tr>
        <td>
            Total Cost @if($order->paid == 0) to Pay @endif
        </td>
        <td align="right" class="total">
            {{number_format($total, 2)}} USD
        </td>
    </tr>
    <tr>
        <td></td>
        <td align="right">
            @if($order->paid == 0)
                {{--                                        <button class="btn btn-success" data-toggle="modal" data-target="#payment_modal"><i class="fa fa-credit-card"></i> Credit Card Pay</button>--}}
                <button class="btn btn-success paypal-pay-button" data-toggle="modal" data-target="#paypal_pay_trigger" data-href="{{route('store.order.paypal.pay',$order->id)}}" data-percentage="{{$settings->paypal_percentage}}" data-fee="{{number_format($total - $total_discount *$settings->paypal_percentage/100,2)}}" data-subtotal="{{number_format($total,2)}}" data-pay=" {{number_format($total+($total*$settings->paypal_percentage/100),2)}} USD" ><i class="fab fa-paypal"></i> Paypal Pay</button>
                <button class="btn btn-success wallet-pay-button" data-href="{{route('store.order.wallet.pay',$order->id)}}" data-pay=" {{ ($total - $total_discount) }}" d ><i class="fa fa-wallet"></i> Wallet Pay</button>


                <div class="modal" id="paypal_pay_trigger" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="block block-rounded block-themed block-transparent mb-0">
                                <div class="block-content cst_content_wrapper font-size-sm text-center">
                                    <h2>Are your sure?</h2>
                                    <div class="text-center"> <p>
                                            Subtotal: {{number_format($total - $total_discount,2)}} USD
                                            <br>
                                            WholeSaleSource Paypal Fee ({{$settings->paypal_percentage}}%): {{number_format($total - $total_discount*$settings->paypal_percentage/100,2)}} USD
                                            <br>Total Cost : {{number_format(($total - $total_discount)+($total*$settings->paypal_percentage/100),2)}} USD</p>
                                    </div>
                                    <p> A amount of  {{number_format(($total - $total_discount) +($total*$settings->paypal_percentage/100),2)}} USD will be deducted through your Paypal Account</p>

                                    <div class="paypal_btn_trigger">
                                        <div class="paypal-button-container"></div>
                                    </div>

                                </div>
                                <div class="block-content block-content-full text-center border-top">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ajax_paypal_form_submit" style="display: none;">
                    <form action="{{ route('store.order.paypal.pay.success', $order->id) }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $order->id }}">
                        <textarea name="response"></textarea>
                    </form>
                </div>


                <script>

                    paypal.Buttons({
                        createOrder: function(data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: '{{number_format(($total - $total_discount) +($total*$settings->paypal_percentage/100),2)}}'
                                    }
                                }]
                            });
                        },
                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(details) {
                                console.log(details);
                                $('.ajax_paypal_form_submit').find('textarea').val(JSON.stringify(details));
                                $('.ajax_paypal_form_submit form').submit();
                            });
                        }
                    }).render('.paypal-button-container');
                </script>


            @endif
        </td>
    </tr>
@endif




