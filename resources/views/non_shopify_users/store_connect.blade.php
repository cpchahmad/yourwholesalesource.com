@extends('layout.shopify')
@section('content')
    <style>
        .avatar-img {
            display: inline-block !important;
            width: 100px;
            height: 100px;

        }
    </style>
    <div class="content">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <!-- Bootstrap Lock -->
                <div class="block block-themed">
                        <div class="block-header bg-primary">
                            <h3 class="block-title">Store Connect</h3>
                        </div>
                        <div class="block-content pb-3">
                            <div class="text-center push-10-t push-30">
                                <img class="avatar-img" src="https://cdn3.iconfinder.com/data/icons/livico-b-shop-e-commerce/128/shopify_colored-02-512.png" alt="">
                            </div>
                            <div class="push-30">
                            <form method="POST" action="{{ route('authenticate') }}">
                                {{ csrf_field() }}
                                <div class="form-material" style="margin-bottom: 10px">
                                    <label for="shop">Store Domain</label>
                                    <input id="shop" name="shop" class="form-control" type="text" autofocus="autofocus" placeholder="example.myshopify.com">
                                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">

                                </div>

                                <button class="btn btn-primary" type="submit">Connect </button>
                            </form>
                            </div>
                        </div>
                    </div>
                <!-- END Bootstrap Lock -->
            </div>
        </div>
    </div>
@endsection
