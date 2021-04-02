@extends('layout.shopify')
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
        <div class="row" style="margin-bottom: 20px">
            <div class="col-md-12">
                <button  onclick="window.location.href='{{route('app.order.download',$order->id)}}'" class="btn btn-sm btn-primary"  style="float: right;margin-right: 10px"> Download CSV </button>
                @if($order->status == "delivered")
                    <button  onclick="window.location.href='{{route('admin.order.complete',$order->id)}}'" class="btn btn-sm btn-success"  style="float: right;margin-right: 10px"> Mark as Completed </button>
                @endif
                @if($order->paid == 0 && $order->status != 'cancelled')
                    <button class="btn btn-sm btn-danger" style="float: right;margin-right: 10px" onclick="window.location.href='{{route('app.order.cancel',$order->id)}}'">Cancel Order</button>
                @endif
                @if($order->paid == 1)
                    <button class="btn btn-sm btn-primary" style="float: right;margin-right: 10px" data-target="#create_refund_modal" data-toggle="modal">Generate Refund</button>
                    <div class="modal fade" id="create_refund_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                            <div class="modal-content">
                                <div class="block block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title">Generate Refund</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option">
                                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <form action="{{route('refund.create')}}" method="post"  enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="source" value="non-shopify-user">
                                        <input type="hidden" name="manager_id" value="{{$user->sale_manager_id}}">
                                        <input type="hidden" name="user_id" value="{{$user->id}}">
                                        <input type="hidden" name="type" value="user-ticket">

                                        <div class="block-content font-size-sm">
                                            <div class="text-center text-danger font-w600">
                                                An amount of $2.50 restocking fee will be applied on all returns
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="form-material">
                                                        <label for="material-error">Refund Title</label>
                                                        <input required class="form-control" type="text"  name="title"
                                                               placeholder="Enter Title here">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="form-material">
                                                        <label for="material-error">Order</label>
                                                        <select name="order_id" class="form-control" required>
                                                            <option value="{{$order->id}}">{{$order->name}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="form-material">
                                                        <label for="material-error">Priority</label>
                                                        <select name="priority" class="form-control" required>
                                                            <option value="low">Low</option>
                                                            <option value="medium">Medium</option>
                                                            <option value="high">High</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="form-material">
                                                        <label for="material-error">Attachments </label>
                                                        <input type="file" name="attachments[]" class="form-control" multiple>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="form-material">
                                                        <label for="material-error">Reason</label>
                                                        <textarea required class="js-summernote" name="message"
                                                                  placeholder="Please Enter Description here !"></textarea>
                                                    </div>
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
                @endif
                <button style="float: right;margin-bottom: 10px" class="btn btn-sm btn-primary mr-2" data-target="#create_new_ticket" data-toggle="modal">Create New Ticket</button>
                @php
                    $categories = \App\TicketCategory::all();
                @endphp
                <div class="modal fade" id="create_new_ticket" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">New Ticket</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                        </button>
                                    </div>
                                </div>
                                <form action="{{route('help-center.ticket.create')}}" method="post"  enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="source" value="non-shopify-user">
                                    <input type="hidden" name="manager_id" value="{{$user->sale_manager_id}}">
                                    <input type="hidden" name="user_id" value="{{$user->id}}">
                                    <input type="hidden" name="type" value="user-ticket">
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">


                                    <div class="block-content font-size-sm">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material">
                                                    <label for="material-error">Ticket Subject</label>
                                                    <input required class="form-control" type="text"  name="title"
                                                           placeholder="Enter Title here">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material">
                                                    <label for="material-error">Email</label>
                                                    <input required class="form-control" type="text"  name="email"
                                                           value="{{$user->email}}"  placeholder="Enter Email here">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material">
                                                    <label for="material-error">Priority</label>
                                                    <select name="priority" class="form-control" required>
                                                        <option value="low">Low</option>
                                                        <option value="medium">Medium</option>
                                                        <option value="high">High</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material">
                                                    <label for="material-error">Ticket Category</label>
                                                    <select name="category" class="form-control" required>
                                                        <option value="default">Default</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material">
                                                    <label for="material-error">Attachments </label>
                                                    <input type="file" name="attachments[]" class="form-control" multiple>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-material">
                                                    <label for="material-error">Message</label>
                                                    <textarea required class="js-summernote" name="message"
                                                              placeholder="Please Enter Description here !"></textarea>
                                                </div>
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
                            <span class="badge badge-warning" style="font-size: small"> Unfulfilled </span>
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
                                <th>Stock Status</th>
                                <th style="width: 25%;">@if($order->paid == 0)Select Warehouse @else Selected Warehouse @endif</th>
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
                                        <td style="width: 30%;">
                                            {{$item->name}}

                                        </td>
                                        <td>
                                            @if($item->fulfilled_by == 'store')
                                                <span class="badge badge-danger"> Store</span>
                                            @elseif ($item->fulfilled_by == 'Fantasy')
                                                <span class="badge badge-success"> YourWholesaleSource </span>
                                            @else
                                                <span class="badge badge-success"> {{$item->fulfilled_by}} </span>
                                            @endif
                                        </td>

                                        <td>{{number_format($item->cost,2)}}  X {{$item->quantity}} USD</td>

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
                                        <td>
                                            @php
                                                $out_of_stock = false;
                                                if($item->linked_real_variant) {
                                                    if($item->linked_real_variant->quantity == 0)
                                                        $out_of_stock = true;
                                                }
                                                elseif($item->linked_dropship_variant) {
                                                      if($item->linked_dropship_variant->linked_product->quantity == 0)
                                                        $out_of_stock = true;
                                                }
                                                elseif($item->linked_woocommerce_variant) {
                                                      if($item->linked_woocommerce_variant->linked_product->quantity == 0)
                                                        $out_of_stock = true;
                                                }
                                                elseif($item->linked_admin_variant) {
                                                      if($item->linked_admin_variant->linked_product->quantity == 0)
                                                        $out_of_stock = true;
                                                }
                                                elseif($item->linked_real_product){
                                                    if($item->linked_real_product->quantity == 0)
                                                        $out_of_stock = true;
                                                }
                                                elseif($item->linked_woocommerce_product) {
                                                      if($item->linked_woocommerce_product->quantity == 0)
                                                        $out_of_stock = true;
                                                }
                                                elseif($item->linked_admin_product) {
                                                      if($item->linked_admin_product->quantity == 0)
                                                        $out_of_stock = true;
                                                }
                                            @endphp

                                            @if($out_of_stock || ($item->linked_real_variant == null && $item->linked_real_product == null  && $item->linked_dropship_variant == null && $item->linked_woocommerce_product == null && $item->linked_woocommerce_variant == null && $item->linked_admin_variant == null && $item->linked_admin_product == null))
                                                <span class="badge badge-danger" style="font-size: small"> Out of Stock </span>
                                            @else
                                                <span class="badge badge-success" style="font-size: small"> In Stock </span>
                                            @endif
                                        </td>
                                        @if($order->paid == 0 && $item->has_associated_non_shopify_warehouse())
                                            <td>
                                                <select name="warehouse" id="" class="form-control warehouse-selector">
                                                    @foreach($item->has_associated_non_shopify_warehouse() as $warehouse_inventory)
                                                        @php
                                                            if($item->linked_real_product)
                                                               $admin_product_id =  $item->linked_real_product->id;
                                                            else
                                                               $admin_product_id = $item->linked_woocommerce_product->id;
                                                        @endphp
                                                        <option  @if($warehouse_inventory->warehouse_id == 3) selected @endif type="text" value="{{ $warehouse_inventory->warehouse->id .','. $admin_product_id . ','. $order->id . ',' . $item->id }}" >{{ $warehouse_inventory->warehouse->title }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @else
                                            <td>
                                                {{ $item->has_warehouse->title }}
                                            </td>
                                        @endif
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
                                Line Items Can't Fulfilled by YourWholesaleSource
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
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Summary
                        </h3>
                    </div>
                    <div class="block-content">
                        @if($order->isShippable())
                        <table class="table table-borderless table-vcenter">
                            <thead>
                            </thead>
                            <tbody class="js-warehouse-shipping">
                            <tr>
                                <td>
                                    Subtotal ({{count($order->line_items)}} items)
                                </td>
                                <td align="right">
                                    {{number_format($order->total_cost,2)}} USD
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Shipping Price
                                </td>
                                <td align="right">
                                    @php
                                        $usps_rate = $order->usps_shipping;
                                    @endphp
                                    {{ $usps_rate == 0 ? 'The Address is not Valid' : $usps_rate . 'USD'}}
                                    {{-- {{ $order->shipping_rate_for_non_shopify }} USD--}}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Handling Fee
                                </td>
                                <td align="right">
                                    {{ $order->handling_fee }} USD
                                </td>
                            </tr>


                            <tr>
                                <td>
                                    Total Cost @if($order->paid == 0) to Pay @endif
                                </td>
                                <td align="right">
                                    {{number_format($order->total_cost + $usps_rate + $order->handling_fee  ,2)}} USD
                                </td>
                            </tr>
                            <tr>
                                <td></td>

                                @if($usps_rate !== 0)
                                    <td align="right">
                                        @if($order->paid == 0)
                                            <button class="btn btn-success" data-toggle="modal" data-target="#payment_modal"><i class="fa fa-credit-card"></i> Credit Card Pay</button>
                                            <button class="btn btn-success paypal-pay-button" data-toggle="modal" data-target="#paypal_pay_trigger" data-href="{{route('store.order.paypal.pay',$order->id)}}" data-percentage="{{$settings->paypal_percentage}}" data-fee="{{number_format($order->total_cost + $usps_rate + $order->handling_fee  *$settings->paypal_percentage/100,2)}}" data-subtotal="{{number_format($order->total_cost + $order->shipping_rate_for_non_shopify,2)}}" data-pay=" {{number_format(($order->total_cost + $order->shipping_rate_for_non_shopify)+(($order->total_cost + $order->shipping_rate_for_non_shopify)*$settings->paypal_percentage/100),2)}} USD" ><i class="fab fa-paypal"></i> Paypal Pay</button>
                                            <button class="btn btn-success wallet-pay-button" data-href="{{route('store.order.wallet.pay',$order->id)}}" data-pay=" {{ number_format($order->total_cost + $usps_rate + $order->handling_fee  , 2) }}" ><i class="fa fa-wallet"></i> Wallet Pay</button>

                                            <div class="modal" id="paypal_pay_trigger" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="block block-rounded block-themed block-transparent mb-0">
                                                            <div class="block-content cst_content_wrapper font-size-sm text-center">
                                                                <h2>Are your sure?</h2>
                                                                <div class="text-center"> <p>
                                                                        Subtotal: {{number_format($order->total_cost + $usps_rate + $order->handling_fee  ,2)}} USD
                                                                        <br>
                                                                        YourWholesaleSource Paypal Fee ({{$settings->paypal_percentage}}%): {{number_format($order->total_cost + $usps_rate + $order->handling_fee  *$settings->paypal_percentage/100,2)}} USD
                                                                        <br>Total Cost : {{ number_format(number_format($order->total_cost + $usps_rate + $order->handling_fee,2) + number_format($order->total_cost + $usps_rate + $order->handling_fee*$settings->paypal_percentage/100,2) ,2) }} USD</p>
                                                                </div>
                                                                <p> A amount of  {{ number_format(number_format($order->total_cost + $usps_rate + $order->handling_fee,2) + number_format($order->total_cost + $usps_rate + $order->handling_fee*$settings->paypal_percentage/100,2) ,2) }} USD will be deducted through your Paypal Account</p>

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

                                        @endif
                                    </td>
                                @else
                                    <td align="right">
                                        <button class="btn btn-success" data-toggle="modal" data-target="#order_address_update_modal">Edit Shipping Address</button>
                                        <div class="modal" id="order_address_update_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="block-header bg-primary-dark text-left">
                                                        <h3 class="block-title text-white">Edit Shipping Address</h3>
                                                        <div class="block-options">
                                                            <button type="button" class="btn-block-option">
                                                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @php
                                                        $shipping = json_decode($order->shipping_address)
                                                    @endphp
                                                    <form action="{{ route('store.order.address.update', $order->id) }}" method="POST">
                                                        @csrf
                                                        <div class="row text-left p-3">
                                                            <div class="col-md-6 mb2">
                                                                <label>First Name</label>
                                                                <input type="text" class="form-control" value="{{$shipping->first_name}}" name="first_name"
                                                                       value=""  placeholder="" required>
                                                            </div>
                                                            <div class="col-md-6 mb2">
                                                                <label>Last Name</label>
                                                                <input type="text" class="form-control" name="last_name"
                                                                       value="{{ $shipping->last_name }}"  placeholder="" required>
                                                            </div>
                                                            <div class="col-md-12 mb2">
                                                                <label>Address</label>
                                                                <input type="text" class="form-control" name="address1"
                                                                       value="{{ $shipping->address1 }}"  placeholder="" required>
                                                            </div>
                                                            <div class="col-md-12 mb2">
                                                                <label>Street (optional)</label>
                                                                <input type="text" class="form-control" name="address2"
                                                                       value="{{ $shipping->address2 }}"  placeholder="" >
                                                            </div>
                                                            <div class="col-md-6 mb2">
                                                                <label>City</label>
                                                                <input type="text" class="form-control" name="city"
                                                                       value="{{ $shipping->city }}"  placeholder="" required>
                                                            </div>
                                                            <div class="col-md-6 mb2">
                                                                <label>Province</label>
                                                                <input type="text" class="form-control" name="province"
                                                                       value="{{ $shipping->province }}"  placeholder="" required>
                                                            </div>
                                                            <div class="col-md-6 mb2">
                                                                <label>Province Code</label>
                                                                <input type="text" class="form-control" name="province_code"
                                                                       value="{{ isset($shipping->province_code) ?? $shipping->province_code }}"  placeholder="" required>
                                                            </div>
                                                            <div class="col-md-6 mb2">
                                                                <label>Zip Code</label>
                                                                <input type="text" class="form-control" name="zip"
                                                                       value="{{ $shipping->zip }}"  placeholder="" required>
                                                            </div>
                                                            <div class="col-md-12 mb2">
                                                                <label>Country</label>
                                                                <select name="country" required class="form-control">
                                                                    <option value="">Select Country</option>
                                                                    @foreach($countries as $country)
                                                                        <option value="{{$country->name}}"
                                                                                @if($country->name == $shipping->country)
                                                                                    selected
                                                                                @endif
                                                                        >
                                                                            {{$country->name}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 mb2">
                                                                <label>Phone</label>
                                                                <input type="text" required class="form-control" name="phone"
                                                                       value="{{ isset($shipping->phone) ?? $shipping->phone }}"  placeholder="" >
                                                            </div>

                                                            <div class="block-content block-content-full text-right border-top">

                                                                <button type="submit" class="btn btn-sm btn-success">Save</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>

                            </tbody>


                        </table>
                        @else
                            <table class="table table-borderless table-vcenter">
                                <thead>
                                </thead>
                                <tbody class="js-warehouse-shipping">
                                <tr class="text-center p-2 shipping-error">
                                    Sorry, the following shipping country is not availble in default warehouse. Please contact support
                                </tr>
                                </tbody>
                            </table>
                        @endif
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
                                @if($fulfillment->tracking_number != null)
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
                                <a class="nav-link " href="#transaction_history"> Transaction History</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link active" href="#order_history">Order History</a>
                        </li>
                    </ul>
                    <div class="block-content tab-content">
                        @if($order->has_payment != null)
                            <div class="tab-pane " id="transaction_history" role="tabpanel">
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
                                                            <p> Cost-Payment Captured by YourWholesaleSource Wallet  {{--by {{$order->has_payment->name}}--}} </p>

                                                        @else
                                                            <p> Cost-Payment Captured On Credit Card  </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="tab-pane active" id="order_history" role="tabpanel">
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
                                                            <h3 class="block-title">   @if($log->status == "Newly Synced") Created @else {{$log->status}} @endif</h3>
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
                @if($order->has_customer != null)
                    <div class="block">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">
                                Customer
                            </h3>

                        </div>
                        @php
                            $customer = $order->has_customer;

                            $shipping = json_decode($order->shipping_address)
                        @endphp
                        <div class="block-content">
                            <p style="font-size: 14px">{{$customer->first_name}} {{$customer->last_name}} <br>{{$customer->orders_count}} Orders</p>
                            <hr>
                            <h6>Customer Information</h6>
                            <p style="font-size: 14px">{{$customer->email}}<br>{{$customer->phone}}</p>

                            @if($shipping != null)
                                <hr>
                                <h6>Shipping Address</h6>
                                <p style="font-size: 14px">{{$shipping->first_name}} {{$shipping->last_name}}
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
                        <div class="block-content">
                            <form
                                role="form"
                                action="{{ route('stripe.process.payment') }}"
                                method="post"
                                class="require-validation"
                                data-cc-on-file="false"
                                data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                                id="payment-form">
                                @csrf
                                <input type="hidden" name="amount_to_be_paid" value="{{ number_format($order->total_cost + $usps_rate + $order->handling_fee, 2) }}">
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <div>
                                    <div class='form-group required'>
                                        <label class='control-label'>Name on Card</label>
                                        <input
                                            class='form-control' size='4' type='text'>
                                    </div>
                                </div>
                                <div class=''>
                                    <div class='form-group card required'>
                                        <label class='control-label'>Card Number</label> <input
                                            autocomplete='off' class='form-control card-number' size='20'
                                            type='text'>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-4 form-group cvc required'>
                                        <label class='control-label'>CVC</label> <input autocomplete='off'
                                                                                        class='form-control card-cvc' placeholder='ex. 311' size='4'
                                                                                        type='text'>
                                    </div>
                                    <div class='col-4 form-group expiration required'>
                                        <label class='control-label'>Expiration Month</label> <input
                                            class='form-control card-expiry-month' placeholder='MM' size='2'
                                            type='text'>
                                    </div>
                                    <div class='col-4 form-group expiration required'>
                                        <label class='control-label'>Expiration Year</label> <input
                                            class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                            type='text'>
                                    </div>
                                </div>
                                <div >
                                    <div class='error form-group' style="display: none;">
                                        <div class='alert-danger alert'>Please correct the errors and try
                                            again.
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right mb-2">
                                    <div class="">
                                        <button class="btn btn-primary btn-lg btn-block pay-btn" type="submit">Pay Now</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if($order->paid == 0)


        <script src="https://www.paypal.com/sdk/js?client-id=AV6qhCigre8RgTt8E6Z0KNesHxr1aDyJ2hmsk2ssQYmlaVxMHm2JFJvqDCsU15FhoCJY0mDzOu-jbFPY&currency=USD"></script>
        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>


{{--        <script--}}
{{--            src="https://www.paypal.com/sdk/js?client-id=ASxb6_rmf3pte_En7MfEVLPe_KDZQj68bKpzJzl7320mmpV3uDRDLGCY1LaCkyYZ4zNpHdC9oZ73-WFv">--}}
{{--        </script>--}}
        <script>

            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '{{number_format($order->total_cost + $usps_rate + $order->handling_fee +($order->total_cost + $order->shipping_rate_for_non_shopify + $order->handling_fee*$settings->paypal_percentage/100),2)}}'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        $('.ajax_paypal_form_submit').find('textarea').val(JSON.stringify(details));
                        $('.ajax_paypal_form_submit form').submit();
                    });
                }
            }).render('#paypal-button-container');
        </script>
    @endif

@endsection
