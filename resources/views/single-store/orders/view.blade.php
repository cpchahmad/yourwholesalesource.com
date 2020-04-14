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
        <div class="row">
            <div class="col-md-9">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Line Items
                        </h3>
                        @if($order->status == 'new')
                            <span class="badge badge-warning" style="float: right;font-size: medium"> {{$order->status}}</span>

                        @elseif($order->status == 'paid')
                            <span class="badge badge-primary" style="float: right;font-size: medium"> Ordered</span>

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

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->line_items as $item)
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

                                </tr>
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
                                        <button class="btn btn-success" data-toggle="modal" data-target="#payment_modal"><i class="fa fa-coins"></i> Pay</button>
                                    @endif
                                </td>
                            </tr>

                            </tbody>


                        </table>

                    </div>
                </div>
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
