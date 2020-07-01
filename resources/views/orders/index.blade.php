@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    All Orders
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">All Orders</a>
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
                                    <th>Shop / User</th>
                                    <th>Source</th>
                                    <th>Order Date</th>
                                    <th>Price</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>

                                @foreach($orders as $index => $order)
                                    <tbody class="">
                                    <tr>

                                        <td class="font-w600"><a href="{{route('admin.order.view',$order->id)}}">{{ $order->name }}</a></td>
                                        <td>
                                            @if($order->custom == 0)
                                                @if($order->has_store != null)
                                                    <span class="badge badge-primary" style="font-size: 12px"> {{explode('.',$order->has_store->shopify_domain)[0]}}</span>
                                                @else
                                                    <span class="badge badge-warning" style="font-size: 12px"> Manual </span>
                                                @endif
                                            @else
                                                <span class="badge badge-primary" style="font-size: 12px"> {{$order->has_user->email}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($order->custom == 1)
                                                <span class="badge badge-primary" style="font-size: 12px"> Custom </span>
                                            @else
                                                <span class="badge badge-warning" style="font-size: 12px"> Shopify </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{date_create($order->shopify_created_at)->format('D m, Y h:i a') }}
                                        </td>

                                        <td>
                                            {{number_format($order->cost_to_pay,2)}} {{$order->currency}}
                                        </td>
                                        <td>
                                            @if($order->paid == '0')
                                                <span class="badge badge-warning" style="font-size: small"> Unpaid </span>
                                            @elseif($order->paid == '1')
                                                <span class="badge badge-success" style="font-size: small"> Paid </span>
                                            @elseif($order->paid == '2')
                                                <span class="badge badge-danger" style="font-size: small;"> Refunded</span>
                                            @endif

                                        </td>

                                        <td>
                                            @if($order->status == 'Paid')
                                                <span class="badge badge-primary" style="float: right;font-size: medium"> Pending</span>
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

                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a href="{{route('admin.order.view',$order->id)}}"
                                                   class="btn btn-sm btn-success" type="button" data-toggle="tooltip" title=""
                                                   data-original-title="View Order"><i class="fa fa-eye"></i></a>
                                            </div>

                                        </td>

                                    </tr>
                                    </tbody>

                                @endforeach
                            </table>
                        @else
                            <p>No Orders Found</p>
                        @endif
                            <div class="row">
                                <div class="col-md-12 text-center" style="font-size: 17px">
                                    {!! $orders->links() !!}
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
