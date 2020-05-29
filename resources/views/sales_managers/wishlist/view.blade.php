@extends('layout.manager')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Wishlist
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Help-Center</li>
                        <li class="breadcrumb-item">Wishlist</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href=""> {{$wishlist->product_name}} </a>
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
                        <h5 class="block-title">{{$wishlist->product_name}}  <span class="badge " style="background: {{$wishlist->has_status->color}};color: white;"> {{$wishlist->has_status->name}}</span>
                        </h5>
                    </div>
                    <div class="block-content">
                        <div class="p-2">
                            @if($wishlist->has_product != null)
                                Wishlist Product :   <a href="{{route('sales_managers.products.view',$wishlist->has_product->id)}}">{{$wishlist->has_product->title}}</a>
                                <hr>
                            @endif

                            @if($wishlist->reference != null)
                                <p>Reference: <span class="badge badge-primary" style="font-size: small">{{$wishlist->reference}} </span></p>
                                <hr>
                            @endif
                            <p>
                                {!! $wishlist->description !!}
                            </p>

                            <div class="attachments">
                                @foreach($wishlist->has_attachments as $a)
                                    <img style="width: 100%;max-width: 250px" src="{{asset('wishlist-attachments')}}/{{$a->source}}" alt="">
                                @endforeach
                            </div>

                            <hr>
                            <div class="text-right p-2">
                                @if(in_array($wishlist->status_id,[1,4]))
                                    <button class="btn btn-success" data-target="#mark-approved-modal" data-toggle="modal">Mark as Approved</button>
                                @endif
                                @if($wishlist->status_id == 3)
                                    <button class="btn btn-primary" data-target="#mark-completed-modal" data-toggle="modal">Mark as Completed</button>
                                @endif
                                @if(!in_array($wishlist->status_id,[4,5]))
                                    <button class="btn btn-danger" data-target="#mark-rejected-modal" data-toggle="modal">Mark as Rejected</button>
                                @endif
                            </div>
                                @if(in_array($wishlist->status_id,[1,4]))
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
                                                <form action="{{route('wishlist.approve')}}" method="post">
                                                    @csrf
                                                    <input  type="hidden" name="wishlist_id" value="{{$wishlist->id}}">
                                                    <input  type="hidden" name="manager_id" value="{{$wishlist->manager_id}}">
                                                    <div class="block-content font-size-sm">
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Target Dropshipping Cost</label>
                                                                    <input readonly class="form-control" type="text" value="{{$wishlist->cost}}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Approved Cost</label>
                                                                    <input required class="form-control" type="number" step="any" name="approved_price">

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
                            @if($wishlist->status_id == 3)
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
                                                <form action="{{route('wishlist.completed')}}" method="post">
                                                    @csrf
                                                    <input  type="hidden" name="wishlist_id" value="{{$wishlist->id}}">
                                                    <input  type="hidden" name="manager_id" value="{{$wishlist->manager_id}}">
                                                    <div class="block-content font-size-sm">
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Target Dropshipping Cost</label>
                                                                    <input readonly class="form-control" type="text" value="{{$wishlist->cost}}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Approved Cost</label>
                                                                    <input readonly class="form-control" type="number" step="any" value="{{$wishlist->approved_price}}">

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <label for="material-error">Wishlist Product</label>
                                                                    <select name="link_product_id" required class="form-control">
                                                                        @foreach($products as $product)
                                                                            <option value="{{$product->id}}">{{$product->title}}</option>
                                                                        @endforeach
                                                                    </select>

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
                                            <form action="{{route('wishlist.reject')}}" method="post">
                                                @csrf
                                                <input  type="hidden" name="wishlist_id" value="{{$wishlist->id}}">
                                                <input  type="hidden" name="manager_id" value="{{$wishlist->manager_id}}">
                                                <div class="block-content font-size-sm">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="form-material">
                                                                <label for="material-error">Target Dropshipping Cost</label>
                                                                <input readonly class="form-control" type="text" value="{{$wishlist->cost}}">

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
                @if(count($wishlist->has_thread) > 0)
                    <h5> Thread </h5>
                    @foreach($wishlist->has_thread as $thread)
                        <div class="block">
                            <div class="block-header">
                                @if($thread->source == 'manager')
                                    <h5 class="block-title">{{$thread->has_manager->name}} <span class="badge badge-primary " style="float: right;font-size: small"> {{date_create($thread->created_at)->format('m d, Y h:i a')}}</span></h5>
                                @elseif($thread->source == 'store')
                                    <h5 class="block-title">{{explode('.',$wishlist->has_store->shopify_domain)[0]}} <span class="badge badge-primary " style="float: right;font-size: small"> {{date_create($thread->created_at)->format('m d, Y h:i a')}}</span></h5>
                                @else
                                    <h5 class="block-title">{{$wishlist->has_user->name}} <span class="badge badge-primary " style="float: right;font-size: small"> {{date_create($thread->created_at)->format('m d, Y h:i a')}}</span></h5>

                                @endif
                            </div>
                            <div class="block-content">
                                <div class="p-2">
                                    {!! $thread->reply !!}

                                    <div class="attachments">
                                        @foreach($thread->has_attachments as $a)
                                            <img style="width: 100%;max-width: 250px" src="{{asset('wishlist-attachments')}}/{{$a->source}}" alt="">
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title">Reply</h5>
                    </div>
                    <div class="block-content">
                        <div class="p-2">
                            <form action="{{route('wishlist.thread.create')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="manager_id" value="{{$wishlist->manager_id}}">
                                <input type="hidden" name="source" value="manager">
                                <input type="hidden" name="wishlist_id" value="{{$wishlist->id}}">
                                <div class="form-group">
                                    <div class="form-material">
                                        <label for="material-error">Message</label>
                                        <textarea required class="js-summernote" name="reply"
                                                  placeholder="Please Enter Message here !"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-material">
                                        <label for="material-error">Attachments </label>
                                        <input type="file" name="attachments[]" class="form-control" multiple>
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary" value="Save">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title">Wishlist Details</h5>
                    </div>
                    <div class="block-content">
                        <div class="p-2 font-size-sm">
                            <span class="font-weight-bold">#: </span> <span class="text-center">{{$wishlist->id}}</span>
                            @if($wishlist->has_store != null)
                                <hr>
                                <span class="font-weight-bold">Store: </span> <span class=" badge badge-primary text-center">{{explode('.',$wishlist->has_store->shopify_domain)[0]}}</span>
                                <hr>
                                <span class="font-weight-bold">Domain: </span> <span class="text-center">{{$wishlist->has_store->shopify_domain}}</span>
                                <hr>
                            @elseif($wishlist->has_user != null)
                                <hr>
                                <span class="font-weight-bold">Client: </span> <span class="text-center">{{$wishlist->has_user->name}}</span>
                                <hr>
                                <span class="font-weight-bold">Email: </span> <span class="text-center">{{$wishlist->has_user->email}}</span>
                                <hr>
                            @endif
                            <span class="font-weight-bold">Cost: </span> {{number_format($wishlist->cost,2)}} USD
                            <hr>
                            <span class="font-weight-bold">Markets: </span>   @if(count($wishlist->has_market) > 0)
                                @foreach($wishlist->has_market as $country)
                                    <span class="badge badge-primary">{{$country->name}}</span>
                                @endforeach
                            @else
                                none
                            @endif
                            <hr>
                            @if($wishlist->approved_price != null)
                                <span class="font-weight-bold">Approved Cost: </span> {{number_format($wishlist->approved_price,2)}} USD
                                <hr>
                            @endif
                            @if($wishlist->reject_reason != null)
                                <span class="font-weight-bold">Reject Reason: </span> {!! $wishlist->reject_reason !!}
                                <hr>
                            @endif
                            <span class="font-weight-bold">Created at: </span> <span class="text-center">{{date_create($wishlist->created_at)->format('m d, Y h:i a')}}</span>
                            <hr>
                            <span class="font-weight-bold">Last Update at: </span> <span class="text-center">{{date_create($wishlist->updated_at)->format('m d, Y h:i a')}}</span>
                            <hr>
                            <span class="font-weight-bold">Status: </span>   @if($wishlist->has_status != null)
                                <span class="badge " style="background: {{$wishlist->has_status->color}};color: white;"> {{$wishlist->has_status->name}}</span>
                            @endif
                            <hr>
                            <span class="font-weight-bold">Wishlist Time: </span>  <span class="text-center">{{$wishlist->created_at->diffForHumans()}}</span>
                            <hr>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
