@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    {{$order->name}}
                    <button  onclick="window.location.href='{{ url()->previous() }}'" class="btn btn-sm btn-primary"  style="margin-right: 10px"> Go Back </button>
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            All Orders
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx active" href=""> {{$order->name}}</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row" style="margin-bottom: 20px">
            <div class="col-md-12">
{{--                <button  onclick="window.location.href='{{route('app.order.download',$order->id)}}'" class="btn btn-sm btn-primary"  style="float: right;margin-right: 10px"> Download CSV </button>--}}
                @if($order->status == "shipped")
                    <button  onclick="window.location.href='{{route('admin.order.mark_as_delivered',$order->id)}}'" class="btn btn-sm btn-success"  style="margin-right: 10px;float: right"> Mark as Delivered </button>
                @endif
                @if($order->paid != 2)
                    <button class="btn btn-sm btn-danger" style="float: right;margin-right: 10px" onclick="window.location.href='{{route('app.refund_cancel_order',$order->id)}}'">Cancel and Refund Order</button>
                @endif
{{--                @if($order->admin_shopify_id == null &&  $order->admin_shopify_name == null)--}}
{{--                    <button class="btn btn-sm btn-success" style="float: right;margin-right: 10px" onclick="window.location.href='{{route('admin.manual_push_to_wefulfill',$order->id)}}'">Sync to Wefulfill Store</button>--}}
{{--                @endif--}}
{{--                @if($order->status == 'Paid')--}}
{{--                    <button class="btn btn-sm btn-info" style="float: right;margin-right: 10px" onclick="window.location.href='{{route('admin.send.order.status.email',$order->id)}}'">Send Order Status Email</button>--}}
{{--                @endif--}}
                @if($order->paid == 1 && is_null($order->pushed_to_erp))
                    <button class="btn btn-sm btn-warning" style="float: right;margin-right: 10px" onclick="window.location.href='{{route('manual.push.to.shipstaion',$order->id)}}'">Push To ShipStation</button>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Line Items
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

                        <table class="table table-borderless table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th></th>
                                <th style="width: 10%">Name</th>
{{--                                <th>Fulfilled By</th>--}}
                                <th>Cost</th>
                                <th>Price X Quantity</th>
                                <th>Status</th>
{{--                                <th>Stock Status</th>--}}
{{--                                <th>Selected Warehouse</th>--}}
                            </tr>
                            </thead>
                            <tbody>


                            @foreach($order->line_items as $item)
                                @if($item->fulfilled_by != 'store')
                                    <tr>
                                        <td>
                                            @if($order->custom == 0)
                                                @if($item->linked_variant != null)
                                                    @if($item->linked_variant->is_dropship_variant == 1)
                                                        <img class="img-avatar" src="{{asset('shipping-marks')}}/{{$item->linked_variant->image}}" alt="">
                                                    @else
                                                        <img class="img-avatar"
                                                             @if($item->linked_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                             @else @if($item->linked_variant->has_image->isV == 1)
                                                             @php
                                                                 $path = asset('images/variants').'/'. $item->linked_variant->has_image->image;
                                                                 if(substr_count($path, "https") > 1) {
                                                                     $path_array = explode("variants/",$path);
                                                                     $path = $path_array[1];
                                                                 }

                                                             @endphp
                                                             src="{{ $path }}"
                                                             @else
                                                             src="{{asset('images')}}/{{$item->linked_variant->has_image->image}}"
                                                             @endif
                                                             @endif alt="">
                                                    @endif
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
                                                @elseif($item->linked_dropship_variant != null)
                                                    <img class="img-avatar"
                                                         @if($item->linked_dropship_variant->image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                         @else src="{{asset('shipping-marks')}}/{{$item->linked_dropship_variant->image}}" @endif alt="">
                                                @elseif($item->linked_woocommerce_variant != null)
                                                    <img class="img-avatar"
                                                         @if($item->linked_woocommerce_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                         @else @if($item->linked_woocommerce_variant->has_image->isV == 1) src="{{asset('images/variants')}}/{{$item->linked_woocommerce_variant->has_image->image}}" @else src="{{asset('images')}}/{{$item->linked_woocommerce_variant->has_image->image}}" @endif @endif alt="">
                                                @elseif($item->linked_admin_variant != null)
                                                    <img class="img-avatar"
                                                         @if($item->linked_admin_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                         @else @if($item->linked_admin_variant->has_image->isV == 1) src="{{asset('images/variants')}}/{{$item->linked_admin_variant->has_image->image}}" @else src="{{asset('images')}}/{{$item->linked_admin_variant->has_image->image}}" @endif @endif alt="">
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
                                                    @elseif($item->linked_woocommerce_product != null)
                                                        @if(count($item->linked_woocommerce_product->has_images)>0)
                                                            @if($item->linked_woocommerce_product->has_images[0]->isV == 1)
                                                                <img class="img-avatar img-avatar-variant"
                                                                     src="{{asset('images/variants')}}/{{$item->linked_woocommerce_product->has_images[0]->image}}">
                                                            @else
                                                                <img class="img-avatar img-avatar-variant"
                                                                     src="{{asset('images')}}/{{$item->linked_woocommerce_product->has_images[0]->image}}">
                                                            @endif
                                                        @else
                                                            <img class="img-avatar img-avatar-variant"
                                                                 src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                        @endif
                                                    @elseif($item->linked_admin_product != null)
                                                        @if(count($item->linked_admin_product->has_images)>0)
                                                            @if($item->linked_admin_product->has_images[0]->isV == 1)
                                                                <img class="img-avatar img-avatar-variant"
                                                                     src="{{asset('images/variants')}}/{{$item->linked_admin_product->has_images[0]->image}}">
                                                            @else
                                                                <img class="img-avatar img-avatar-variant"
                                                                     src="{{asset('images')}}/{{$item->linked_admin_product->has_images[0]->image}}">
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
                                            @if($item->linked_product != null && $item->linked_product->linked_product != null)
                                                <a href="{{ route('product.view', $item->linked_product->linked_product->id) }}">{{$item->name}}</a>
                                            @else
                                                {{$item->name}}
                                            @endif
                                        </td>
{{--                                        <td>--}}
{{--                                            @if($item->fulfilled_by == 'store')--}}
{{--                                                <span class="badge badge-danger"> Store</span>--}}
{{--                                            @elseif ($item->fulfilled_by == 'Fantasy')--}}
{{--                                                <span class="badge badge-success"> Awareness Drop Shipping </span>--}}
{{--                                            @else--}}
{{--                                                <span class="badge badge-success"> {{$item->fulfilled_by}} </span>--}}
{{--                                            @endif--}}
{{--                                        </td>--}}

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
{{--                                        <td>--}}
{{--                                            @php--}}
{{--                                                if($order->custom == 0) {--}}
{{--                                                    $out_of_stock = false;--}}
{{--                                                    if($item->linked_variant) {--}}
{{--                                                        if($item->linked_variant->quantity == 0)--}}
{{--                                                            $out_of_stock = true;--}}
{{--                                                    }--}}
{{--                                                    elseif($item->linked_product){--}}
{{--                                                        if($item->linked_product->quantity == 0)--}}
{{--                                                            $out_of_stock = true;--}}
{{--                                                    }--}}
{{--                                                }--}}
{{--                                                else {--}}
{{--                                                    $out_of_stock = false;--}}
{{--                                                    if($item->linked_real_variant) {--}}
{{--                                                        if($item->linked_real_variant->quantity == 0)--}}
{{--                                                            $out_of_stock = true;--}}
{{--                                                    }--}}
{{--                                                    elseif($item->linked_dropship_variant) {--}}
{{--                                                      if($item->linked_dropship_variant->linked_product->quantity == 0)--}}
{{--                                                        $out_of_stock = true;--}}
{{--                                                    }--}}
{{--                                                    elseif($item->linked_woocommerce_variant) {--}}
{{--                                                      if($item->linked_woocommerce_variant->linked_product->quantity == 0)--}}
{{--                                                        $out_of_stock = true;--}}
{{--                                                    }--}}
{{--                                                    elseif($item->linked_admin_variant) {--}}
{{--                                                      if($item->linked_admin_variant->linked_product->quantity == 0)--}}
{{--                                                        $out_of_stock = true;--}}
{{--                                                    }--}}
{{--                                                    elseif($item->linked_real_product){--}}
{{--                                                        if($item->linked_real_product->quantity == 0)--}}
{{--                                                            $out_of_stock = true;--}}
{{--                                                    }--}}
{{--                                                    elseif($item->linked_woocommerce_product) {--}}
{{--                                                      if($item->linked_woocommerce_product->quantity == 0)--}}
{{--                                                        $out_of_stock = true;--}}
{{--                                                    }--}}
{{--                                                    elseif($item->linked_admin_product) {--}}
{{--                                                      if($item->linked_admin_product->quantity == 0)--}}
{{--                                                        $out_of_stock = true;--}}
{{--                                                    }--}}

{{--                                                }--}}
{{--                                            @endphp--}}

{{--                                            @if($order->custom == 0)--}}
{{--                                                @if($out_of_stock || ($item->linked_variant == null && $item->linked_product == null ))--}}
{{--                                                    <span class="badge badge-danger" style="font-size: small"> Out of Stock </span>--}}
{{--                                                @else--}}
{{--                                                    <span class="badge badge-success" style="font-size: small"> In Stock </span>--}}
{{--                                                @endif--}}
{{--                                            @else--}}
{{--                                                @if($out_of_stock || ($item->linked_real_variant == null && $item->linked_real_product == null  && $item->linked_dropship_variant == null && $item->linked_woocommerce_product == null && $item->linked_woocommerce_variant == null && $item->linked_admin_variant == null && $item->linked_admin_product == null))--}}
{{--                                                    <span class="badge badge-danger" style="font-size: small"> Out of Stock </span>--}}
{{--                                                @else--}}
{{--                                                    <span class="badge badge-success" style="font-size: small"> In Stock </span>--}}
{{--                                                @endif--}}
{{--                                            @endif--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            {{ $item->has_warehouse->title }}--}}
{{--                                        </td>--}}
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td colspan="12" class="text-right">

                                    @if($order->getStatus($order) == "unfulfilled" && in_array($order->paid,[1]))
                                        <button class="btn btn-primary" onclick="window.location.href='{{route('admin.order.fulfillment',$order->id)}}'"> Mark as Fulfilled </button>
                                    @endif
                                </td>
                            </tr>

                            </tbody>

                        </table>
                    </div>
                </div>
                @if($order->checkStoreItem($order) > 0)
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                Line Items Can't Fulfilled by Awareness Drop Shipping
                            </h3>

                        </div>
                        <div class="block-content">
                            <table class="table  table-borderless table-striped table-vcenter">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th >Name</th>
                                    <th>Fulfilled By</th>
                                    <th>Price X Quantity</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->line_items as $item)
                                    @if($item->fulfilled_by == 'store')
                                        <tr>
                                            <td>
                                                <img class="img-avatar img-avatar-variant"
                                                     src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                            </td>
                                            <td style="width: 30%">
                                                {{$item->name}}

                                            </td>
                                            <td>
                                                <span class="badge badge-danger"> Store</span>
                                            </td>

                                            <td>{{$item->price}} X {{$item->quantity}}  USD </td>

                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                @endif

                @php
                    $total_shipping = 0;
                    $total_handling_fee = 0;
                    if($total_quantity > 1){
                        $total_handling_fee = (($total_quantity - 1) * 0.5) + 2.5;
                    }else{
                        $total_handling_fee = 2.5;
                    }

                if($total_quantity > 0 && $total_quantity <= 10){
                    $total_shipping =  4.99;
                }elseif ($total_quantity > 10 && $total_quantity <= 20){
                    $total_shipping =  6.99;
                }elseif ($total_quantity > 20 && $total_quantity <= 30){
                    $total_shipping =  8.99;
                }elseif ($total_quantity > 30 && $total_quantity <= 50) {
                     $total_shipping =  10.99;
                }else{
                    $total_shipping =  15.99;
                }
                $usps_rate = $total_shipping;
                @endphp


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
                                    Subtotal ({{count($order->line_items)}} items)
                                </td>
                                <td align="right">
                                    @if($order->custom == 0)
                                        {{number_format($order->total_cost,2)}} USD
                                    @else
                                        {{number_format($order->total_cost,2)}} USD
                                    @endif
{{--                                    {{number_format($order->cost_to_pay - $order->shipping_price,2)}} USD--}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Shipping Price
                                </td>
                                <td align="right">

                                    {{ number_format($total_shipping, 2) . ' USD'}}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Handling Fee
                                </td>
                                <td align="right">
                                    {{ number_format($total_handling_fee, 2) }} USD
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Total Cost
                                </td>
                                <td align="right">

                                    {{number_format($order->total_cost + $total_handling_fee + $total_shipping ,2)}} USD

                                </td>
                            </tr>
                            </tbody>


                        </table>

                    </div>
                </div>
                @if(count($order->fulfillments) >0)
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                Fulfillments
                            </h3>
                            @if($order->status == "fulfilled")

                            @endif
                        </div>
                    </div>

                    @foreach($order->fulfillments as $fulfillment)
                        <div class="block">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">
                                    {{$fulfillment->name}}
                                </h3>

                                <span class="badge badge-primary" style="float: right;font-size: medium"> {{$fulfillment->status}}</span>
                                @if($fulfillment->tracking_number != null)
                                    <button class="btn btn-sm btn-info ml-2" data-toggle="modal" data-target="#edit_tracking_modal{{$fulfillment->id}}"> Edit tracking </button>
                                @endif
                            </div>
                            <div class="block-content">
                                @if($fulfillment->tracking_number != null)
                                    <p style="font-size: 12px">
                                        @if($fulfillment->courier ) Courier Service Provider : {{ $fulfillment->courier->title }} @endif <br>
                                        Tracking Number : {{$fulfillment->tracking_number}} <br>
                                        Tracking Url : {{$fulfillment->tracking_url}} <br>
                                        Tracking Notes : {{$fulfillment->tracking_notes}} <br>

                                    </p>
                                @endif
                                <table class="table table-borderless table-striped table-vcenter">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th >Name</th>
                                        <th>Cost X Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($fulfillment->line_items as $item)
                                        <tr>
                                            <td>
                                                @if($item->linked_line_item != null)
                                                    @if($order->custom == 0)
                                                        @if($item->linked_line_item->linked_variant != null)
                                                            <img class="img-avatar"
                                                                 @if($item->linked_line_item->linked_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                                 @else @if($item->linked_line_item->linked_variant->has_image->isV == 1) src="{{asset('images/variants')}}/{{$item->linked_line_item->linked_variant->has_image->image}}" @else src="{{asset('images')}}/{{$item->linked_line_item->linked_variant->has_image->image}}" @endif @endif alt="">
                                                        @else
                                                            @if($item->linked_line_item->linked_product != null)
                                                                @if(count($item->linked_line_item->linked_product->has_images)>0)
                                                                    @if($item->linked_line_item->linked_product->has_images[0]->isV == 1)
                                                                        <img class="img-avatar img-avatar-variant"
                                                                             src="{{asset('images/variants')}}/{{$item->linked_line_item->linked_product->has_images[0]->image}}">
                                                                    @else
                                                                        <img class="img-avatar img-avatar-variant"
                                                                             src="{{asset('images')}}/{{$item->linked_line_item->linked_product->has_images[0]->image}}">
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
                                                        @if($item->linked_line_item->linked_real_variant != null)
                                                            <img class="img-avatar"
                                                                 @if($item->linked_line_item->linked_real_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                                 @else @if($item->linked_line_item->linked_real_variant->has_image->isV == 1) src="{{asset('images/variants')}}/{{$item->linked_line_item->linked_real_variant->has_image->image}}" @else src="{{asset('images')}}/{{$item->linked_line_item->linked_real_variant->has_image->image}}" @endif @endif alt="">
                                                        @else
                                                            @if($item->linked_line_item->linked_real_product != null)
                                                                @if(count($item->linked_line_item->linked_real_product->has_images)>0)
                                                                    @if($item->linked_line_item->linked_real_product->has_images[0]->isV == 1)
                                                                        <img class="img-avatar img-avatar-variant"
                                                                             src="{{asset('images/variants')}}/{{$item->linked_line_item->linked_real_product->has_images[0]->image}}">
                                                                    @else
                                                                        <img class="img-avatar img-avatar-variant"
                                                                             src="{{asset('images')}}/{{$item->linked_line_item->linked_real_product->has_images[0]->image}}">
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
                                                @else
                                                    <img class="img-avatar img-avatar-variant"
                                                         src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                @endif
                                            </td>
                                            <td style="width: 60%">
                                                @if($item->linked_line_item != null)
                                                    {{$item->linked_line_item->name}}
                                                @else
                                                    {{$item->name}}
                                                @endif
                                            </td>
                                            <td> @if($item->linked_line_item != null)
                                                    {{number_format($item->linked_line_item->cost,2)}}  X {{$item->fulfilled_quantity}}  USD
                                                @else
                                                    {{number_format($item->cost,2)}}  X {{$item->fulfilled_quantity}}  USD
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                    @if($fulfillment->tracking_number == null)
                                        <tr>
                                            <td colspan="12" class="text-right">
                                                <button class="btn btn-sm btn-danger" onclick="window.location.href='{{route('admin.order.fulfillment.cancel',['id'=>$order->id,'fulfillment_id'=>$fulfillment->id])}}'"> Cancel Fulfillment </button>
                                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_tracking_modal{{$fulfillment->id}}"> Add tracking </button>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="modal fade" id="add_tracking_modal{{$fulfillment->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                                <div class="modal-content">
                                    <div class="block block-themed block-transparent mb-0">
                                        <div class="block-header bg-primary-dark">
                                            <h3 class="block-title">Add Tracking to Fulfillment</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <form action="{{route('admin.order.fulfillment.tracking',$order->id)}}" method="post">
                                            @csrf
                                            <div class="block-content">
                                                <input type="hidden" name="fulfillment[]" value="{{$fulfillment->id}}">
                                                <div class="block">
                                                    <div class="block-header block-header-default">
                                                        <h3 class="block-title">
                                                            {{$fulfillment->name}}
                                                        </h3>
                                                    </div>
                                                    <div class="block-content">
                                                        <table class="table table-borderless  table-vcenter">
                                                            <thead>

                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Tracking Number <span style="color: red">*</span></td>
                                                                    <td>
                                                                        <input type="text" required name="tracking_number[]" class="form-control" placeholder="#XXXXXX" >
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Tracking Url <span style="color: red">*</span></td>
                                                                    <td>
                                                                        <input type="url" required name="tracking_url[]" class="form-control" placeholder="https://example/tracking/XXXXX" value="{{ $order->courier_url }}">
                                                                    </td>

                                                                </tr>
                                                                <tr>
                                                                    <td>Tracking Notes</td>
                                                                    <td>
                                                                        <input type="text" name="tracking_notes[]" class="form-control" placeholder="Notes for this fulfillment">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Courier Service Provider</td>
                                                                    <td>
                                                                        <select name="courier_id[]" class="form-control" id="">
                                                                            <option selected value="{{ $order->courier_id }}"> {{ $order->courier_name }}</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="block-content block-content-full text-right border-top">
                                                <button type="submit" class="btn btn-sm btn-primary" >Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="edit_tracking_modal{{$fulfillment->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                                <div class="modal-content">
                                    <div class="block block-themed block-transparent mb-0">
                                        <div class="block-header bg-primary-dark">
                                            <h3 class="block-title">Edit Tracking of Fulfillment</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <form action="{{route('admin.order.edit.fulfillment.tracking',['id'=>$order->id,'fulfillment_id'=>$fulfillment->id])}}" method="post">
                                            @csrf
                                            <div class="block-content">
                                                <div class="block">
                                                    <div class="block-header block-header-default">
                                                        <h3 class="block-title">
                                                            {{$fulfillment->name}}
                                                        </h3>
                                                    </div>
                                                    <div class="block-content">
                                                        <table class="table table-borderless  table-vcenter">
                                                            <thead>

                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>Tracking Number <span style="color: red">*</span></td>
                                                                <td>
                                                                    <input type="text" required name="tracking_number" class="form-control" placeholder="#XXXXXX" value="{{ $fulfillment->tracking_number }}" >
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tracking Url <span style="color: red">*</span></td>
                                                                <td>
                                                                    <input type="url" required name="tracking_url" class="form-control" placeholder="https://example/tracking/XXXXX" value="{{ $fulfillment->tracking_url }}">
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td>Tracking Notes</td>
                                                                <td>
                                                                    <input type="text" name="tracking_notes" class="form-control" placeholder="Notes for this fulfillment" value="{{ $fulfillment->tracking_notes }}">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Courier Service Provider</td>
                                                                <td>
                                                                    <select name="courier_id" class="form-control" id="">
                                                                        <option selected value="{{ $order->courier_id }}"> {{ $order->courier_name }}</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="block-content block-content-full text-right border-top">
                                                <button type="submit" class="btn btn-sm btn-primary" >Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endforeach
                @endif

                <div class="block">

                    <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                        @if($order->has_payment != null)
                            <li class="nav-item">
                                <a class="nav-link active" href="#transaction_history"> Transaction History</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="#order_history">Order History</a>
                        </li>
                    </ul>
                    <div class="block-content tab-content">
                        @if($order->has_payment != null)
                            <div class="tab-pane active" id="transaction_history" role="tabpanel">
                                <div class="block">
                                    <div class="block-content">
                                        <ul class="timeline timeline-alt">
                                            <li class="timeline-event">
                                                <div class="timeline-event-icon bg-success">
                                                    <i class="fa fa-dollar-sign"></i>
                                                </div>
                                                <div class="timeline-event-block block js-appear-enabled animated fadeIn" data-toggle="appear">
                                                    <div class="block-header block-header-default">
                                                        <h3 class="block-title">{{number_format($order->has_payment->amount,2)}} USD</h3>
                                                        <div class="block-options">
                                                            <div class="timeline-event-time block-options-item font-size-sm font-w600">
{{--                                                                {{date_create($order->has_payment->created_at)->format('d M, Y h:i a')}}--}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="block-content">
                                                        @if($order->pay_by == 'Paypal')
                                                            <p> Cost-Payment Captured Via Paypal "{{$order->has_payment->paypal_payment_id}}" by {{$order->has_payment->name}} </p>

                                                        @elseif($order->pay_by == 'Wallet')
                                                            <p> Cost-Payment Captured by Awareness Drop Shipping Wallet  {{--by {{$order->has_payment->name}}--}} </p>


                                                        @else
                                                            <p> Cost-Payment Captured On Card *****{{$order->has_payment->card_last_four}} by {{$order->has_payment->name}} </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="tab-pane" id="order_history" role="tabpanel">
                            @if(count($order->logs) > 0)
                                <div class="block">
                                    <div class="block-content">
                                        <ul class="timeline timeline-alt">
                                            @foreach($order->logs as $log)
                                                <li class="timeline-event">
                                                    @if($log->status == "Newly Synced")
                                                        <div class="timeline-event-icon bg-warning">
                                                            <i class="fa fa-sync"></i>
                                                        </div>
                                                    @elseif($log->status == "paid")
                                                        <div class="timeline-event-icon bg-success">
                                                            <i class="fa fa-dollar-sign"></i>
                                                        </div>
                                                    @elseif($log->status == "Fulfillment")
                                                        <div class="timeline-event-icon bg-primary">
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                    @elseif($log->status == "Fulfillment Cancelled")
                                                        <div class="timeline-event-icon bg-danger">
                                                            <i class="fa fa-ban"></i>
                                                        </div>
                                                    @elseif($log->status == "Tracking Details Added")
                                                        <div class="timeline-event-icon bg-amethyst">
                                                            <i class="fa fa-truck"></i>
                                                        </div>
                                                    @elseif($log->status == "Tracking Details Updated")
                                                        <div class="timeline-event-icon bg-amethyst">
                                                            <i class="fa fa-truck"></i>
                                                        </div>
                                                    @elseif($log->status == "Delivered")
                                                        <div class="timeline-event-icon" style="background: deeppink">
                                                            <i class="fa fa-home"></i>
                                                        </div>
                                                    @elseif($log->status == "Completed")
                                                        <div class="timeline-event-icon" style="background: darkslategray">
                                                            <i class="fa fa-check"></i>
                                                        </div>
                                                    @endif
                                                    <div class="timeline-event-block block js-appear-enabled animated fadeIn" data-toggle="appear">
                                                        <div class="block-header block-header-default">
                                                            <h3 class="block-title">{{$log->status}}</h3>
                                                            <div class="block-options">
                                                                <div class="timeline-event-time block-options-item font-size-sm font-w600">
                                                                    {{date_create($log->created_at)->format('d M, Y h:i a')}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="block-content">
                                                            <p> {{$log->message}} </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @else
                                <p> No Order Logs Found </p>
                            @endif
                        </div>
                    </div>


                </div>

            </div>
            <div class="col-md-3">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Notes
                        </h3>
                    </div>
                    <div class="block-content">
                        @if($order->notes != null)
                            {{$order->notes}}
                        @else
                            <p> No Notes</p>
                        @endif
                    </div>
                </div>
                @if($order->shipping_address != null)
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                Shipping Address
                            </h3>
                        </div>
                        @php
                            $shipping = json_decode($order->shipping_address)
                        @endphp
                        <div class="block-content">
                            @if($shipping != null)
                                <p style="font-size: 14px">{{$shipping->first_name}} {{$shipping->last_name}}
                                    @if(isset($shipping->company))
                                        <br>{{$shipping->company}}
                                    @endif
                                    <br> {{$shipping->address1}}
                                    <br> {{$shipping->address2}}
                                    <br> {{$shipping->city}}
                                    <br> {{$shipping->province}} {{$shipping->zip}}
                                    <br> {{$shipping->country}}
                                    @if(isset($shipping->phone))
                                        <br>{{$shipping->phone}}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>





@endsection
