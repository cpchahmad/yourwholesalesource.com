@extends('layout.single')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    WeFullFill Products
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">WeFullFill Products</a>
                        </li>
{{--                        <li>--}}
{{--                            <a class="btn btn-block btn-danger" href="{{route('store.product.wefulfill')}}">Clear <i class="fa fa-close"></i></a>--}}
{{--                        </li>--}}
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <form class="js-form-icon-search push" action="{{route('store.product.wefulfill')}}" method="get">
            <div class="input-group input-group-lg">
                <input type="text" class="js-icon-search form-control font-size-base" name="search" placeholder="Search by title, tags keyword">
                <div class="input-group-append">
                    <button type="submit">
                    <span class="input-group-text">
                        <i class="fa fa-search"></i>
                    </span>
                    </button>
                </div>
            </div>
        </form>

            <div class="row mb-2" style="padding: 0 14px;">
                @foreach($categories as $index => $category)
                    @if($index < 11)
                        <div class="col-sm-3 p-0">
                            <a href="{{route('store.product.wefulfill')}}?category={{$category->title}}">
                            <div class="block m-0">
                                <div class="block-content p-3 text-center">
                                    <p class="m-0" style="font-size:14px;">{{$category->title}}</p>
                                </div>
                            </div>
                            </a>
                        </div>
                    @endif
                    @if($index == 11)
                        <div class="col-sm-3 p-0 see-more-block">
                            <div class="block">
                                <div class="block-content p-3 text-center">
                                    <p  class="m-0" style="font-size:14px;">See More ....</p>
                                </div>
                            </div>
                        </div>
                    @endif

                        @if($index >= 11)
                            <div class="col-xs-3 after12" style="display: none">
                                <a href="{{route('store.product.wefulfill')}}?category={{$category->title}}">
                                    <div class="block pointer">
                                        <div class="block-content">
                                            <p>{{$category->title}}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                        @if($index == count($categories)-1)
                            <div class="col-xs-3 after12 see-less-block" style="display: none">
                                <div class="block pointer">
                                    <div class="block-content">
                                        <p>See Less ....</p>
                                    </div>
                                </div>
                            </div>
                        @endif


                @endforeach
            </div>
            <div class="row" style="margin-top: 20px">
                @if(count($products) > 0)
                    @foreach($products as $index => $product)
                        <div class="col-sm-4 col-lg-3 padding10">
                            <div class="block">
                                <div class="img-container">
                                    <a href="{{route('store.product.wefulfill.show',$product->id)}}">
                                        <img class="img-responsive"
                                             @if(count($product->has_images) > 0)
                                             @if($product->has_images[0]->isV == 0)
                                             src="{{asset('images')}}/{{$product->has_images[0]->image}}"
                                             @else src="{{asset('images/variants')}}/{{$product->has_images[0]->image}}"
                                             @endif
                                             @else
                                             src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                             @endif
                                             alt="">
                                    </a>
                                    <div class="img-options">
                                        <div class="img-options-content">
                                            <div class="push-20">
                                                <a class="btn btn-sm btn-primary" href="{{route('store.product.wefulfill.show',$product->id)}}">View</a>

                                                @if(!in_array($product->id,$shop->has_imported->pluck('id')->toArray()))
                                                <a class="btn btn-sm btn-success" href="{{route('store.product.wefulfill.add-to-import-list',$product->id)}}">
                                                    <i class="fa fa-plus"></i> Add to Import List
                                                </a>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content" style="padding-bottom: 10px">
                                    <div class="push-10">
                                        <div class="h4 font-w600 text-success pull-right push-10-l">${{number_format($product->price,2)}}</div>
                                        <a class="h4" href="{{route('store.product.wefulfill.show',$product->id)}}">{{$product->title}}</a>
                                    </div>
                                    <p class="text-muted"> Category & Tags </p>
                                    @if(count($product->has_categories) > 0)
                                        <div class="tags" style="margin-top: 5px">
                                            @if($product->tags != null)
                                                @foreach($product->has_categories as $cat)
                                                    <span class="badge badge-primary">{{$cat->title}}</span>
                                                @endforeach
                                            @endif
                                        </div>

                                    @endif
                                    @if(count($product->has_subcategories) > 0)
                                        <div class="tags" style="margin-top: 5px">
                                            @foreach($product->has_subcategories as $subcat)
                                                <span class="badge badge-primary">{{$subcat->title}}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($product->tags != null)
                                        <div class="tags" style="margin-top: 5px">
                                            @foreach(explode(',',$product->tags) as $tag)
                                                <span class="badge badge-primary">{{$tag}}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                    <p class="text-muted">Shipping Price $50.00 / Estimated 7 Days Delivery /  <a class="" href="">Fast Shipping</a></p>
                                    <hr>
                                    @if(!in_array($product->id,$shop->has_imported->pluck('id')->toArray()))
                                    <button onclick="window.location.href='{{ route('store.product.wefulfill.add-to-import-list',$product->id)}}'" class="btn btn-primary btn-block mb2"><i class="fa fa-plus"></i> Add to Import List</button>
                                   @else
                                        <button disabled class="btn btn-success btn-block mb2"><i class="fa fa-check-circle-o"></i> Added to Import List</button>
                                    @endif
                                    <span class="mb2" style="color: grey">Fulfilled By WeFullFill</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card">
                        <div class="card-body">
                            <div class="block pointer">
                                <div class="block-content">
                                    <p>
                                        No Product Available !
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="row">
                <div class="col-md-12 text-center" style="font-size: 17px">
                    {!! $products->links() !!}
                </div>
            </div>
        </div>


@endsection
