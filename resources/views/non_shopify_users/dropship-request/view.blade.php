@extends('layout.shopify')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Dropship Request
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Dropship Request</li>
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
            <div class="col-md-8">
                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title">{{$item->product_name}}  <span class="badge " style="background: {{$item->has_status->color}};color: white;"> {{$item->has_status->name}}</span>
                        </h5>
                    </div>
                    <div class="block-content">
                        <div class="p-2">

                            @if($item->product_url != null)
                                    <a target="_blank" href="{{$item->product_url}}">Reference Link Preview</a>
                                    <hr>
                            @endif
                            <p>
                                {!! $item->description !!}
                            </p>

                            <div class="attachments">
                                @foreach($item->has_attachments as $a)
                                    <img style="width: 100%;max-width: 250px" src="{{asset('dropship-attachments')}}/{{$a->source}}" alt="">
                                @endforeach
                            </div>
                                <hr>
                                @if($item->status_id == 4 && $item->reject_reason != null && $item->rejected_by_use == null)
                                    <div class="row p-2">
                                        <div class="col-md-8 text-danger font-w600">
                                            Rejection Reason:
                                            {!! $item->reject_reason !!}
                                        </div>
                                    </div>
                                @endif
                                @if($item->status_id == 2)
                                    <div class="row p-2">
                                        <div class="col-md-6">
                                            <p class="font-w400 text-success "> <i class="fa fa-question-circle text-success"></i> The approved quote is
                                                based on your offered packing size, weight and
                                                other request, there will be a final confirmation
                                                once your stock landed in our warehouse .
                                            </p>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button class="btn btn-success" data-target="#mark-accepted-modal" data-toggle="modal">Mark as Accepted</button>
                                            <button class="btn btn-danger ml-2" data-target="#mark-rejected-modal" data-toggle="modal">Mark as Rejected</button>
                                        </div>
                                    </div>
                                @endif
                                @if($item->status_id == 2)
                                    <div class="modal fade" id="mark-accepted-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-popout" role="document">
                                            <div class="modal-content">
                                                <div class="block block-themed block-transparent mb-0">
                                                    <div class="block-header bg-primary-dark">
                                                        <h3 class="block-title">Mark as Accepted</h3>
                                                        <div class="block-options">
                                                            <button type="button" class="btn-block-option">
                                                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <form action="{{route('dropship.requests.accept')}}" method="post">
                                                        @csrf
                                                        <input  type="hidden" name="dropship_request_id" value="{{$item->id}}">
                                                        <input  type="hidden" name="manager_id" value="{{$item->manager_id}}">
                                                        <div class="block-content font-size-sm">
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <div class="form-material">
                                                                        <label for="material-error">You are about to accept the Qoute with a Approved Dropshipping Cost of:</label>
                                                                        <input readonly class="form-control" type="text" value="{{$item->approved_price}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="block-content block-content-full text-right border-top">

                                                            <button type="submit" class="btn btn-sm btn-success">Accept</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                        <input  type="hidden" name="by_user" value="1">
                                                        <div class="block-content font-size-sm">
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
                                @endif

                                @if($item->status_id == 3)
                                    <div class="row p-2">
                                        <div class="col-md-12 text-right pr-0">
                                            <button class="btn btn-success ml-2" data-target="#mark-shipped-modal" data-toggle="modal">Mark as Shipped</button>
                                        </div>
                                    </div>
                                @endif
                                @if($item->status_id == 7)
                                    <div class="row p-2">
                                        <div class="col-md-12 text-right pr-0">
                                            <button class="btn btn-success ml-2" data-target="#mark-approved-modal" data-toggle="modal">Approve New Dropshipping cost</button>
                                            <button class="btn btn-danger ml-2" data-target="#mark-cancel-modal" data-toggle="modal">Cancel Request</button>
                                        </div>
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
                                                        <form action="{{route('dropship.requests.completed')}}" method="post">
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

                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-material">
                                                                            <label for="material-error">Approved Dropshipping Cost</label>
                                                                            <input readonly class="form-control" type="text" value="{{$item->approved_price}}">
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
                                        <div class="modal fade" id="mark-cancel-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    <div class="block block-themed block-transparent mb-0">
                                                        <div class="block-header bg-primary-dark">
                                                            <h3 class="block-title">Mark as Cancelled</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option">
                                                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <form action="{{route('dropship.requests.cancelled')}}" method="post">
                                                            @csrf
                                                            <input  type="hidden" name="dropship_request_id" value="{{$item->id}}">
                                                            <input  type="hidden" name="manager_id" value="{{$item->manager_id}}">
                                                            <div class="block-content font-size-sm">
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-material">
                                                                            <label for="material-error">You are about to cancel the Dropship Request !</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="block-content block-content-full text-right border-top">
                                                                <button type="submit" class="btn btn-sm btn-success">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($item->status_id == 3)
                                    <div class="modal fade" id="mark-shipped-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-popout" role="document">
                                            <div class="modal-content">
                                                <div class="block block-themed block-transparent mb-0">
                                                    <div class="block-header bg-primary-dark">
                                                        <h3 class="block-title">Mark as Shipped</h3>
                                                        <div class="block-options">
                                                            <button type="button" class="btn-block-option">
                                                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <form action="{{route('dropship.requests.shipped')}}" method="post">
                                                        @csrf
                                                        <input  type="hidden" name="dropship_request_id" value="{{$item->id}}">
                                                        <input  type="hidden" name="manager_id" value="{{$item->manager_id}}">
                                                        <div class="block-content font-size-sm">
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <div class="form-material">
                                                                        <label for="material-error">Tracking Number</label>
                                                                        <input placeholder="Please Enter Tracking number here !" name="tracking_number" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <div class="form-material">
                                                                        <label for="material-error">Shipping Provider</label>
                                                                        <input placeholder="Please Enter Shipping Provider Title here !" name="shipping_provider" class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="block-content block-content-full text-right border-top">

                                                            <button type="submit" class="btn btn-sm btn-success">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($item->status_id == 8)
                                    <div class="row p-2">
                                        <div class="col-md-12 text-right pr-0">
                                            <button class="btn btn-success ml-2" data-target="#mark-continue-modal" data-toggle="modal">Continue Processing</button>
                                            <button class="btn btn-danger ml-2" data-target="#mark-cancel-modal" data-toggle="modal">Cancel Request</button>
                                        </div>
                                        <div class="modal fade" id="mark-continue-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    <div class="block block-themed block-transparent mb-0">
                                                        <div class="block-header bg-primary-dark">
                                                            <h3 class="block-title">Mark as Cancelled</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option">
                                                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <form action="{{route('dropship.requests.continue')}}" method="post">
                                                            @csrf
                                                            <input  type="hidden" name="dropship_request_id" value="{{$item->id}}">
                                                            <input  type="hidden" name="manager_id" value="{{$item->manager_id}}">
                                                            <div class="block-content font-size-sm">
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-material">
                                                                            <label for="material-error">Accepting this will let you to add new product stock ones agian</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="block-content block-content-full text-right border-top">
                                                                <button type="submit" class="btn btn-sm btn-success">Continue</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="mark-cancel-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    <div class="block block-themed block-transparent mb-0">
                                                        <div class="block-header bg-primary-dark">
                                                            <h3 class="block-title">Mark as Cancelled</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option">
                                                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <form action="{{route('dropship.requests.cancelled')}}" method="post">
                                                            @csrf
                                                            <input  type="hidden" name="dropship_request_id" value="{{$item->id}}">
                                                            <input  type="hidden" name="manager_id" value="{{$item->manager_id}}">
                                                            <div class="block-content font-size-sm">
                                                                <div class="form-group">
                                                                    <div class="col-sm-12">
                                                                        <div class="form-material">
                                                                            <label for="material-error">You are about to cancel the Dropship Request !</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="block-content block-content-full text-right border-top">
                                                                <button type="submit" class="btn btn-sm btn-success">Cancel</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                        </div>
                    </div>
                </div>


                @if($item->status_id == 3 || $item->status_id == 6)
                    <div class="block">
                    <div class="block-header d-flex justify-content-between">
                        <h5 class="block-title">Shipping Marks</h5>
                        @if($item->status_id == 3 && count($item->shipping_marks) != 1)
                            <a class="btn btn-primary" href="{{ route('users.dropship.requests.create.shipping.mark', $item->id) }}">Create Shipping Marks</a>
                        @endif
                    </div>
                    <div class="block-content">
                        <div class="p-2">
                            @if(count($item->shipping_marks))
                                <table class="table variants-div js-table-sections table-hover" style="overflow-x: hidden">
                                    <thead>
                                    <tr>
                                        <th style="vertical-align: top;">Shipping marks</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody class="product-details-body">
                                @foreach($item->shipping_marks as $mark)
                                    <tr class="single-product-details">
                                        <td class="">{{ $mark->dropship_product->title }}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                                <a href="{{route('users.dropship.requests.view.shipping.mark',['id'=> $item->id, 'mark_id' => $mark->id])}}"
                                                   class="btn btn-sm btn-success" type="button" data-toggle="tooltip" title=""
                                                   data-original-title="View Shipping mark"><i class="fa fa-eye"></i></a>
                                                <a href="{{route('delete.shipping.mark',$mark->id)}}"
                                                   class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title=""
                                                   data-original-title="Delete Shipping mark"><i class="fa fa-times"></i></a>
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

                @if($item->status_id == 7)
                    <div class="block">
                        <div class="block-header d-flex justify-content-between">
                            <h5 class="block-title">Rejection Proof</h5>
                        </div>
                        <div class="block-content">
                            <div class="p-2">
                                <div class="form-group">
                                    <div class="form-material">
                                        <label for="material-error">Approved Dropshipping Cost</label>
                                        <input readonly class="form-control" type="text" value="{{$item->approved_price}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-material">
                                        <label for="material-error">Adjusted Weight</label>
                                        <input readonly class="form-control" type="text" value="{{$item->adjusted_weight}}">
                                    </div>
                                </div>

                                <img style="width: 100%;max-width: 250px" src="{{asset('rejection-proof')}}/{{$item->rejection_proof}}" alt="">
                            </div>
                        </div>
                    </div>
                @endif

                @if($item->status_id == 8)
                    <div class="block">
                        <div class="block-header d-flex justify-content-between">
                            <h5 class="block-title">Inventory Details</h5>
                        </div>
                        <div class="block-content">
                            <div class="p-2">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Inventory</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($item->dropship_products as $product)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $product->title }}</td>
                                            <td>
                                                <table class="w-100">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>SKU</th>
                                                        <th>Actual</th>
                                                        <th>Received</th>
                                                        <th>Missing</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($product->dropship_product_variants as $variant)
                                                        <tr>
                                                            <td class="d-flex align-items-center">
                                                                <img style="width: 100px; height: auto;" src="{{asset('shipping-marks')}}/{{$variant->image}}" alt="">
                                                            </td>
                                                            <td>{{ $variant->sku }}</td>
                                                            <td>{{ $variant->inventory }}</td>
                                                            <td>{{ $variant->received }}</td>
                                                            <td>{{ $variant->missing }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{--                @if(count($wishlist->has_thread) > 0)--}}
{{--                    <h5> Thread </h5>--}}
{{--                    @foreach($wishlist->has_thread as $thread)--}}
{{--                        @if(!($thread->show))--}}
{{--                            <div class="block">--}}
{{--                                <div class="block-header">--}}
{{--                                    @if($thread->source == 'manager')--}}
{{--                                        <h5 class="block-title">{{$thread->has_manager->name}} (Manager) <span class="badge badge-primary " style="float: right;font-size: small"> {{date_create($thread->created_at)->format('m d, Y h:i a')}}</span></h5>--}}
{{--                                    @else--}}
{{--                                        <h5 class="block-title">{{$user->name}} <span class="badge badge-primary " style="float: right;font-size: small"> {{date_create($thread->created_at)->format('m d, Y h:i a')}}</span></h5>--}}

{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="block-content">--}}
{{--                                    <div class="p-2">--}}
{{--                                        {!! $thread->reply !!}--}}

{{--                                        <div class="attachments">--}}
{{--                                            @foreach($thread->has_attachments as $a)--}}
{{--                                                <img style="width: 100%;max-width: 250px" src="{{asset('wishlist-attachments')}}/{{$a->source}}" alt="">--}}
{{--                                            @endforeach--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                @endif--}}
{{--                @if(!in_array($item->status_id,[3,5]))--}}
{{--                    <div class="block">--}}
{{--                    <div class="block-header">--}}
{{--                        <h5 class="block-title">Reply To Manager</h5>--}}
{{--                    </div>--}}
{{--                    <div class="block-content">--}}
{{--                        <div class="p-2">--}}
{{--                            <form action="{{route('wishlist.thread.create')}}" method="post" enctype="multipart/form-data">--}}
{{--                                @csrf--}}
{{--                                <input type="hidden" name="manager_id" value="{{$wishlist->manager_id}}">--}}
{{--                                <input type="hidden" name="user_id" value="{{$wishlist->user_id}}">--}}
{{--                                <input type="hidden" name="source" value="non-shopify-user">--}}
{{--                                <input type="hidden" name="wishlist_id" value="{{$wishlist->id}}">--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="form-material">--}}
{{--                                        <label for="material-error">Message</label>--}}
{{--                                        <textarea required class="js-summernote" name="reply"--}}
{{--                                                  placeholder="Please Enter Message here !"></textarea>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="form-material">--}}
{{--                                        <label for="material-error">Attachments </label>--}}
{{--                                        <input type="file" name="attachments[]" class="form-control" multiple>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <input type="submit" class="btn btn-primary" value="Save">--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                @endif--}}
            </div>
            <div class="col-md-4">
{{--                @if($item->has_product != null)--}}
{{--                    <div class="block">--}}
{{--                        <div class="block-header">--}}
{{--                            <h5 class="block-title">Reference Product</h5>--}}
{{--                        </div>--}}
{{--                        <div class="options-container">--}}
{{--                            <a href="{{route('users.product.wefulfill.show',$wishlist->has_product->id)}}">--}}
{{--                                @if(count($wishlist->has_product->has_images) > 0)--}}
{{--                                    @foreach($wishlist->has_product->has_images()->orderBy('position')->get() as $index => $image)--}}
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
{{--                                        <a class="btn btn-sm btn-primary" href="{{route('users.product.wefulfill.show',$wishlist->has_product->id)}}">View</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="block-content" style="padding-bottom: 10px">--}}
{{--                            <div class="push-10">--}}
{{--                                <a class="h6" style="font-size: 0.9rem" href="{{route('users.product.wefulfill.show',$wishlist->has_product->id)}}">{{$wishlist->has_product->title}}</a>--}}
{{--                                <div class="font-w600 text-success mt-1 push-10-l">${{number_format($wishlist->has_product->price,2)}}</div>--}}
{{--                            </div>--}}

{{--                            @if($wishlist->has_product->processing_time != null)--}}
{{--                                <hr>--}}
{{--                                <p class="text-muted font-size-sm">  Dispatch Within {{$wishlist->has_product->processing_time}} </p>--}}

{{--                            @endif--}}
{{--                            <hr>--}}
{{--                            <button onclick="window.location.href='{{route('users.product.wefulfill.show',$wishlist->has_product->id)}}'" class="btn btn-primary btn-block mb2">View Product</button>--}}
{{--                            <button onclick="window.location.href='{{route('app.download.product')}}?shop=wefullfill.myshopify.com&&product_id={{$wishlist->has_product->shopify_id}}'" class="btn btn-warning btn-block mb2">Download</button>--}}

{{--                            <span class="mb2 font-size-sm" style="color: grey">Fulfilled By WeFullFill</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <hr>--}}
{{--                @endif--}}
                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title">Dropship Request Details</h5>
                    </div>
                    <div class="block-content">
                        <div class="p-2 font-size-sm">
                            <span class="font-weight-bold">#: </span> <span class="text-center">{{$item->id}}</span>
                            <hr>
                            <span class="font-weight-bold">Client: </span> <span class="text-center">{{$user->name}}</span>
                            <hr>
                            <span class="font-weight-bold">Email: </span> <span class="text-center">{{$user->email}}</span>
                            <hr>
                            <span class="font-weight-bold">Cost: </span> {{number_format($item->cost,2)}} USD
                            <hr>
                            <span class="font-weight-bold">Weekly Sales: </span> {{ $item->weekly_sales }}
                            <hr>
                            <span class="font-weight-bold">Weight: </span> {{ $item->weight }} (kg)
                            <hr>
                            <span class="font-weight-bold">Packing size: </span> {{ $item->packing_size }}
                            <hr>
                            <span class="font-weight-bold">Contains Battery: </span> @if($item->battery) Yes @else No @endif
                            <hr>
                            <span class="font-weight-bold">Relabell Needed: </span> @if($item->relabell) Yes @else No @endif
                            <hr>
                            <span class="font-weight-bold">Repacking Needed: </span> @if($item->re_pack) Yes @else No @endif
                            <hr>
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
                            <span class="font-weight-bold">Created at: </span> <span class="text-center">{{date_create($item->created_at)->format('m d, Y h:i a')}}</span>
                            <hr>
                            <span class="font-weight-bold">Last Update at: </span> <span class="text-center">{{date_create($item->updated_at)->format('m d, Y h:i a')}}</span>
                            <hr>
                            <span class="font-weight-bold">Status: </span>   @if($item->has_status != null)
                                <span class="badge " style="background: {{$item->has_status->color}};color: white;"> {{$item->has_status->name}}</span>
                            @endif
                            <hr>
                            @if($item->approved_price != null)
                                <span class="font-weight-bold">Approved Cost: </span> {{number_format($item->approved_price,2)}} USD
                                <hr>
                            @endif

                            <span class="font-weight-bold">Tracking Number: </span> {{ $item->tracking_number }}
                            <hr>

                            <span class="font-weight-bold">Shipping Provider: </span> {{ $item->shipping_provider }}
                            <hr>

                            <span class="font-weight-bold">Dropship Request Time: </span>  <span class="text-center">{{$item->created_at->diffForHumans()}}</span>
                            <hr>
                            <span class="font-weight-bold">Manager: </span>  <span class="badge badge-warning text-center" style="font-size: small"> {{$item->has_manager->name}} </span>
                            <hr>
                            <span class="font-weight-bold">Manager Email: </span>  <span class="text-center"> {{$item->has_manager->email}} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
