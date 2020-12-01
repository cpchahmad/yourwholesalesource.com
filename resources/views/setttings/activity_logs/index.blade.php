@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Activity Logs
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Activity Logs</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <form class="js-form-icon-search push" action="" method="get">
            <div class="form-group">
                <div class="input-group">
                    <input type="user_search" class="form-control" placeholder="Search By User Name" value="{{$search}}" name="search" >
                    <select name="type_search" id="" class="form-control">
                        <option value="product">Product</option>
                        <option value="retailer_product">Retailer Product</option>
                        <option value="order">Order</option>
                        <option value="ticket">Ticket</option>
                        <option value="wishlist">Wishlist</option>
                        <option value="wallet">Wallet</option>
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        <a class="btn btn-danger" href="/admin/users/logs"> <i class="fa fa-times"></i> Clear </a>
                    </div>
                </div>
            </div>
        </form>
        <div class="block">
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th>User</th>
                            <th>Type</th>
                            <th>Item</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td class="font-w600" style="vertical-align: middle">
                                    @if($log->user_id == 0)
                                        WeFullFill(Admin)
                                    @else
                                        {{ $log->user->name }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success"> {{ $log->model_type }}</span>
                                </td>

                                <td style="vertical-align: middle">
                                    {{ $log->model_name }}
                                </td>
                                <td style="vertical-align: middle">
                                    {{ $log->action }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
