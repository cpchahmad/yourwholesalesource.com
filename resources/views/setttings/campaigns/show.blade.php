@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    {{ $campaign->title }}
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
                                </tr>
                                </thead>

                                @foreach($campaign->users as $user)
                                    <tbody class="">
                                    <tr>
                                        <td class="font-w600">{{ $user->name }}</td>
                                        <td class="font-w600">{{ $user->email }}</td>
                                        <td><span class="badge @if($user->pivot->status === null) badge-primary @else badge-success @endif">{{ $campaign->users->pivot->status }}</span></td>
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
