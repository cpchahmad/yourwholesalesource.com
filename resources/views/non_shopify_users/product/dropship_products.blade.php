@extends('layout.shopify')
@section('content')

    <style>
        .marketing_video iframe{
            width: 100%;
        }
        .push-20 a{
            margin: 3px;
        }
    </style>

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    My Dropship Products
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">My Dropship Products</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <form class="js-form-icon-search push" action="{{route('users.product.dropship')}}" method="get">
            <div class="form-group">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Search by title, tags keyword" value="{{$search}}" name="search">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                        <a class="btn btn-danger" href="{{route('users.product.dropship')}}"> <i class="fa fa-times"></i> Clear </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="row" style="margin-top: 20px">
            @if(count($products) > 0)
                @foreach($products as $index => $product)
                    <div class="col-sm-4 col-lg-3">
                        <div class="block">
                            <div class="options-container">
                                <a href="{{route('users.product.wefulfill.show',$product->id)}}">
                                    @if(count($product->has_images) > 0)
                                        <img class="img-fluid options-item" data-src="{{asset('shipping-marks')}}/{{$product->has_images()->first()->image}}" alt="">
                                    @else
                                        <img class="img-fluid options-item" data-src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                    @endif

                                </a>
                                <div class="options-overlay bg-black-75">
                                    <div class="options-overlay-content">
                                        <div class="push-20">
                                            <a class="btn btn-sm btn-primary" href="{{route('users.product.dropship.show',$product->id)}}">View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="block-content" style="padding-bottom: 10px">
                                <div class="push-10">
                                    <a class="h6" style="font-size: 0.9rem" href="{{route('users.product.dropship.show',$product->id)}}">{{$product->title}}</a>
                                    <div class="d-flex">
                                        <div class="font-w600 text-success mt-1">
                                            From.
                                            @if(count($product->hasVariants) > 0)
                                                ${{ number_format($product->hasVariants->min('price'), 2) }}
                                            @else
                                                ${{ number_format($product->price, 2) }}
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <button onclick="window.location.href='{{route('users.product.dropship.show',$product->id)}}'" class="btn btn-primary btn-block mb2">View Product</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <div class="block">
                        <div class="block-content ">
                            <p class="text-center"> No Product Found !</p>
                        </div>
                    </div>
                </div>

            @endif

        </div>
        <div class="row">
            <div class="col-md-12 text-center" style="font-size: 17px">
                {!! $products->appends(request()->input())->links() !!}
            </div>
        </div>
    </div>


@endsection
