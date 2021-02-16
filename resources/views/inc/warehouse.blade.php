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
