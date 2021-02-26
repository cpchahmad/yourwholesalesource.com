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
                                @if($item->status_id == 2)
                                    <div class="row p-2">
                                        <div class="col-md-8">
                                            <p class="font-w400 text-success "> <i class="fa fa-question-circle text-success"></i> The approved quote is
                                                based on your offered packing size, weight and
                                                other request, there will be a ﬁnal conﬁrmation
                                                once your stock landed in our warehouse .
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-success" data-target="#mark-approved-modal" data-toggle="modal">Mark as Accepted</button>
                                        </div>
                                    </div>
                                @endif
                                @if($item->status_id == 2)
                                    <div class="modal fade" id="mark-approved-modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
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
                                                    <form action="{{route('wishlist.accept')}}" method="post">
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
                                                                        <input readonly class="form-control" type="number" step="any" name="approved_price" value="{{$item->approved_price}}">

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
                                @endif

                        </div>
                    </div>
                </div>
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
                            @if($item->reject_reason != null)
                                <span class="font-weight-bold">Reject Reason: </span> {{$item->reject_reason}}
                                <hr>
                            @endif
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
