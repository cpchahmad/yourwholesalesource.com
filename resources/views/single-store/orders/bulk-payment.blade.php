@extends('layout.single')
@section('content')
    <div class="content">
        <div class="row bulk-forms">
            @foreach($orders as $order)
                <form class="col-md-12 bulk-payment-form"  method="post" action="{{ route('store.order.wallet.pay.bulk') }}">
                    @csrf
                    <input type="hidden" value="{{ $order->id }}" name="order_ids[]">

                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                {{$order->name}}
                            </h3>
                        </div>
                        <div class="block-content">
                            <table class="table table-hover table-borderless table-striped table-vcenter">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th style="width: 10%">Name</th>
                                    <th>Fulfilled By</th>
                                    <th>Cost</th>
                                    <th>Price X Quantity</th>
                                    <th>Status</th>
                                    <th>Billing Address</th>
                                    <th>Shipping Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->line_items as $item)
                                    @if($item->fulfilled_by != 'store')
                                        <tr>
                                            <td>
                                                @if($order->custom == 0)
                                                    @if($item->linked_variant != null)
                                                        <img class="img-avatar"
                                                             @if($item->linked_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                             @else @if($item->linked_variant->has_image->isV == 1) src="{{asset('images/variants')}}/{{$item->linked_variant->has_image->image}}" @else src="{{asset('images')}}/{{$item->linked_variant->has_image->image}}" @endif @endif alt="">
                                                    @else
                                                        @if($item->linked_product != null)
                                                            @if(count($item->linked_product->has_images)>0)
                                                                @if($item->linked_product->has_images[0]->isV == 1)
                                                                    <img class="img-avatar img-avatar-variant"
                                                                         src="{{asset('images/variants')}}/{{$item->linked_product->has_images[0]->image}}">
                                                                @else
                                                                    <img class="img-avatar img-avatar-variant"
                                                                         src="{{asset('images')}}/{{$item->linked_product->has_images[0]->image}}">
                                                                @endif
                                                            @else
                                                                <img class="img-avatar img-avatar-variant"
                                                                     src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                            @endif
                                                        @else
                                                            <img class="img-avatar img-avatar-variant"
                                                                 src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                        @endif
                                                    @endif
                                                @else
                                                    @if($item->linked_real_variant != null)
                                                        <img class="img-avatar"
                                                             @if($item->linked_real_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                             @else @if($item->linked_real_variant->has_image->isV == 1) src="{{asset('images/variants')}}/{{$item->linked_real_variant->has_image->image}}" @else src="{{asset('images')}}/{{$item->linked_real_variant->has_image->image}}" @endif @endif alt="">
                                                    @else
                                                        @if($item->linked_real_product != null)
                                                            @if(count($item->linked_real_product->has_images)>0)
                                                                @if($item->linked_real_product->has_images[0]->isV == 1)
                                                                    <img class="img-avatar img-avatar-variant"
                                                                         src="{{asset('images/variants')}}/{{$item->linked_real_product->has_images[0]->image}}">
                                                                @else
                                                                    <img class="img-avatar img-avatar-variant"
                                                                         src="{{asset('images')}}/{{$item->linked_real_product->has_images[0]->image}}">
                                                                @endif
                                                            @else
                                                                <img class="img-avatar img-avatar-variant"
                                                                     src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                            @endif
                                                        @else
                                                            <img class="img-avatar img-avatar-variant"
                                                                 src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td style="width: 30%">
                                                {{$item->name}}

                                            </td>
                                            <td>
                                                @if($item->fulfilled_by == 'store')
                                                    <span class="badge badge-danger"> Store</span>
                                                @elseif ($item->fulfilled_by == 'Fantasy')
                                                    <span class="badge badge-success"> WeFullFill </span>
                                                @else
                                                    <span class="badge badge-success"> {{$item->fulfilled_by}} </span>
                                                @endif
                                            </td>

                                            <td>{{number_format($item->cost,2)}}  X {{$item->quantity}}  USD</td>
                                            <td>{{$item->price}} X {{$item->quantity}}  USD </td>
                                            <td>
                                                @if($item->fulfillment_status == null)
                                                    <span class="badge badge-warning"> Unfulfilled</span>
                                                @elseif($item->fulfillment_status == 'partially-fulfilled')
                                                    <span class="badge badge-danger"> Partially Fulfilled</span>
                                                @else
                                                    <span class="badge badge-success"> Fulfilled</span>
                                                @endif
                                            </td>
                                            @php
                                                $billing = json_decode($order->billing_address);
                                                $shipping = json_decode($order->shipping_address)
                                            @endphp
                                            <td class="align-middle">
                                                @if(!(is_null($billing)))
                                                    <p style="font-size: 14px">{{$billing->first_name}} {{$billing->last_name}} <br> {{$billing->company}}
                                                        <br> {{$billing->address1}}
                                                        <br> {{$billing->address2}}
                                                        <br> {{$billing->city}}
                                                        <br> {{$billing->province}} {{$billing->zip}}
                                                        <br> {{$billing->country}}
                                                        <br> {{$billing->phone}}
                                                    </p>
                                                @else
                                                    <p>Not Provided!</p>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if(!(is_null($billing)))
                                                    <p style="font-size: 14px">{{$shipping->first_name}} {{$shipping->last_name}}
                                                        <br> {{$shipping->company}}
                                                        <br> {{$shipping->address1}}
                                                        <br> {{$shipping->address2}}
                                                        <br> {{$shipping->city}}
                                                        <br> {{$shipping->province}} {{$shipping->zip}}
                                                        <br> {{$shipping->country}}
                                                        @if(isset($shipping->phone))
                                                            <br>{{$shipping->phone}}
                                                        @endif
                                                    </p>
                                                @else
                                                    <p>Not Provided!</p>
                                                @endif
                                            </td>

                                        </tr>
                                    @endif
                                @endforeach

                                </tbody>


                            </table>
                        </div>
                    </div>
            @endforeach
                    <div class="col-md-12">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Summary
                        </h3>
                    </div>
                    <div class="block-content">
                        <table class="table table-borderless table-vcenter">
                            <thead>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    Subtotal
                                </td>
                                <td align="right">
                                    {{number_format($cost_to_pay - $shipping_price,2)}} USD
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Shipping Price
                                </td>
                                <td align="right">
                                    {{number_format($shipping_price,2)}} USD
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Total Cost  to Pay
                                </td>
                                <td align="right">
                                    {{number_format($cost_to_pay,2)}} USD
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align="right">
                                    <button type="button" class="btn btn-success bulk-wallet-pay-button" data-pay=" {{number_format($cost_to_pay,2)}} USD" ><i class="fa fa-wallet"></i> Wallet Pay</button>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#payment_modal"><i class="fa fa-credit-card"></i> Credit Card Pay</button>
                                    <button type="button" class="btn btn-success paypal-pay-button" data-toggle="modal" data-target="#paypal_pay_trigger" data-href="{{route('store.order.paypal.pay',$order->id)}}" data-percentage="{{$settings->paypal_percentage}}" data-fee="{{number_format($order->cost_to_pay*$settings->paypal_percentage/100,2)}}" data-subtotal="{{number_format($order->cost_to_pay,2)}}" data-pay=" {{number_format($order->cost_to_pay+($order->cost_to_pay*$settings->paypal_percentage/100),2)}} USD" ><i class="fab fa-paypal"></i> Paypal Pay</button>

                                    @if($order->paid == 0)
                                        <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    <form  method="POST" class="bulk-card-submit">
                                                        @csrf
                                                        <div class="block block-themed block-transparent mb-0">
                                                            <div class="block-header bg-primary-dark text-left">
                                                                <h3 class="block-title">Payment for Orders</h3>
                                                                <div class="block-options">
                                                                    <button type="button" class="btn-block-option">
                                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                    </button>
                                                                </div>
                                                            </div>


                                                                <div class="block-content font-size-sm">
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12 text-left">
                                                                            <div class="form-material">
                                                                                <label for="material-error">Card Name</label>
                                                                                <input  class="form-control" type="text" required=""  name="card_name"
                                                                                        placeholder="Enter Card Title here">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12 text-left">
                                                                            <div class="form-material">
                                                                                <label for="material-error">Card Number</label>
                                                                                <input type="text" required=""  name="card_number"  class="form-control js-card js-masked-enabled"
                                                                                       placeholder="9999-9999-9999-9999">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12 text-left">
                                                                            <div class="form-material">
                                                                                <label for="material-error">Amount to Pay</label>
                                                                                <input  class="form-control" type="text" readonly value="{{number_format($cost_to_pay,2)}} USD"  name="amount"
                                                                                        placeholder="Enter 14 Digit Card Number here">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12 text-left">
                                                                            <div class="form-material">
                                                                                <label for="material-error">WeFullFill Charges ({{$settings->payment_charge_percentage}}%)</label>
                                                                                <input  class="form-control" type="text" readonly value="{{number_format($cost_to_pay*$settings->payment_charge_percentage/100,2)}} USD"  name="amount"
                                                                                        placeholder="Enter 14 Digit Card Number here">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12 text-left">
                                                                            <div class="form-material">
                                                                                <label for="material-error">Total Cost</label>
                                                                                <input  class="form-control" type="text" readonly value="{{number_format($cost_to_pay+$cost_to_pay*$settings->payment_charge_percentage/100,2)}} USD"  name="amount"
                                                                                        placeholder="Enter 14 Digit Card Number here">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="block-content block-content-full text-right border-top">
                                                                    <button type="button" class="btn btn-success bulk-card-btn">Proceed Payment</button>
                                                                </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    <div class="modal" id="paypal_pay_trigger" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="block block-rounded block-themed block-transparent mb-0">
                                                    <div class="block-content cst_content_wrapper font-size-sm text-center">
                                                        <h2>Are your sure?</h2>
                                                        <div class="text-center"> <p>
                                                                Subtotal: {{number_format($order->cost_to_pay,2)}} USD
                                                                <br>
                                                                WeFullFill Paypal Fee ({{$settings->paypal_percentage}}%): {{number_format($order->cost_to_pay*$settings->paypal_percentage/100,2)}} USD
                                                                <br>Total Cost : {{number_format($order->cost_to_pay+($order->cost_to_pay*$settings->paypal_percentage/100),2)}} USD</p>
                                                        </div>
                                                        <p> A amount of  {{number_format($order->cost_to_pay+($order->cost_to_pay*$settings->paypal_percentage/100),2)}} USD will be deducted through your Paypal Account</p>

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
                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
                </form>
        </div>
    </div>



@endsection
