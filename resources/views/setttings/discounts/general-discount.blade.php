@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    General Discount Preferences
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">General Discount Preferences</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                {{--Tiered Pricing applying section start--}}
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">General Discount Preferences</h3>
                    </div>
                    <form action="{{ route('save.general.discount.preferences') }}" method="post">
                        @csrf
                        <div class="block-content">
                            <div class="form-group">
                                <label class="">Discount(%)</label>
                                <input type="text" class="form-control" name="discount" value="{{ \App\GeneralDiscountPreferences::first()->discount_amount }}" placeholder="Enter the discount %, you wants to apply">
                            </div>

                            <div class="form-group">
                                <label class="">Preferences</label>
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" class="custom-control-input preference-check" id="prefer-global" name="global" value="1" @if(\App\GeneralDiscountPreferences::first()->global == 1) checked="" @endif>
                                    <label class="custom-control-label " for="prefer-global">Global</label>
                                </div>
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" class="custom-control-input preference-check" id="prefer-store" name="global" value="0"  @if(\App\GeneralDiscountPreferences::first()->global == 0) checked="" @endif>
                                    <label class="custom-control-label" for="prefer-store">Selected Stores / Users</label>
                                </div>
                            </div>

                            <div class="form-group" @if(\App\GeneralDiscountPreferences::first()->global == 1) style="display: none" @endif>
                                <div class="form-material">
                                    <label for="material-error">Stores <i class="fa fa-question-circle"  title="Store where tiered pricing should be applied."> </i></label>
                                    <select class="form-control shop-preference js-select2" style="width: 100%;" data-placeholder="Choose multiple markets.." name="shops[]"   multiple="">
                                        <option></option>

                                        @foreach($shops as $shop)
                                            <option
                                                @php
                                                    $stores = \App\GeneralDiscountPreferences::first()->stores_id;
                                                    if($stores != null) {
                                                        $store_array= json_decode($stores);
                                                        if(in_array($shop->id, $store_array))
                                                            echo "selected";
                                                    }
                                                @endphp
                                                value="{{$shop->id}}">{{explode('.',$shop->shopify_domain)[0]}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-material mt-2">
                                    <label for="material-error">Non Shopify Users <i class="fa fa-question-circle"  title="Non-shopify stores where tiered pricing should be applied."> </i></label>
                                    <select class="form-control non-shopify-user-preference js-select2" style="width: 100%;" data-placeholder="Choose multiple markets.." name="non_shopify_users[]"   multiple="">
                                        <option></option>
                                        @foreach($non_shopify_users as $user)
                                            <option
                                                @php
                                                    $users = \App\GeneralDiscountPreferences::first()->users_id;
                                                    if($users != null) {
                                                        $users_array= json_decode($users);
                                                        if(in_array($user->id, $users_array))
                                                            echo "selected";
                                                    }
                                                @endphp
                                                value="{{$user->id}}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="text-right my-3 ">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                {{--Tiered Pricing applying section end--}}

                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">General Fixed Price Preferences</h3>
                    </div>
                    <form action="{{ route('save.general.fixed.preferences') }}" method="post">
                        @csrf
                        <div class="block-content">
                            <div class="form-group">
                                <label class="">Fixed($)</label>
                                <input type="text" class="form-control" name="discount" value="{{ \App\GeneralFixedPricePreferences::first()->fixed_amount }}" placeholder="Enter the fixed price, you wants to apply">
                            </div>

                            <div class="form-group">
                                <label class="">Preferences</label>
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" class="custom-control-input preference-fixed" id="prefer-global-fixed" name="global" value="1" @if(\App\GeneralFixedPricePreferences::first()->global == 1) checked="" @endif>
                                    <label class="custom-control-label" for="prefer-global-fixed">Global</label>
                                </div>
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" class="custom-control-input preference-fixed" id="prefer-store-fixed" name="global" value="0"  @if(\App\GeneralFixedPricePreferences::first()->global == 0) checked="" @endif>
                                    <label class="custom-control-label" for="prefer-store-fixed">Selected Stores / Users</label>
                                </div>
                            </div>

                            <div class="form-group" @if(\App\GeneralFixedPricePreferences::first()->global == 1) style="display: none" @endif>
                                <div class="form-material">
                                    <label for="material-error">Stores <i class="fa fa-question-circle"  title="Store where tiered pricing should be applied."> </i></label>
                                    <select class="form-control shop-preference js-select2" style="width: 100%;" data-placeholder="Choose multiple markets.." name="shops[]"   multiple="">
                                        <option></option>

                                        @foreach($shops as $shop)
                                            <option
                                                @php
                                                    $stores = \App\GeneralFixedPricePreferences::first()->stores_id;
                                                    if($stores != null) {
                                                        $store_array= json_decode($stores);
                                                        if(in_array($shop->id, $store_array))
                                                            echo "selected";
                                                    }
                                                @endphp
                                                value="{{$shop->id}}">{{explode('.',$shop->shopify_domain)[0]}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-material mt-2">
                                    <label for="material-error">Non Shopify Users <i class="fa fa-question-circle"  title="Non-shopify stores where tiered pricing should be applied."> </i></label>
                                    <select class="form-control non-shopify-user-preference js-select2" style="width: 100%;" data-placeholder="Choose multiple markets.." name="non_shopify_users[]"   multiple="">
                                        <option></option>
                                        @foreach($non_shopify_users as $user)
                                            <option
                                                @php
                                                    $users = \App\GeneralFixedPricePreferences::first()->users_id;
                                                    if($users != null) {
                                                        $users_array= json_decode($users);
                                                        if(in_array($user->id, $users_array))
                                                            echo "selected";
                                                    }
                                                @endphp
                                                value="{{$user->id}}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
