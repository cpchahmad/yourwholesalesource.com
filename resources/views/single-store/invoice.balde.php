@extends('layout.single')
@section('content')
<div class="bg-body-light">
    <div class="content content-full pt-2 pb-2">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h4 my-2">
                Invoice Zone
            </h1>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Invoice Zone</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@if(!isset($wallet) && !isset($user))
<div class="block ">
    <div class="block-content ">
        <p class="text-center"> No Account Associated With This Store Found ! <a href="{{route('store.index')}}"> Click Here For Account Association :) </a></p>
    </div>
</div>
@else
@if($wallet != null)
<div class="content">
    <div class="content-grid">
        <div class="row mb2">
            <div class="col-md-3">
                <div class="block ">
                    <div class="block-header">
                        <h3 class="block-title ">Available</h3>
                    </div>
                    <div class="block-content ">
                        <p class="font-size-h2"> {{number_format($wallet->available,2)}} USD</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="block ">
                    <div class="block-header">
                        <h3 class="block-title">Pending</h3>
                    </div>
                    <div class="block-content ">
                        <p class=" font-size-h2"> {{number_format($wallet->pending,2)}} USD</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="block ">
                    <div class="block-header">
                        <h3 class="block-title">Transferred</h3>
                    </div>
                    <div class="block-content ">
                        <p class="font-size-h2"> {{number_format($wallet->transferred,2)}} USD</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="block ">
                    <div class="block-header">
                        <h3 class="block-title">Used</h3>
                    </div>
                    <div class="block-content ">
                        <p class=" font-size-h2"> {{number_format($wallet->used,2)}} USD</p>
                    </div>
                </div>
            </div>
        </div>
        @include('inc.wallet_log')
    </div>
</div>
@endif
@endif
@endsection
