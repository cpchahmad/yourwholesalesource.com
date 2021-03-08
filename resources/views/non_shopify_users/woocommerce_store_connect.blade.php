@extends('layout.shopify')
@section('content')
    <style>
        .avatar-img {
            display: inline-block !important;
            width: 100px;
            height: 100px;

        }
    </style>
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                  Store Connect

                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Stores</a>
                        </li>
                        <li class="breadcrumb-item">
                          Woocommerce Store Connect
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <!-- Bootstrap Lock -->
                <div class="block block-themed">
                        <div class="block-header bg-primary">
                            <h3 class="block-title">Woocommerce Store Connect</h3>
                        </div>
                        <div class="block-content pb-3">
                            <div class="text-center push-10-t push-30">
                                <img class="avatar-img" src="https://download.logo.wine/logo/WooCommerce/WooCommerce-Logo.wine.png" alt="">
                            </div>
                            <div class="push-30">
                            <form method="POST" action="{{ route('store.user.authenticate.woocommerce') }}">
                                {{ csrf_field() }}
                                <div class="form-material" style="margin-bottom: 10px">
                                    <label for="shop">Store Domain</label>
                                    <input id="shop" name="shop_url" class="form-control @error('shop_url') is-invalid @enderror" type="text" autofocus="autofocus" placeholder="example.com">
                                    @error('shop_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <label for="consumer_key" class="mt-2">Consumer Key</label>
                                    <input id="consumer_key" name="consumer_key" class="form-control @error('consumer_key') is-invalid @enderror" type="text"  placeholder="Consumer Key">
                                    @error('consumer_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <label for="consumer_secret" class="mt-2">Consumer Secret</label>
                                    <input id="consumer_secret" name="consumer_secret" class="form-control @error('consumer_secret') is-invalid @enderror" type="text"  placeholder="Consumer Secret">
                                    @error('consumer_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit">Connect </button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                <!-- END Bootstrap Lock -->
            </div>
        </div>
    </div>
@endsection
