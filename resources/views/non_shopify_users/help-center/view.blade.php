@extends('layout.shopify')
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
                    <div class="block">
                        <div class="block-header">
                            @if($thread->source == 'manager')
                                <h5 class="block-title">{{$thread->has_manager->name}} (Manager) <span class="badge badge-primary " style="float: right;font-size: small"> {{date_create($thread->created_at)->format('m d, Y h:i a')}}</span></h5>
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
                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title">Reply</h5>
                    </div>
                    <div class="block-content">
                        <div class="p-2">
                            <form action="{{route('help-center.ticket.thread.create')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="manager_id" value="{{$ticket->manager_id}}">
                                <input type="hidden" name="user_id" value="{{$ticket->user_id}}">
                                <input type="hidden" name="source" value="non-shopify-user">
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
                            <span class="font-weight-bold">Client: </span> <span class="text-center">{{$ticket->has_user->name}}</span>
                            <hr>
                            <span class="font-weight-bold">Email: </span> <span class="text-center">{{$ticket->email}}</span>
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
