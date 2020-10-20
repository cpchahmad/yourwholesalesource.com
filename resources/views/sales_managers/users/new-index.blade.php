@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Users
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx active" href="">Users</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <form class="js-form-icon-search push" action="" method="get">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Search by name" value="{{$user_search}}" name="user_search">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        <a class="btn btn-danger" href="{{route('users.index')}}"> <i class="fa fa-times"></i> Clear </a>
                    </div>
                </div>
            </div>
        </form>
        <div class="block">
            <div class="block-content">
                @if (count($users) > 0)
                    <table class="table table-hover table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>

                            <th>Title</th>
                            <th>Email</th>
                            <th>Stores</th>
                            <th>Manager</th>
                            <th>Files</th>
                            <th>Orders</th>
                            <th>Tickets</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="">
                        @foreach($users as $index => $user)
                            <tr>

                                <td class="font-w600"><a href="{{route('users.view',$user->id)}}">{{$user->name}}</a></td>
                                <td>
                                    <span class="badge badge-primary">{{$user->email}}</span>
                                </td>
                                <td>
                                    @if($user->has_shops()->count() > 0)
                                        @foreach($user->has_shops()->get() as $store)
                                            <span class="badge badge-primary">
                                                {{$store->shopify_domain}}
                                            </span>
                                        @endforeach
                                    @endif

                                </td>
                                <td>
                                    @if($user->has_manager != null)
                                        <a href="{{route('sales-managers.view',$user->has_manager->id)}}">
                                        <img class="img-avatar-rounded" @if($user->has_manager->profile == null) src="{{ asset('assets/media/avatars/avatar10.jpg') }}" @else  src="{{asset('managers-profiles')}}/{{$user->has_manager->profile}}" @endif alt="">
                                        <span style="margin: auto 0px auto 5px;">{{$user->has_manager->name}}</span>
                                        </a>
                                    @else
                                        Manager Deleted
                                    @endif
                                </td>
                                <td>
                                    {{count($user->has_files)}}
                                </td>
                                <td>
                                    {{count($user->has_orders)}}

                                </td>
                                <td>
                                    {{count($user->has_tickets)}}

                                </td>
                                <td class="text-right">
                                    <div class="btn-group mr-2 mb-2">
                                        <a class="btn btn-primary btn-xs btn-sm text-white" data-toggle="modal" data-target="#assign_manager_{{$user->id}}" type="button" title="Assign Sales Manager">  <i class="fa fa-user"></i></a>
                                        <a class="btn btn-xs btn-sm btn-success" type="button" href="{{route('users.view',$user->id)}}" title="View User">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center"> No User Available</p>
                @endif
                    <div class="row">
                        <div class="col-md-12 text-center" style="font-size: 17px">
                            {!! $users->links() !!}
                        </div>
                    </div>
            </div>
        </div>
    </div>
    @endsection
