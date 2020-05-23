@extends('layout.single')
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
                            My Orders
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
        @if($order->status == "delivered")
            <div class="row mb2">
                <div class="col-md-12">
                    <button  onclick="window.location.href='{{route('admin.order.complete',$order->id)}}'" class="btn btn-sm btn-success"  style="float: right"> Mark as Completed </button>
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
                        @elseif($order->status == 'partially-shipped')
                            <span class="badge " style="float: right;font-size: medium;background: darkolivegreen;color: white;"> {{$order->status}}</span>
                        @elseif($order->status == 'shipped')
                            <span class="badge " style="float: right;font-size: medium;background: orange;color: white;"> {{$order->status}}</span>
                        @elseif($order->status == 'delivered')
                            <span class="badge " style="float: right;font-size: medium;background: deeppink;color: white;"> {{$order->status}}</span>
                        @elseif($order->status == 'completed')
                            <span class="badge " style="float: right;font-size: medium;background: darkslategray;color: white;"> {{$order->status}}</span>
                        @else
                            <span class="badge badge-success" style="float: right;font-size: medium"> {{$order->status}}</span>
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
                                        @if($item->linked_variant != null)
                                            <img class="img-avatar"
                                                 @if($item->linked_variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                 @else @if($item->linked_variant->has_image->isV == 1) src="{{asset('images/variants')}}/{{$item->linked_variant->has_image->image}}" @else src="{{asset('images')}}/{{$item->linked_variant->has_image->image}}" @endif @endif alt="">
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
                                        @elseif ($item->fulfilled_by == 'Fantasy')
                                            <span class="badge badge-success"> WeFullFill </span>
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
                                    Cost @if($order->paid == 0) to Pay @else Paid @endif
                                </td>
                                <td align="right">
                                    {{number_format($order->cost_to_pay,2)}} {{$order->currency}}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align="right">
                                    @if($order->paid == 0)
                                        <button class="btn btn-success" data-toggle="modal" data-target="#payment_modal"><i class="fa fa-credit-card"></i> Credit Card Pay</button>

                                        <button class="btn btn-success paypal-pay-button" data-href="{{route('store.order.paypal.pay',$order->id)}}" data-pay=" {{number_format($order->cost_to_pay,2)}} {{$order->currency}}" ><i class="fab fa-paypal"></i> Paypal Pay</button>
                                        <button class="btn btn-success wallet-pay-button" data-href="{{route('store.order.wallet.pay',$order->id)}}" data-pay=" {{number_format($order->cost_to_pay,2)}} {{$order->currency}}" ><i class="fa fa-wallet"></i> Wallet Pay</button>

                                    @endif
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
                                    </tbody>

                                </table>
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
                                                        <h3 class="block-title">{{number_format($order->has_payment->amount,2)}} {{$order->currency}}</h3>
                                                        <div class="block-options">
                                                            <div class="timeline-event-time block-options-item font-size-sm font-w600">
                                                                {{date_create($order->has_payment->created_at)->format('d M, Y h:i a')}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="block-content">
                                                        @if($order->pay_by == 'Paypal')
                                                            <p> Cost-Payment Captured Via Paypal "{{$order->has_payment->paypal_payment_id}}" by {{$order->has_payment->name}} </p>

                                                        @elseif($order->pay_by == 'Wallet')
                                                            <p> Cost-Payment Captured On Wallet  by {{$order->has_payment->name}} </p>

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
                            $shipping = json_decode($order->shipping_address)
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
    @if($order->paid == 0)
        <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
            <div class="modal-dialog modal-dialog-popout" role="document">
                <div class="modal-content">
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Payment for Order <{{$order->name}}></h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option">
                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                </button>
                            </div>
                        </div>
                        <form action="{{route('store.order.proceed.payment')}}" method="post">
                            @csrf
                            <input type="hidden" name="order_id" value="{{$order->id}}">
                            <div class="block-content font-size-sm">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="form-material">
                                            <label for="material-error">Card Name</label>
                                            <input  class="form-control" type="text" required=""  name="card_name"
                                                    placeholder="Enter Card Title here">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="form-material">
                                            <label for="material-error">Card Number</label>
                                            <input type="text" required=""  name="card_number"  class="form-control js-card js-masked-enabled"
                                                   placeholder="9999-9999-9999-9999">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="form-material">
                                            <label for="material-error">Amount to Pay</label>
                                            <input  class="form-control" type="text" readonly value="{{number_format($order->cost_to_pay,2)}} USD"  name="amount"
                                                    placeholder="Enter 14 Digit Card Number here">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="block-content block-content-full text-right border-top">
                                <button type="submit" class="btn btn-success" >Proceed Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif


@endsection
