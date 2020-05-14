@extends('layout.shopify')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                   Bulk Import Orders
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">  Bulk Import Orders</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="row" >
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content">
                        @if (count($orders) > 0)
                            <table class="table js-table-sections table-hover table-borderless table-striped table-vcenter">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Order Date</th>
                                    <th>Cost</th>
                                    <th>Status</th>
                                    <th style="text-align: right"></th>
                                </tr>
                                </thead>

                                @foreach($orders as $index => $order)
                                    <tbody class="js-table-sections-header">
                                    <tr>
                                        <td class="text-center">
                                            <i class="fa fa-angle-right text-muted"></i>
                                        </td>
                                        <td>{{$index+1}}</td>
                                        <td class="font-w600"><a href="{{route('users.order.view',$order->id)}}">{{ $order->name }}</a></td>
                                        <td>
                                            {{date_create($order->shopify_created_at)->format('D m, Y h:i a') }}
                                        </td>
                                        <td>
                                            {{number_format($order->cost_to_pay,2)}} {{$order->currency}}

                                        </td>
                                        <td>
                                            @if($order->status == 'paid')
                                                <span class="badge badge-primary" style="float: right;font-size: medium"> {{$order->status}}</span>

                                            @elseif($order->status == 'unfulfilled')
                                                <span class="badge badge-warning" style="font-size: small"> {{$order->status}}</span>
                                            @elseif($order->status == 'partially-shipped')
                                                <span class="badge " style="font-size: small;background: darkolivegreen;color: white;"> {{$order->status}}</span>
                                            @elseif($order->status == 'shipped')
                                                <span class="badge " style="font-size: small;background: orange;color: white;"> {{$order->status}}</span>
                                            @elseif($order->status == 'delivered')
                                                <span class="badge " style="font-size: small;background: deeppink;color: white;"> {{$order->status}}</span>
                                            @elseif($order->status == 'completed')
                                                <span class="badge " style="font-size: small;background: darkslategray;color: white;"> {{$order->status}}</span>
                                            @elseif($order->status == 'new')
                                                <span class="badge badge-warning" style="font-size: small"> Draft </span>
                                            @else
                                                <span class="badge badge-success" style="font-size: small">  {{$order->status}} </span>
                                            @endif

                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a href="{{route('users.order.view',$order->id)}}"
                                                   class="btn btn-sm btn-success" type="button" data-toggle="tooltip" title=""
                                                   data-original-title="View Order"><i class="fa fa-eye"></i></a>
                                                <a href="{{route('users.order.delete',$order->id)}}"
                                                   class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title=""
                                                   data-original-title="Delete Order"><i class="fa fa-times"></i></a>
                                            </div>

                                        </td>

                                    </tr>
                                    </tbody>
                                    <tbody>
                                    @foreach($order->line_items as $item)
                                        @if($item->fulfilled_by != 'store')
                                            <tr>
                                                <td class="text-center">

                                                </td>
                                                <td>
                                                    @if($item->linked_real_variant != null)
                                                        <img class="img-avatar"
                                                             @if($item->linked_real_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                             @else @if($item->linked_real_variant->has_image->isV == 1) src="{{asset('images/variants')}}/{{$item->linked_real_variant->has_image->image}}" @else src="{{asset('images')}}/{{$item->linked_real_variant->has_image->image}}" @endif @endif alt="">
                                                    @else
                                                        <img class="img-avatar img-avatar-variant"
                                                             src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$item->name}}

                                                </td>
                                                <td>
                                                    Fulfilled By:
                                                    @if($item->fulfilled_by == 'store')
                                                        <span class="badge badge-danger"> Store</span>
                                                    @elseif ($item->fulfilled_by == 'Fantasy')
                                                        <span class="badge badge-success"> WeFullFill </span>
                                                    @else
                                                        <span class="badge badge-success"> {{$item->fulfilled_by}} </span>
                                                    @endif
                                                </td>

                                                <td>{{number_format($item->cost,2)}}  X {{$item->quantity}}  {{$order->currency}}</td>

                                                <td>
                                                    @if($item->fulfillment_status == null)
                                                        <span class="badge badge-warning"> Unfulfilled</span>
                                                    @elseif($item->fulfillment_status == 'partially-fulfilled')
                                                        <span class="badge badge-danger"> Partially Fulfilled</span>
                                                    @else
                                                        <span class="badge badge-success"> Fulfilled</span>
                                                    @endif
                                                </td>
                                                <td></td>

                                            </tr>
                                        @endif
                                    @endforeach

                                    </tbody>
                                @endforeach
                            </table>
                        @else
                            <p>No Orders Found </p>
                        @endif
                    </div>
                </div>
                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title"> Unprocessed Data </h5>
                    </div>
                    <div class="block-content">
                        @if (count($data) > 0)
                            <table class="table table-hover table-borderless table-striped table-vcenter">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Id</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                </tr>
                                </thead>

                                @foreach($data as $index => $item)
                                    <tbody class="">
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{$item->order_number}}</td>
                                        <td>{{$item->sku}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->email}}</td>
                                        <td>{{$item->country}}</td>
                                    </tr>
                                    </tbody>

                                @endforeach
                            </table>
                        @else
                            <p>No Unprocessed Data Found </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
