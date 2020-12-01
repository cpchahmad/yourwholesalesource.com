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
        <div class="block">
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th>User</th>
                            <th>Item</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td class="font-w600" style="vertical-align: middle">
                                    @if($log->user_id == 0)
                                        <span class="badge badge-success">WeFullFill(Admin)</span>
                                    @else
                                        <span class="badge badge-success">{{ $log->user->name }}</span>
                                    @endif
                                </td>
                                <td style="vertical-align: middle">
                                    @if($log->model_type == "App\Product")
                                        <a href="{{ route('product.view', $log->model_id) }}">
                                            {{ $log->product->title }}
                                        </a>
                                    @endif
                                </td>
                                <td style="vertical-align: middle">
                                    {{ $log->action }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
