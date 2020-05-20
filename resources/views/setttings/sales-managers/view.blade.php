@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    {{$manager->email}}
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Sales Managers </li>
                        <li class="breadcrumb-item">Edit </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block">
            <ul class="nav nav-tabs nav-justified nav-tabs-block " data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#stores"> Stores </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#users">Non-Shopify Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tickets">Tickets</a>
                </li>
            </ul>
            <div class="block-content tab-content">
                <div class="tab-pane active" id="stores" role="tabpanel">
                    <div class="block">
                        <div class="block-content">
                            @if (count($manager->has_sales_stores) > 0)
                                <table class="table table-hover table-borderless table-striped table-vcenter">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Shopify Domain</th>
                                        <th>Imported Products</th>
                                        <th>Orders</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody class="">
                                    @foreach($manager->has_sales_stores as $index => $store)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td class="font-w600">{{ explode('.',$store->shopify_domain)[0]}}</td>
                                            <td>
                                                <span class="badge badge-primary">{{$store->shopify_domain}}</span>
                                            </td>
                                            <td>
                                              {{count($store->has_imported)}}
                                            </td>
                                            <td>
                                                {{count($store->has_orders)}}

                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group mr-2 mb-2">
                                                    <a class="btn btn-xs btn-sm btn-success" type="button" href="" title="View Store">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center"> No Store Available</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="users" role="tabpanel">
                    <div class="block">
                        <div class="block-content">
                            @if (count($manager->has_users) > 0)
                                <table class="table table-hover table-borderless table-striped table-vcenter">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Email</th>
                                        <th>Imported Files</th>
                                        <th>Orders</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody class="">
                                    @foreach($manager->has_users as $index => $user)
                                        <tr>
                                            <td>{{$index+1}}</td>
                                            <td class="font-w600">{{$user->name}}</td>
                                            <td>
                                                <span class="badge badge-primary">{{$user->email}}</span>
                                            </td>
                                            <td>
                                                {{count($user->has_files)}}
                                            </td>
                                            <td>
                                                {{count($user->has_orders)}}

                                            </td>
                                            <td class="text-right">
                                                <div class="btn-group mr-2 mb-2">
                                                    <a class="btn btn-xs btn-sm btn-success" type="button" href="" title="View User">
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
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tickets" role="tabpanel">
                    <div class="block">
                        <div class="block-content">
                            <p class="text-center"> No Tickets Available</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
