<select name="" id="" class="">
    @foreach($rates as $rate)
        <option value="{{ $rate->Postage->Rate }}">{{ $rate->Postage->Rate .' USD' }}</option>
    @endforeach
</select>


{{--                        <form action="{{route('store.order.proceed.payment')}}" method="post">--}}
{{--                            @csrf--}}
{{--                            <input type="hidden" name="order_id" value="{{$order->id}}">--}}
{{--                            <div class="block-content font-size-sm">--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="col-sm-12">--}}
{{--                                        <div class="form-material">--}}
{{--                                            <label for="material-error">Card Name</label>--}}
{{--                                            <input  class="form-control" type="text" required=""  name="card_name"--}}
{{--                                                    placeholder="Enter Card Title here">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="col-sm-12">--}}
{{--                                        <div class="form-material">--}}
{{--                                            <label for="material-error">Card Number</label>--}}
{{--                                            <input type="text" required=""  name="card_number"  class="form-control js-card js-masked-enabled"--}}
{{--                                                   placeholder="9999-9999-9999-9999">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="col-sm-12">--}}
{{--                                        <div class="form-material">--}}
{{--                                            <label for="material-error">Amount to Pay</label>--}}
{{--                                            <input  class="form-control" type="text" readonly value="{{number_format($order->cost_to_pay,2)}} USD"  name="amount"--}}
{{--                                                    placeholder="Enter 14 Digit Card Number here">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="col-sm-12">--}}
{{--                                        <div class="form-material">--}}
{{--                                            <label for="material-error">YourWholesaleSource Charges ({{$settings->payment_charge_percentage}}%)</label>--}}
{{--                                            <input  class="form-control" type="text" readonly value="{{number_format($order->cost_to_pay*$settings->payment_charge_percentage/100,2)}} USD"  name="amount"--}}
{{--                                                    placeholder="Enter 14 Digit Card Number here">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="col-sm-12">--}}
{{--                                        <div class="form-material">--}}
{{--                                            <label for="material-error">Total Cost</label>--}}
{{--                                            <input  class="form-control" type="text" readonly value="{{number_format($order->cost_to_pay+$order->cost_to_pay*$settings->payment_charge_percentage/100,2)}} USD"  name="amount"--}}
{{--                                                    placeholder="Enter 14 Digit Card Number here">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                            </div>--}}

{{--                            <div class="block-content block-content-full text-right border-top">--}}
{{--                                <button type="submit" class="btn btn-success" >Proceed Payment</button>--}}
{{--                            </div>--}}
{{--                        </form>--}}
