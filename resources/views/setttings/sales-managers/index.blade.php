@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Sales Managers
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Sales Managers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div  class="form-horizontal push-30">
    <div class="content">
        <div class="row mb2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6 text-right">
                <a class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#create_manager_modal">Add New</a>
            </div>
        </div>

        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content">
                        @if (count($sales_managers) > 0)
                            <table class="table table-hover table-borderless table-striped table-vcenter">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Non Shopify User</th>
                                    <th></th>
                                </tr>
                                </thead>

                                @foreach($sales_managers as $index => $manager)
                                    <tbody class="">
                                    <tr>
                                        <td class="font-w600">{{ $manager->name }}</td>
                                        <td>
                                           {{$manager->email}}
                                        </td>

                                        <td>
                                            <span class="label label-success">Active</span>
                                        </td>
                                         <td>
                                             @if($manager->hasRole('non-shopify-users'))
                                                 <span class="label label-success">Yes</span>
                                             @else
                                                 <button onclick="window.location.href='{{route('sales-managers.set_manager_as_user',$manager->id)}}'"  class="btn btn-sm btn-warning">Set as Non-Shopify User</button>
                                                 @endif

                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('sales-managers.delete', $manager->id) }}"
                                               class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title=""
                                               data-original-title="Delete Manager"><i class="fa fa-times"></i></a>
                                        </td>

                                    </tr>
                                    </tbody>

                                @endforeach
                            </table>
                        @else
                            <p>No Sales Manager Found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="create_manager_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Sale Managers</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option">
                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                            </button>
                        </div>
                    </div>
                    <form id="create_manager_form" action="{{route('sales-managers.create')}}" method="post">
                        @csrf
                        <div class="block-content font-size-sm">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">Name</label>
                                        <input required class="form-control" type="text" id="manager_name" name="name"
                                               placeholder="Enter Sales Manager Title here">

                                </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">Email</label>
                                        <input required class="form-control" type="email" id="manager_email" name="email"
                                               placeholder="Enter Sales Manager Email here">

                                    </div>
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

@endsection
