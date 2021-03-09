@extends('layout.woocommerce')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Ticket
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Help-Center</li>
                        <li class="breadcrumb-item">Tickets</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href=""> {{$ticket->title}} </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-8">
                @if(in_array($ticket->status_id,[1,2,3]))
                    <div class="text-right mb2">
                        <button class="btn btn-success" onclick="window.location.href='{{route('help-center.ticket.marked_as_completed',$ticket->id)}}'"> Marked as Completed </button>
                        @if($ticket->order_id !== null)
                            <button class="btn btn-primary mr-2" onclick="window.location.href='{{route('store.order.view',$ticket->order_id)}}'">View Order</button>
                        @endif
                    </div>
                @endif
                @if(in_array($ticket->status_id,[4]) && $ticket->review == 0)
                    <div class="text-right mb2">
                        <button class="btn btn-warning" data-target="#add_review_modal" data-toggle="modal"> Add Review </button>
                    </div>
                    <div class="modal fade" id="add_review_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-popout" role="document">
                            <div class="modal-content">
                                <div class="block block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title">Add Review</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option">
                                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <form action="{{route('ticket.post_review')}}" method="post">
                                        @csrf
                                        <input type="hidden" value="{{$ticket->id}}" name="ticket_id">
                                        <input type="hidden" value="{{$ticket->user_id}}" name="user_id">
                                        <input type="hidden" value="{{$ticket->shop_id}}" name="shop_id">
                                        <input type="hidden" value="{{$ticket->manager_id}}" name="manager_id">
                                        <input type="hidden" id="rating-input" value="0" name="rating">
                                        <input type="hidden" value="{{$ticket->has_user->name}}" name="name">
                                        <input type="hidden" value="{{$ticket->has_user->name}}" name="email">

                                        <div class="block-content font-size-sm">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="form-material">
                                                        <label for="">Rating</label>
                                                        <div class='rating-stars '>
                                                            <ul id='stars' style="margin-bottom: 5px">
                                                                <li class='star' title='Poor' data-value='1'>
                                                                    <i class='fa fa-star fa-fw'></i>
                                                                </li>
                                                                <li class='star' title='Fair' data-value='2'>
                                                                    <i class='fa fa-star fa-fw '></i>
                                                                </li>
                                                                <li class='star' title='Good' data-value='3'>
                                                                    <i class='fa fa-star fa-fw '></i>
                                                                </li>
                                                                <li class='star' title='Excellent' data-value='4'>
                                                                    <i class='fa fa-star fa-fw '></i>
                                                                </li>
                                                                <li class='star' title='WOW!!!' data-value='5'>
                                                                    <i class='fa fa-star fa-fw '></i>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="form-material">
                                                        <label for="material-error">Review</label>
                                                        <textarea required rows="3" class="js-summernote" name="review"
                                                                  placeholder="Please Enter Description here !"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="block-content block-content-full text-right border-top">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                @endif
                @if($ticket->review == 1)
                    <div class="text-right mb2">
                        <button class="btn btn-warning" data-target="#view_review_modal" data-toggle="modal"> View Review </button>
                    </div>
                    <div class="modal fade" id="view_review_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-popout" role="document">
                            <div class="modal-content">
                                <div class="block block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title">Reviews</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option">
                                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="block-content font-size-sm">
                                        @foreach($ticket->has_reviews as $review)
                                            <div class="d-flex">
                                                <input type="hidden" name="rating" value="{{$review->rating}}">
                                                <div class='rating-stars disabled'>
                                                    <ul id='stars' style="margin-bottom: 5px">
                                                        <li class='star' title='Poor' data-value='1'>
                                                            <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                        <li class='star' title='Fair' data-value='2'>
                                                            <i class='fa fa-star fa-fw '></i>
                                                        </li>
                                                        <li class='star' title='Good' data-value='3'>
                                                            <i class='fa fa-star fa-fw '></i>
                                                        </li>
                                                        <li class='star' title='Excellent' data-value='4'>
                                                            <i class='fa fa-star fa-fw '></i>
                                                        </li>
                                                        <li class='star' title='WOW!!!' data-value='5'>
                                                            <i class='fa fa-star fa-fw '></i>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div style="margin-left: auto">
                                                    <span class="badge badge-primary">{{$review->created_at->diffForHumans()}}</span>
                                                </div>
                                            </div>

                                            <p>{!! $review->review !!}</p><hr>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title">{{$ticket->title}} <span class="badge @if($ticket->priority == 'low') badge-primary @elseif($ticket->priority == 'medium') badge-warning @else badge-danger @endif" style="float: right;font-size: small"> {{$ticket->priority}}</span></h5>
                    </div>
                    <div class="block-content">
                        <div class="p-2">
                            <p>Ticket-Token: <span class="badge badge-primary" style="font-size: small">{{$ticket->token}} </span></p>
                            <hr>
                            {!! $ticket->message !!}
                            <div class="attachments">
                                @foreach($ticket->has_attachments as $a)
                                    <img style="width: 100%;max-width: 250px" src="{{asset('ticket-attachments')}}/{{$a->source}}" alt="">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @if(count($ticket->has_thread) > 0)
                    <h5> Thread </h5>
                    @foreach($ticket->has_thread as $thread)
                        <div class="block  @if($thread->source == 'manager') bg-muted text-white @endif">

                            <div class="block-header">
                                @if($thread->source == 'manager')
                                    <h5 class="block-title text-white">{{$thread->has_manager->name}} (Manager) <span class="badge badge-primary " style="float: right;font-size: small"> {{date_create($thread->created_at)->format('m d, Y h:i a')}}</span></h5>
                                @else
                                    <h5 class="block-title">{{$ticket->has_user->name}} <span class="badge badge-primary " style="float: right;font-size: small"> {{date_create($thread->created_at)->format('m d, Y h:i a')}}</span></h5>
                                @endif
                            </div>
                            <div class="block-content">
                                <div class="p-2">
                                    {!! $thread->reply !!}

                                    <div class="attachments">
                                        @foreach($thread->has_attachments as $a)
                                            <img style="width: 100%;max-width: 250px" src="{{asset('ticket-attachments')}}/{{$a->source}}" alt="">
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                @if(in_array($ticket->status_id,[1,2,3]))
                    <div class="block">
                        <div class="block-header">
                            <h5 class="block-title">Reply To Manager</h5>
                        </div>
                        <div class="block-content">
                            <div class="p-2">
                                <form action="{{route('help-center.ticket.thread.create')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="manager_id" value="{{$ticket->manager_id}}">
                                    <input type="hidden" name="shop_id" value="{{$ticket->shop_id}}">
                                    <input type="hidden" name="source" value="store">
                                    <input type="hidden" name="ticket_id" value="{{$ticket->id}}">
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
                @endif
            </div>
            <div class="col-md-4">
                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title">Ticket Details</h5>
                    </div>
                    <div class="block-content">
                        <div class="p-2 font-size-sm">
                            <span class="font-weight-bold">#: </span> <span class="text-center">{{$ticket->id}}</span>
                            <hr>
                            <span class="font-weight-bold">Store: </span> <span class=" badge badge-primary text-center">{{$ticket->has_user->name}}</span>
                            <hr>
                            <span class="font-weight-bold">Domain: </span> <span class="text-center">{{$ticket->email}}</span>
                            <hr>
                            <span class="font-weight-bold">Created at: </span> <span class="text-center">{{date_create($ticket->created_at)->format('m d, Y h:i a')}}</span>
                            <hr>
                            <span class="font-weight-bold">Last Update at: </span> <span class="text-center">{{date_create($ticket->updated_at)->format('m d, Y h:i a')}}</span>
                            <hr>
                            <span class="font-weight-bold">Category: </span> <span class="badge @if($ticket->has_category == null) badge-light @endif text-center" @if($ticket->has_category != null) style="background: {{$ticket->has_category->color}}; color:white" @endif> {{$ticket->category}} </span>
                            <hr>
                            <span class="font-weight-bold">Status: </span>   @if($ticket->has_status != null)
                                <span class="badge " style="background: {{$ticket->has_status->color}};color: white;"> {{$ticket->has_status->status}}</span>
                            @endif
                            <hr>
                            <span class="font-weight-bold">Ticket Time: </span>  <span class="text-center">{{$ticket->created_at->diffForHumans()}}</span>
                            <hr>
                            <span class="font-weight-bold">Manager: </span>  <span class="badge badge-warning text-center" style="font-size: small"> {{$ticket->has_manager->name}} </span>
                            <hr>
                            <span class="font-weight-bold">Manager Email: </span>  <span class="text-center"> {{$ticket->has_manager->email}} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
