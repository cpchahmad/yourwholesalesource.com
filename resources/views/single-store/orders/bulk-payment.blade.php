@extends('layout.single')
@section('content')
    <div class="content">
        <div class="row bulk-forms">
            @foreach($orders as $order)
                <form class="fulfilment_process_form col-md-12" action="{{route('admin.order.fulfillment.process',$order->id)}}" method="post">
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
                </form>
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
                                    Subtotal ({{count($line_items)}} items)
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
                                    Total Cost @if($order->paid == 0) to Pay @endif
                                </td>
                                <td align="right">
                                    {{number_format($cost_to_pay,2)}} USD
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align="right">
                                    @if($order->paid == 0)
                                        <button class="btn btn-success wallet-pay-button" data-href="{{route('store.order.wallet.pay',$order->id)}}" data-pay=" {{number_format($order->cost_to_pay,2)}} USD" ><i class="fa fa-wallet"></i> Wallet Pay</button>
                                    @endif
                                </td>
                            </tr>

                            </tbody>


                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
