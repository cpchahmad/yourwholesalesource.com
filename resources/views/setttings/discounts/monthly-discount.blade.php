@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Monthly Discount Settings
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Monthly Discount Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="block">
                    <form action="{{ route('save.monthly.discount.settings') }}" method="post">
                        @csrf
                        <div class="block-header d-flex justify-content-between">
                            <h3 class="block-title">Monthly Discount Settings</h3>
                        </div>

                        <div class="block-content">

                            <div class="form-group">
                                <label class="">Enable/ Disable Settings</label>
                                <div class="custom-control custom-switch custom-control-success mb-1">
                                    <input @if($settings && $settings->enable)checked="" @endif   type="checkbox" class="custom-control-input status-switch" id="enable_status" name="enable">
                                    <label class="custom-control-label" for="enable_status">@if($settings && $settings->enable) Enabled @else Disabled @endif</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="">Sales Target</label>
                                <input type="text" class="form-control" name="sales_target" value="{{ $settings ? $settings->sales_target : null }}" placeholder="Enter the monthly sales target..">
                            </div>

                            <div class="form-group">
                                <label class="">Discount(%)</label>
                                <input type="text" class="form-control" name="discount" value="{{ $settings ? $settings->discount : null }}" placeholder="Enter the discount %, you wants to apply">
                            </div>

                            <div class="text-right my-3 ">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
