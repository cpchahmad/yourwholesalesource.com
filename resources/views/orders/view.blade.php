@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    {{$order->name}}
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
        @if($order->status == "shipped")
            <div class="row mb2">
                <div class="col-md-12">
                    <button class="btn btn-sm btn-success"  style="float: right"> Mark as Delivered </button>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-9">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Line Items
                        </h3>
                        @if($order->status == 'paid')
                            <span class="badge badge-primary" style="float: right;font-size: medium"> {{$order->status}}</span>

                        @elseif($order->status == 'unfulfilled')
                            <span class="badge badge-warning" style="float: right;font-size: medium"> {{$order->status}}</span>

                        @elseif($order->status == 'shipped')
                            <span class="badge " style="float: right;font-size: medium;background: orange;color: white;"> {{$order->status}}</span>
                        @else
                            <span class="badge badge-success" style="float: right;font-size: medium"> {{$order->status}}</span>
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
                                            @if($item->linked_variant != null)
                                                <img class="img-avatar"
                                                     @if($item->linked_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                     @else src="{{asset('images/variants')}}/{{$item->linked_variant->has_image->image}}" @endif alt="">
                                            @else
                                                <img class="img-avatar img-avatar-variant"
                                                     src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                            @endif
                                        </td>
                                        <td>
                                            {{$item->name}}

                                        </td>
                                        <td>
                                            @if($item->fulfilled_by == 'store')
                                                <span class="badge badge-danger"> Store</span>
                                            @else
                                                <span class="badge badge-success"> {{$item->fulfilled_by}} </span>
                                            @endif
                                        </td>

                                        <td>{{number_format($item->cost,2)}}  X {{$item->quantity}}  {{$order->currency}}</td>
                                        <td>{{$item->price}} X {{$item->quantity}}  {{$order->currency}} </td>
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
                            <tr>
                                <td colspan="12" class="text-right">
                                    @if($order->getStatus($order) == "unfulfilled")
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
                                Line Items Can't Fulfilled by WeFullFill
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
                                                @if($item->linked_variant != null)
                                                    <img class="img-avatar"
                                                         @if($item->linked_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                         @else src="{{asset('images/variants')}}/{{$item->linked_variant->has_image->image}}" @endif alt="">
                                                @else
                                                    <img class="img-avatar img-avatar-variant"
                                                         src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                @endif
                                            </td>
                                            <td>
                                                {{$item->name}}

                                            </td>
                                            <td>
                                                <span class="badge badge-danger"> Store</span>
                                            </td>

                                            <td>{{$item->price}} X {{$item->quantity}}  {{$order->currency}} </td>

                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                @endif
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
                                    {{number_format($order->subtotal_price,2)}} {{$order->currency}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Tax
                                </td>
                                <td align="right">
                                    {{number_format($order->total_tax,2)}} {{$order->currency}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Total
                                </td>
                                <td align="right">
                                    {{number_format($order->total_price,2)}} {{$order->currency}}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Cost Paid
                                </td>
                                <td align="right">
                                    {{number_format($order->cost_to_pay,2)}} {{$order->currency}}
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
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_tracking_modal"  style="float: right"> Add tracking </button>
                                <div class="modal fade" id="add_tracking_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
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
                                                    <div class="block-content" style="height: 500px; overflow: auto">
                                                        @foreach($order->fulfillments as $fulfillment)
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
                                                                                <input type="url" required name="tracking_url[]" class="form-control" placeholder="https://example/tracking/XXXXX">
                                                                            </td>

                                                                        </tr>
                                                                        <tr>
                                                                            <td>Tracking Notes</td>
                                                                            <td>
                                                                                <input type="text" name="tracking_notes[]" class="form-control" placeholder="Notes for this fulfillment">
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>

                                                                    </table>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <div class="block-content block-content-full text-right border-top">
                                                        <button type="submit" class="btn btn-sm btn-primary" >Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                            </div>
                            <div class="block-content">
                                @if($order->status == "shipped")
                                    <p style="font-size: 12px"> Tracking Number : {{$fulfillment->tracking_number}} <br>
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
                                                @if($item->linked_line_item->linked_variant != null)
                                                    <img class="img-avatar"
                                                         @if($item->linked_line_item->linked_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                         @else src="{{asset('images/variants')}}/{{$item->linked_line_item->linked_variant->has_image->image}}" @endif alt="">
                                                @else
                                                    <img class="img-avatar img-avatar-variant"
                                                         src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                                @endif
                                            </td>
                                            <td>
                                                {{$item->linked_line_item->name}}
                                            </td>
                                            <td>{{number_format($item->linked_line_item->cost,2)}}  X {{$item->fulfilled_quantity}}  {{$order->currency}}</td>

                                        </tr>
                                    @endforeach
                                    @if($order->status == "fulfilled")
                                        <tr>
                                            <td colspan="12" class="text-right">
                                                <button class="btn btn-sm btn-danger" onclick="window.location.href='{{route('admin.order.fulfillment.cancel',['id'=>$order->id,'fulfillment_id'=>$fulfillment->id])}}'"> Cancel Fulfillment </button>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif
                @if($order->has_payment != null)
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                Transaction History
                            </h3>
                        </div>
                        <div class="block-content">
                            <ul class="timeline timeline-alt">

                                <li class="timeline-event">
                                    <div class="timeline-event-icon bg-success">
                                        <i class="fa fa-dollar-sign"></i>
                                    </div>
                                    <div class="timeline-event-block block js-appear-enabled animated fadeIn" data-toggle="appear">
                                        <div class="block-header block-header-default">
                                            <h3 class="block-title">{{number_format($order->has_payment->amount,2)}} {{$order->currency}}</h3>
                                            <div class="block-options">
                                                <div class="timeline-event-time block-options-item font-size-sm font-w600">
                                                    {{date_create($order->has_payment->created_at)->format('d M, Y h:i a')}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <p> Cost-Payment Captured On Card *****{{$order->has_payment->card_last_four}} by {{$order->has_payment->name}} </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif

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
                @if($order->customer != null)
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                Customer
                            </h3>

                        </div>
                        @php
                            $customer = json_decode($order->customer);
                            $billing = json_decode($order->billing_address);
                            $shipping = json_decode($order->shipping_address);
                        @endphp
                        <div class="block-content">
                            <p style="font-size: 14px">{{$customer->first_name}} {{$customer->last_name}} <br>{{$customer->orders_count}} Orders</p>
                            <hr>
                            <h6>Customer Information</h6>
                            <p style="font-size: 14px">{{$customer->email}}<br>{{$customer->phone}}</p>
                            @if($billing != null)
                                <hr>
                                <h6>Billing Address</h6>
                                <p style="font-size: 14px">{{$billing->first_name}} {{$billing->last_name}} <br> {{$billing->company}}
                                    <br> {{$billing->address1}}
                                    <br> {{$billing->address2}}
                                    <br> {{$billing->city}}
                                    <br> {{$billing->province}} {{$billing->zip}}
                                    <br> {{$billing->country}}
                                    <br> {{$billing->phone}}
                                </p>
                            @endif
                            @if($shipping != null)
                                <hr>
                                <h6>Shipping Address</h6>
                                <p style="font-size: 14px">{{$shipping->first_name}} {{$shipping->last_name}}
                                    <br> {{$shipping->company}}
                                    <br> {{$shipping->address1}}
                                    <br> {{$shipping->address2}}
                                    <br> {{$shipping->city}}
                                    <br> {{$shipping->province}} {{$shipping->zip}}
                                    <br> {{$shipping->country}}
                                    <br> {{$shipping->phone}}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


@endsection
