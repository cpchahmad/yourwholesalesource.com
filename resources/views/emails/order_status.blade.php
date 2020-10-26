<!DOCTYPE html>
<html>
<head>
    <title>Order Status</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{now()}}"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body style="margin: 0">
<style>
    .email-body
    {
        color:black;
    }
    .email-content
    {
        /*max-width: 450px;*/
        width : 90%;
    }
    .email-content-detail
    {
        margin: 50px 0;
    }
    @media (max-width: 570px) {
        .email_btn
        {
            padding:15px 30px !important;
            font-size:18px !important;
        }
    }
    @media (max-width: 430px) {
        .email_btn {
            padding: 15px 20px !important;
            font-size: 12px !important;
        }
    }
    @media (max-width: 400px) {
        .email_btn {
            padding: 15px 10px !important;
            font-size: 12px !important;
        }
        span
        {
            font-size:18px !important ;
        }
    }
</style>

{{--<div class="email-body" style="padding: 20px;max-width: 700px;margin: auto; font-family: DIN Next,sans-serif;">--}}
{{--    <div class="email-contaner" style="border: 4px solid #7daa40;padding: 25px;">--}}
{{--        <div class="email-content" style=" max-width: 450px;  margin: auto;  text-align: center; ">--}}
{{--            <div class="email-logo">--}}

{{--                <img src="https://cdn.shopify.com/s/files/1/0370/7361/7029/files/image_3.png?v=1585895317" alt="Wefullfill" style="width: 50%">--}}

{{--            </div>--}}
{{--            <div class="email-content-detail" style="margin: 50px 0;">--}}
{{--                <h1 class="email-title" style="margin: 0;margin-bottom: 30px;font-size: 34px;">An Order is being placed on wefulfill</h1>--}}
{{--                <p class="email-message-1" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53;" >Hey {{ $user->name }}, an order is being placed {{ $retail_order->name }} </p>--}}

{{--                <a href="{{ route('store.order.view', $retail_order->id) }}" target="_blank" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">View Details</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="email-body" style="padding: 20px;max-width: 80%;margin: auto; font-family: DIN Next,sans-serif;">
    <div class="email-contaner" style="border: 2px solid #7daa40;padding: 25px;">
        <div class="email-content" style="margin: auto;  text-align: center; ">
            <div class="email-logo">
                <img src="https://cdn.shopify.com/s/files/1/0370/7361/7029/files/image_3.png?v=1585895317" alt="Wefullfill" style="width: 35%">
            </div>

            <div class="email-content-detail" style="margin: 50px 0;">
                <h1 class="email-title" style="margin: 0;margin-bottom: 30px;font-size: 34px;">{{ $template->subject }}</h1>
                <p class="email-message-1" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53;" >{{ $template->body }} </p>
                <hr>
                <div class="order-details text-left">
                    <div class="row">
                        <div class="col-md-12">
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
                                                                     @else @if($item->linked_variant->has_image->isV == 1)
                                                                     src="{{asset('images/variants')}}/{{$item->linked_variant->has_image->image}}"
                                                                     @else
                                                                     src="{{asset('images')}}/{{$item->linked_variant->has_image->image}}"
                                                                     @endif
                                                                     @endif alt="">
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
                                                {{number_format($order->cost_to_pay - $order->shipping_price,2)}} USD
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Shipping Price
                                            </td>
                                            <td align="right">
                                                {{number_format($order->shipping_price,2)}} USD
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Total Cost
                                            </td>
                                            <td align="right">
                                                {{number_format($order->cost_to_pay,2)}} USD
                                            </td>
                                        </tr>
                                        </tbody>


                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('store.order.view', $order->id) }}" target="_blank" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">View Details</a>
            </div>
        </div>
    </div>
</div>



<div class="email-footer" style=" padding: 25px 10px; color: white; ">

    <div class="email-footer-caption">
        <ul style=" color: white; list-style: none; padding: 0 ;  margin-top: 25px;text-align: center; ">
            <li class="site-name" style="width: max-content; display: inline-block; margin-right: 15px;padding-right: 15px;border-right: 1px solid white;"><a href="" style="color: white;text-decoration: none;">WeFullFill</a></li>
            <li class="dalls" style="width: max-content; display: inline-block; margin-right: 15px; padding-right: 15px; border-right: 1px solid white;">ROOM 2103 TUNG CHIU COMMERCIAL CENTRE 193,LOCKHART ROAD WAN</li>
            <li class="country" style="width: max-content; display: inline-block;">China</li>
        </ul>
    </div>

</div>

</body>
</html>
