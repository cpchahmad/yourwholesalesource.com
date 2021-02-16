<tbody class="js-warehouse-shipping">
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
        {{$shipping}} USD
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
            <button class="btn btn-success paypal-pay-button" data-toggle="modal" data-target="#paypal_pay_trigger" data-href="{{route('store.order.paypal.pay',$order->id)}}" data-percentage="{{$settings->paypal_percentage}}" data-fee="{{number_format($order->cost_to_pay - $total_discount *$settings->paypal_percentage/100,2)}}" data-subtotal="{{number_format($order->cost_to_pay,2)}}" data-pay=" {{number_format($order->cost_to_pay+($order->cost_to_pay*$settings->paypal_percentage/100),2)}} USD" ><i class="fab fa-paypal"></i> Paypal Pay</button>
            <button class="btn btn-success wallet-pay-button" data-href="{{route('store.order.wallet.pay',$order->id)}}" data-pay=" {{number_format($order->cost_to_pay - $total_discount,2)}} USD" d ><i class="fa fa-wallet"></i> Wallet Pay</button>


            <div class="modal" id="paypal_pay_trigger" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="block block-rounded block-themed block-transparent mb-0">
                            <div class="block-content cst_content_wrapper font-size-sm text-center">
                                <h2>Are your sure?</h2>
                                <div class="text-center"> <p>
                                        Subtotal: {{number_format($order->cost_to_pay - $total_discount,2)}} USD
                                        <br>
                                        WeFullFill Paypal Fee ({{$settings->paypal_percentage}}%): {{number_format($order->cost_to_pay - $total_discount*$settings->paypal_percentage/100,2)}} USD
                                        <br>Total Cost : {{number_format(($order->cost_to_pay - $total_discount)+($order->cost_to_pay*$settings->paypal_percentage/100),2)}} USD</p>
                                </div>
                                <p> A amount of  {{number_format(($order->cost_to_pay - $total_discount) +($order->cost_to_pay*$settings->paypal_percentage/100),2)}} USD will be deducted through your Paypal Account</p>

                                <div class="paypal_btn_trigger">
                                    <div id="paypal-button-container"></div>
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

        @endif
    </td>
</tr>

</tbody>

