@extends('layout.single')
@section('content')
    <div class="content">
        <div class="row bulk-forms">
            @foreach($orders as $order)
                <form class="col-md-12"  method="post">
                    @csrf
                    <input type="hidden" value="{{ $order->id }}" name="order_ids[]">

                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                {{$order->name}}
                            </h3>

                            @if($order->paid == '0')
                                <span class="badge badge-warning" style="font-size: small"> Unpaid </span>
                            @elseif($order->paid == '1')
                                <span class="badge badge-success" style="font-size: small"> Paid </span>
                            @elseif($order->paid == '2')
                                <span class="badge badge-danger" style="font-size: small;"> Refunded</span>
                            @endif

                            @if($order->status == 'Paid')
                                <span class="badge badge-warning" style="font-size: small"> Unfulfilled</span>
                            @elseif($order->status == 'unfulfilled')
                                <span class="badge badge-warning" style="font-size: small"> {{ucfirst($order->status)}}</span>
                            @elseif($order->status == 'partially-shipped')
                                <span class="badge " style="font-size: small;background: darkolivegreen;color: white;"> {{ucfirst($order->status)}}</span>
                            @elseif($order->status == 'shipped')
                                <span class="badge " style="font-size: small;background: orange;color: white;"> {{ucfirst($order->status)}}</span>
                            @elseif($order->status == 'delivered')
                                <span class="badge " style="font-size: small;background: deeppink;color: white;"> {{ucfirst($order->status)}}</span>
                            @elseif($order->status == 'completed')
                                <span class="badge " style="font-size: small;background: darkslategray;color: white;"> {{ucfirst($order->status)}}</span>
                            @elseif($order->status == 'new')
                                <span class="badge badge-warning" style="font-size: small"> Draft </span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge badge-warning" style="font-size: small"> {{ucfirst($order->status)}} </span>
                            @else
                                <span class="badge badge-success" style="font-size: small">  {{ucfirst($order->status)}} </span>
                            @endif
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
                                    <button type="button" class="btn btn-success wallet-pay-button" data-href="{{route('store.order.wallet.pay.bulk')}}" data-pay=" {{number_format($cost_to_pay,2)}} USD" ><i class="fa fa-wallet"></i> Wallet Pay</button>
                                    <button class="btn btn-success" data-toggle="modal" data-target="#payment_modal"><i class="fa fa-credit-card"></i> Credit Card Pay</button>
                                    <button class="btn btn-success paypal-pay-button" data-toggle="modal" data-target="#paypal_pay_trigger" data-href="{{route('store.order.paypal.pay',$order->id)}}" data-percentage="{{$settings->paypal_percentage}}" data-fee="{{number_format($order->cost_to_pay*$settings->paypal_percentage/100,2)}}" data-subtotal="{{number_format($order->cost_to_pay,2)}}" data-pay=" {{number_format($order->cost_to_pay+($order->cost_to_pay*$settings->paypal_percentage/100),2)}} USD" ><i class="fab fa-paypal"></i> Paypal Pay</button>

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
