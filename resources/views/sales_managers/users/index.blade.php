@extends('layout.manager')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Non Shopify Users
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx active" href="">Non Shopify Users</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block">
            <div class="block-content">
                @if (count($users) > 0)
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
                        @foreach($users as $index => $user)
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
    @endsection
