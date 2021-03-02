@extends('layout.index')
@section('content')
    <style>
        .wishlist_description img {
            max-width: 150px;
        }
    </style>
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Dropship Request
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Help-Center</li>
                        <li class="breadcrumb-item"> Dropship Request</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href=""> {{$item->product_name}} </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title">{{$item->product_name}}  <span class="badge " style="background: {{$item->has_status->color}};color: white;"> {{$item->has_status->name}}</span>
                        </h5>
                    </div>
                    <div class="block-content">
                        <div class="p-2">
                            @if($item->reference != null)
                                <a target="_blank" href="{{$item->reference}}">Reference Link Preview</a>
                                <hr>
                            @endif
                          <div class="wishlist_description">
                                {!! $item->description !!}
                          </div>
                            <div class="attachments">
                                @foreach($item->has_attachments as $a)
                                    <img style="width: 100%;max-width: 250px" src="{{asset('dropship-attachments')}}/{{$a->source}}" alt="">
                                @endforeach
                            </div>
                            <hr>
                            <div class="text-right p-2">
                                @if(in_array($item->status_id,[1,4]))
                                    <button class="btn btn-success" data-target="#mark-approved-modal" data-toggle="modal">Mark as Approved</button>
                                @endif
                                @if($item->status_id == 6)
                                    <button class="btn btn-primary" data-target="#mark-completed-modal" data-toggle="modal">Mark as Completed</button>
                                    <button class="btn btn-danger" data-target="#mark-rejected-by-weight-modal" data-toggle="modal">Mark as Rejected By Weight</button>
                                    <button class="btn btn-danger" data-target="#mark-rejected-by-inventory-modal" data-toggle="modal">Mark as Rejected By Inventory</button>
                                @endif
                                @if(!in_array($item->status_id,[4,5,6,7,8]))
                                    <button class="btn btn-danger" data-target="#mark-rejected-modal" data-toggle="modal">Mark as Rejected</button>
                                @endif
                            </div>
                            @if(in_array($item->status_id,[1,4]))
                                <div class="modal fade" id="mark-approved-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-popout" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Mark as Approved</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option">
                                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form action="{{route('dropship.requests.approve')}}" method="post">
                                                    @csrf
                                                    <input  type="hidden" name="dropship_request_id" value="{{$item->id}}">
                                                    <input  type="hidden" name="manager_id" value="{{$item->manager_id}}">
                                                    <div class="block-content font-size-sm">
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">You are about to approve the Dropship Request !</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Target Dropshipping Cost</label>
                                                                    <input readonly class="form-control" type="text" value="{{$item->cost}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="block-content block-content-full text-right border-top">
                                                        <button type="submit" class="btn btn-sm btn-success">Approved</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($item->status_id == 6)
                                <div class="modal fade" id="mark-completed-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-popout" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Mark as Completed</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option">
                                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form action="{{route('dropship.requests.completed')}}" method="post">
                                                    @csrf
                                                    <input  type="hidden" name="dropship_request_id" value="{{$item->id}}">
                                                    <input  type="hidden" name="manager_id" value="{{$item->manager_id}}">
                                                    <div class="block-content font-size-sm">
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Target Dropshipping Cost</label>
                                                                    <input readonly class="form-control" type="text" value="{{$item->cost}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="block-content block-content-full text-right border-top">

                                                        <button type="submit" class="btn btn-sm btn-success">Completed</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="mark-rejected-by-weight-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-popout" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Mark as Rejected By Weight</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option">
                                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form action="{{route('dropship.reqeusts.rejected.by.weight')}}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input  type="hidden" name="dropship_request_id" value="{{$item->id}}">
                                                    <input  type="hidden" name="manager_id" value="{{$item->manager_id}}">
                                                    <div class="block-content font-size-sm">
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Target Dropshipping Cost</label>
                                                                    <input readonly class="form-control" type="text" value="{{$item->cost}}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Approved Cost</label>
                                                                    <input class="form-control" type="number" step="any" name="approved_price">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Adjusted Weight</label>
                                                                    <input class="form-control" type="number" step="any" name="adjusted_weight">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Proof</label>
                                                                    <input class="form-control" type="file" name="rejection_proof">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="block-content block-content-full text-right border-top">

                                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="mark-rejected-by-inventory-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-popout" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Mark as Completed</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option">
                                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form action="{{route('wishlist.completed')}}" method="post">
                                                    @csrf
                                                    <input  type="hidden" name="dropship_request_id" value="{{$item->id}}">
                                                    <input  type="hidden" name="manager_id" value="{{$item->manager_id}}">
                                                    <div class="block-content font-size-sm">
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Target Dropshipping Cost</label>
                                                                    <input readonly class="form-control" type="text" value="{{$item->cost}}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Approved Cost</label>
                                                                    <input readonly class="form-control" type="number" step="any" value="{{$item->approved_price}}">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if($item->has_store_product != 1)
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <div class="form-material">
                                                                        <label for="material-error">Dropship Product</label>
                                                                        <select name="link_product_id" style="width: 100%;" data-placeholder="Choose Reference Product" required class="form-control js-select2">
                                                                            <option ></option>
                                                                            @foreach($products as $product)
                                                                                <option value="{{$product->id}}">{{$product->title}}</option>
                                                                            @endforeach
                                                                        </select>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <div class="form-material">
                                                                        <label for="material-error">Product Already at Store (Shopify Product ID)</label>
                                                                        <input  class="form-control" type="text" name="product_shopify_id" value="{{$item->product_shopify_id}}">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="block-content block-content-full text-right border-top">

                                                        <button type="submit" class="btn btn-sm btn-success">Completed</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="modal fade" id="mark-rejected-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-popout" role="document">
                                    <div class="modal-content">
                                        <div class="block block-themed block-transparent mb-0">
                                            <div class="block-header bg-primary-dark">
                                                <h3 class="block-title">Mark as Rejected</h3>
                                                <div class="block-options">
                                                    <button type="button" class="btn-block-option">
                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <form action="{{route('dropship.requests.reject')}}" method="post">
                                                @csrf
                                                <input  type="hidden" name="dropship_request_id" value="{{$item->id}}">
                                                <input  type="hidden" name="manager_id" value="{{$item->manager_id}}">
                                                <div class="block-content font-size-sm">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="form-material">
                                                                <label for="material-error">Target Dropshipping Cost</label>
                                                                <input readonly class="form-control" type="text" value="{{$item->cost}}">

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="form-material">
                                                                <label for="material-error">Rejected Reason</label>
                                                                <textarea required class="js-summernote" name="reject_reason"
                                                                          placeholder="Please Enter Reject Reason here !"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="block-content block-content-full text-right border-top">

                                                    <button type="submit" class="btn btn-sm btn-danger" >Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
{{--                @if($item->has_store_product != 1)--}}
{{--                @if($item->has_product != null)--}}
{{--                    <div class="block">--}}
{{--                        <div class="block-header">--}}
{{--                            <h5 class="block-title">Reference Product</h5>--}}
{{--                        </div>--}}
{{--                        <div class="options-container">--}}
{{--                            <a href="{{route('product.view',$item->has_product->id)}}">--}}
{{--                                @if(count($item->has_product->has_images) > 0)--}}
{{--                                    @foreach($item->has_product->has_images()->orderBy('position')->get() as $index => $image)--}}
{{--                                        @if($index == 0)--}}
{{--                                            @if($image->isV == 0)--}}
{{--                                                <img class="img-fluid options-item" src="{{asset('images')}}/{{$image->image}}">--}}
{{--                                            @else   <img class="img-fluid options-item" src="{{asset('images/variants')}}/{{$image->image}}" alt="">--}}
{{--                                            @endif--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
{{--                                @else--}}
{{--                                    <img class="img-fluid options-item" src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">--}}
{{--                                @endif--}}

{{--                            </a>--}}
{{--                            <div class="options-overlay bg-black-75">--}}
{{--                                <div class="options-overlay-content">--}}
{{--                                    <div class="push-20">--}}
{{--                                        <a class="btn btn-sm btn-primary" href="{{route('product.view',$item->has_product->id)}}">View</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="block-content" style="padding-bottom: 10px">--}}
{{--                            <div class="push-10">--}}
{{--                                <a class="h6" style="font-size: 0.9rem" href="{{route('product.view',$item->has_product->id)}}">{{$item->has_product->title}}</a>--}}
{{--                                <div class="font-w600 text-success mt-1 push-10-l">${{number_format($item->has_product->price,2)}}</div>--}}
{{--                            </div>--}}

{{--                            @if($item->has_product->processing_time != null)--}}
{{--                                <hr>--}}
{{--                                <p class="text-muted font-size-sm">  Dispatch Within {{$item->has_product->processing_time}} </p>--}}

{{--                            @endif--}}
{{--                            <hr>--}}
{{--                            <button onclick="window.location.href='{{route('product.view',$item->has_product->id)}}'" class="btn btn-primary btn-block mb2">View Product</button>--}}
{{--                            <span class="mb2 font-size-sm" style="color: grey">Fulfilled By WeFullFill</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <hr>--}}
{{--                    @endif--}}
{{--                    @else--}}
{{--                @if($item->has_retailer_product != null)--}}
{{--                    <div class="block">--}}
{{--                        <div class="block-header">--}}
{{--                            <h5 class="block-title">Reference Product</h5>--}}
{{--                        </div>--}}
{{--                        <div class="options-container">--}}
{{--                            <a href="{{route('product.view',$item->has_retailer_product->id)}}">--}}
{{--                                @if(count($item->has_retailer_product->has_images) > 0)--}}
{{--                                    @foreach($item->has_retailer_product->has_images()->orderBy('position')->get() as $index => $image)--}}
{{--                                        @if($index == 0)--}}
{{--                                            <img class="img-fluid options-item" src="{{$image->image}}">--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
{{--                                @else--}}
{{--                                    <img class="img-fluid options-item" src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">--}}
{{--                                @endif--}}

{{--                            </a>--}}
{{--                            <div class="options-overlay bg-black-75">--}}
{{--                                <div class="options-overlay-content">--}}
{{--                                    <div class="push-20">--}}
{{--                                        <a class="btn btn-sm btn-primary" href="{{route('product.retailer.view',$item->has_retailer_product->id)}}">View</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="block-content" style="padding-bottom: 10px">--}}
{{--                            <div class="push-10">--}}
{{--                                <a class="h6" style="font-size: 0.9rem" href="{{route('product.retailer.view',$item->has_retailer_product->id)}}">{{$item->has_retailer_product->title}}</a>--}}
{{--                                <div class="font-w600 text-success mt-1 push-10-l">${{number_format($item->has_retailer_product->price,2)}}</div>--}}
{{--                            </div>--}}

{{--                            @if($item->has_retailer_product->processing_time != null)--}}
{{--                                <hr>--}}
{{--                                <p class="text-muted font-size-sm">  Dispatch Within {{$item->has_retailer_product->processing_time}} </p>--}}

{{--                            @endif--}}
{{--                            <hr>--}}
{{--                            <button onclick="window.location.href='{{route('product.retailer.view',$item->has_retailer_product->id)}}'" class="btn btn-primary btn-block mb2">View Product</button>--}}
{{--                            <span class="mb2 font-size-sm" style="color: grey">Fulfilled By WeFullFill</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <hr>--}}
{{--                @endif--}}
{{--                @endif--}}


                @if(in_array($item->status_id,[6,7.8]))
                    <div class="block">
                        <div class="block-header d-flex justify-content-between">
                            <h5 class="block-title">Shipping Marks</h5>
                        </div>
                        <div class="block-content">
                            <div class="p-2">
                                @if(count($item->shipping_marks))
                                    <table class="table variants-div js-table-sections table-hover" style="overflow-x: hidden">
                                        <thead>
                                        <tr>
                                            <th style="vertical-align: top;">Product</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody class="product-details-body">
                                        @foreach($item->shipping_marks as $mark)
                                            <tr class="single-product-details">
                                                <td class="">{{ $mark->dropship_product->title }}</td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <a href="{{route('dropship.requests.view.shipping.mark',['id'=> $item->id, 'mark_id' => $mark->id])}}"
                                                           class="btn btn-sm btn-success" type="button" data-toggle="tooltip" title=""
                                                           data-original-title="View Shipping mark"><i class="fa fa-eye"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center">No Shipping Marks Added.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title">Dropship Request Details</h5>
                    </div>
                    <div class="block-content">
                        <div class="row font-size-sm">
                            <div class="col-md-6">
                                <span class="font-weight-bold">#: </span> <span class="text-center">{{$item->id}}</span>
                                @if($item->has_store != null)
                                    <hr>
                                    <span class="font-weight-bold">Store: </span> <span class=" badge badge-primary text-center">{{explode('.',$item->has_store->shopify_domain)[0]}}</span>
                                    <hr>
                                    <span class="font-weight-bold">Domain: </span> <span class="text-center">{{$item->has_store->shopify_domain}}</span>
                                    <hr>
                                @elseif($item->has_user != null)
                                    <hr>
                                    <span class="font-weight-bold">Client: </span> <span class="text-center">{{$item->has_user->name}}</span>
                                    <hr>
                                    <span class="font-weight-bold">Email: </span> <span class="text-center">{{$item->has_user->email}}</span>
                                    <hr>
                                @endif
                                <span class="font-weight-bold">Cost: </span> {{number_format($item->cost,2)}} USD
                                <hr>
                                <span class="font-weight-bold">Weekly Sales: </span> {{ $item->weekly_sales }}
                                <hr>
                                <span class="font-weight-bold">Weight: </span> {{ $item->weight }} (kg)
                                <hr>
                                <span class="font-weight-bold">Adjusted Weight: </span> {{ $item->adjusted_weight }} (kg)
                                <hr>
                                <span class="font-weight-bold">Packing size: </span> {{ $item->packing_size }}
                                <hr>
                                <span class="font-weight-bold">Contains Battery: </span> @if($item->battery) Yes @else No @endif
                                <hr>
                                <span class="font-weight-bold">Relabell Needed: </span> @if($item->relabell) Yes @else No @endif
                                <hr>
                                <span class="font-weight-bold">Repacking Needed: </span> @if($item->re_pack) Yes @else No @endif
                                <hr>
                            </div>
                           <div class="col-md-6">
                               <span class="font-weight-bold">Stock: </span> {{ $item->stock }}
                               <hr>
                               <span class="font-weight-bold">Number of Options: </span> {{ $item->option_count }}
                               <hr>
                               <span class="font-weight-bold">Markets: </span>   @if(count($item->has_market) > 0)
                                   @foreach($item->has_market as $country)
                                       <span class="badge badge-primary">{{$country->name}}</span>
                                   @endforeach
                               @else
                                   none
                               @endif
                               <hr>
                               @if($item->approved_price != null)
                                   <span class="font-weight-bold">Approved Cost: </span> {{number_format($item->approved_price,2)}} USD
                                   <hr>
                               @endif
                               @if($item->reject_reason != null)

                                   <span class="font-weight-bold text-danger font-w600">@if($item->rejected_by_use)Reject Reason By User: @else Reject Reason: @endif </span>  {!! $item->reject_reason !!}
                                   <hr>
                               @endif
                               <span class="font-weight-bold">Created at: </span> <span class="text-center">{{date_create($item->created_at)->format('m d, Y h:i a')}}</span>
                               <hr>
                               <span class="font-weight-bold">Last Update at: </span> <span class="text-center">{{date_create($item->updated_at)->format('m d, Y h:i a')}}</span>
                               <hr>
                               <span class="font-weight-bold">Status: </span>   @if($item->has_status != null)
                                   <span class="badge " style="background: {{$item->has_status->color}};color: white;"> {{$item->has_status->name}}</span>
                               @endif
                               <hr>
                               <span class="font-weight-bold">Wishlist Time: </span>  <span class="text-center">{{$item->created_at->diffForHumans()}}</span>
                               <hr>
                               <span class="font-weight-bold">Manager: </span>  <span class="badge badge-warning text-center" style="font-size: small"> {{$item->has_manager->name}} </span>
                               <hr>
                               <span class="font-weight-bold">Manager Email: </span>  <span class="text-center"> {{$item->has_manager->email}} </span>
                               <hr>
                               <span class="font-weight-bold">Tracking Number: </span> {{ $item->tracking_number }}
                               <hr>
                               <span class="font-weight-bold">Shipping Provider: </span> {{ $item->shipping_provider }}
                               <hr>
                           </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
