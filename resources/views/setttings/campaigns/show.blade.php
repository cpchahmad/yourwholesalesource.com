@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    {{ $campaign->name }}
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Campaigns</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content">
                        @if (count($campaign->users) > 0)
                            <table class="js-table-sections table table-hover table-borderless table-vcenter">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>

                                @foreach($campaign->users as $user)
                                    <tbody class="">
                                    <tr>
                                        <td class="font-w600">{{ $user->name }}</td>
                                        <td class="font-w600">{{ $user->email }}</td>
                                        <td><span class="badge @if($user->pivot->status === null) badge-primary @else badge-success @endif">@if($user->pivot->status === null) Pending @else {{ $user->pivot->status }} @endif</span></td>
                                        <td class="text-right btn-group" style="float: right">
                                            <a href="{{ route('campaigns.remove.user', ['id' => $campaign->id, 'user_id' => $user->id]) }}"
                                               class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title=""
                                               data-original-title="Delete Campaign"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        @else
                            <p>No Receivers Yet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
