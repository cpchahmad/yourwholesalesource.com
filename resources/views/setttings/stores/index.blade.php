@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                   Stores
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx active" href="">Stores</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block">
            <div class="block-content">
                @if (count($stores) > 0)
                    <table class="table table-hover table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>

                            <th>Title</th>
                            <th>Shopify Domain</th>
                            <th>Manager</th>
                            <th>Products</th>
                            <th>Orders</th>
                            <th>Tickets</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="">
                        @foreach($stores as $index => $store)
                            <tr>

                                <td class="font-w600">{{ explode('.',$store->shopify_domain)[0]}}</td>
                                <td>
                                    <span class="badge badge-primary">{{$store->shopify_domain}}</span>
                                </td>
                                <td>
                                    @if($store->has_manager != null)
                                        <img class="rounded img-avatar" @if($store->has_manager->profile == null) src="{{ asset('assets/media/avatars/avatar10.jpg') }}" @else  src="{{asset('managers-profiles')}}/{{$store->has_manager->profile}}" @endif alt="">
                                        <span style="margin-left: 10px">{{$store->has_manager->name}}</span>
                                    @else
                                        Manager Deleted
                                    @endif
                                </td>
                                <td>
                                    {{count($store->has_imported)}}
                                </td>
                                <td>
                                    {{count($store->has_orders)}}

                                </td>
                                <td>
                                    {{count($store->has_tickets)}}

                                </td>
                                <td class="text-right">
                                    <div class="btn-group mr-2 mb-2">
                                        <a class="btn btn-primary btn-xs btn-sm text-white" data-toggle="modal" data-target="#assign_manager_{{$store->id}}" type="button" title="Assign Sales Manager">  <i class="fa fa-user"></i></a>

                                        <a class="btn btn-xs btn-sm btn-success" type="button" href="{{route('stores.view',$store->id)}}" title="View Store">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade" id="assign_manager_{{$store->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-popout" role="document">
                                    <div class="modal-content">
                                        <div class="block block-themed block-transparent mb-0">
                                            <div class="block-header bg-primary-dark">
                                                <h3 class="block-title">Manage Sales Manager</h3>
                                                <div class="block-options">
                                                    <button type="button" class="btn-block-option">
                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <form action="{{route('assign_manager',$store->id)}}" method="post">
                                                @csrf
                                                <input type="hidden" name="type" value="store">
                                                <div class="block-content font-size-sm">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <label for="">Sales Managers</label>
                                                            <select required name="sale_manager_id" class="form-control">
                                                                @foreach($managers as $manager)
                                                                    <option  @if($store->has_manager != null) @if($store->has_manager->id == $manager->if) selected @endif @endif value="{{$manager->id}}"> {{$manager->name}} {{$manager->last_name}} ({{$manager->email}}) </option>
                                                                @endforeach
                                                            </select>
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

                        @endforeach
                        </tbody>
                    </table>

                @else
                    <p class="text-center"> No Store Available</p>
                @endif
                    <div class="row">
                        <div class="col-md-12 text-center" style="font-size: 17px">
                            {!! $stores->links() !!}
                        </div>
                    </div>
            </div>
        </div>
    </div>

    @endsection
