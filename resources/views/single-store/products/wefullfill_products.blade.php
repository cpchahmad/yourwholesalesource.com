@extends('layout.single')
@section('content')
    <style>
        .mb2{
            margin-bottom: 10px !important;
        }
        .pointer{
            cursor: pointer;
        }
        .pointer:hover{
            background-color: lightgray;
            color: white;
        }
        .padding10{
            padding: 5px !important;
        }
    </style>
    <div class="content">
        <div class="content-grid">
            <div class="row mb2">
                <h3 class="font-w700" style="display: contents">WeFullFill Products </h3>
                <div class="d-inline-block pull-right">
                    <a class="btn btn-block btn-danger" href="{{route('store.product.wefulfill')}}">Clear <i class="fa fa-close"></i></a>
                </div>
            </div>
            <form action="{{route('store.product.wefulfill')}}" method="get">
            <div class="row mb2 ">

                <div class="col-md-10">
                        <input type="search" class="form-control" placeholder="Search by title, tags keyword" name="search">
                </div>
                <div class="col-md-2">
                      <button class="btn btn-block btn-primary">Search</button>
                </div>

            </div>
            </form>
            <div class="row mb2">
                @foreach($categories as $index => $category)
                    @if($index < 11)
                        <div class="col-xs-3 before12 ">
                            <a href="{{route('store.product.wefulfill')}}?category={{$category->title}}">
                            <div class="block pointer">
                                <div class="block-content">
                                    <p>{{$category->title}}</p>
                                </div>
                            </div>
                            </a>
                        </div>
                    @endif
                    @if($index == 11)
                        <div class="col-xs-3 before12 see-more-block  ">
                            <div class="block pointer">
                                <div class="block-content">
                                    <p>See More ....</p>
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
    </div>

@endsection
