@extends('layout.single')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Orders
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">My Orders</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <form class="js-form-icon-search push" action="" method="get">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Search by Order ID" value="{{$search}}" name="search" required >
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        <a class="btn btn-danger" href=""> <i class="fa fa-times"></i> Clear </a>

                    </div>
                </div>
            </div>
        </form>


        <div class="row" >
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content">
                        @if (count($orders) > 0)
                            <table class="table table-hover table-borderless table-striped table-vcenter">
                                <thead>
                                <tr>

                                    <th>Name</th>
                                    <th>Order Date</th>
                                    <th>Price</th>
                                    <th>Cost</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th style="text-align: right">
                                        <a href="{{route('store.sync.orders')}}"
                                             class="btn btn-sm btn-primary" style="font-size: 12px" type="button" data-toggle="tooltip" title=""
                                             data-original-title="Sync Orders"><i class="fa fa-sync"></i> Sync New Orders</a></th>
                                </tr>
                                </thead>

                                @foreach($orders as $index => $order)
                                    <tbody class="">
                                    <tr>

                                        <td class="font-w600"><a href="{{route('store.order.view',$order->id)}}">{{ $order->name }}</a></td>
                                        <td>
                                            {{date_create($order->shopify_created_at)->format('D m, Y h:i a') }}
                                        </td>

                                        <td>
                                            {{number_format($order->total_price,2)}} {{$order->currency}}
                                        </td>
                                        <td>
                                            {{number_format($order->cost_to_pay,2)}} {{$order->currency}}

                                        </td>
                                        <td>
                                            @if($order->paid == '0')
                                                <span class="badge badge-warning" style="font-size: small"> unpaid </span>
                                            @elseif($order->paid == '1')
                                                <span class="badge badge-success" style="font-size: small"> paid </span>
                                            @elseif($order->paid == '2')
                                                <span class="badge badge-danger" style="font-size: small;"> refunded</span>
                                            @endif

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
                                            @else
                                                <span class="badge badge-success" style="font-size: small"> {{$order->status}}</span>
                                            @endif

                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a href="{{route('store.order.view',$order->id)}}"
                                                   class="btn btn-sm btn-success" type="button" data-toggle="tooltip" title=""
                                                   data-original-title="View Order"><i class="fa fa-eye"></i></a>
                                                <a href="{{route('store.order.delete',$order->id)}}"
                                                   class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title=""
                                                   data-original-title="Delete Order"><i class="fa fa-times"></i></a>
                                            </div>

                                        </td>

                                    </tr>
                                    </tbody>

                                @endforeach
                            </table>
                        @else
                            <p>No Orders Found <a href="{{route('store.sync.orders')}}"
                                                  class="btn btn-sm btn-primary" style="font-size: 12px;float: right" type="button" data-toggle="tooltip" title=""
                                                  data-original-title="Sync Orders"><i class="fa fa-sync"></i> Sync New Orders</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
