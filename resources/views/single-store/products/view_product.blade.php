@extends('layout.single')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    {{ $product->title }}
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Products</a>
                        </li>
                        <li class="breadcrumb-item">{{ $product->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row mb2">
            <div class="col-md-9">
            </div>
            <div class="col-md-3 text-right">
                @if(!in_array($product->id,$shop->has_imported->pluck('id')->toArray()))
                    <a href="{{route('store.product.wefulfill.add-to-import-list',$product->id)}}" class="btn btn-primary btn-square ">Add to Import List</a>
                @endif
            </div>
        </div>
        <div class="block">
            <div class="block-content">
                <div class="row items-push">
                    <div class="col-sm-6">
                        <!-- Images -->
                        <div class="row js-gallery" >
                            <?php
                            if(count($product->has_images) > 0){
                                $images = $product->has_images;
                            }
                            else{
                                $images = [];
                            }

                            ?>

                            <div class="col-md-12 mb2">
                                @if(count($images) > 0)
                                    @if($images[0]->isV == 0)
                                        <a class="img-link img-link-zoom-in img-lightbox" href="{{asset('images')}}/{{$images[0]->image}}">
                                            <img class="img-fluid" src="{{asset('images')}}/{{$images[0]->image}}" alt="">
                                        </a>
                                    @else
                                        <a class="img-link img-link-zoom-in img-lightbox" href="{{asset('images/variants')}}/{{$images[0]->image}}">
                                            <img class="img-fluid" src="{{asset('images/variants')}}/{{$images[0]->image}}" alt="">
                                        </a>
                                    @endif

                                @endif
                            </div>
                            @if(count($images) > 0)
                                @foreach($images as $image)
                                    <div class="col-md-4">
                                        @if($image->isV == 0)
                                            <a class="img-link img-link-zoom-in img-lightbox" href="{{asset('images')}}/{{$image->image}}">
                                                <img class="img-fluid" src="{{asset('images')}}/{{$image->image}}" alt="">
                                            </a>
                                        @else
                                            <a class="img-link img-link-zoom-in img-lightbox" href="{{asset('images/variants')}}/{{$image->image}}">
                                                <img class="img-fluid" src="{{asset('images/variants')}}/{{$image->image}}" alt="">
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <hr>
                        @if(count($product->has_categories) > 0)
                            <div class="tags" style="margin-top: 5px">
                                @if($product->tags != null)
                                    <h4 style="margin-bottom: 5px">Categories</h4>
                                    @foreach($product->has_categories as $cat)
                                        <span class="badge badge-primary">{{$cat->title}}</span>
                                    @endforeach
                                @endif
                            </div>
                            <hr>
                        @endif
                        @if(count($product->has_subcategories) > 0)
                            <div class="tags" style="margin-top: 5px">
                                <h4 style="margin-bottom: 5px">Subcategories</h4>
                                @foreach($product->has_subcategories as $subcat)
                                    <span class="badge badge-primary">{{$subcat->title}}</span>
                                @endforeach
                            </div>
                            <hr>
                        @endif

                        @if($product->tags != null)
                            <div class="tags" style="margin-top: 5px">

                                <h4 style="margin-bottom: 5px">Tags</h4>
                                @foreach(explode(',',$product->tags) as $tag)
                                    <span class="badge badge-info">{{$tag}}</span>
                                @endforeach
                            </div>

                    @endif
                    <!-- END Images -->
                    </div>
                    <div class="col-sm-6">
                        <!-- Vital Info -->
                        <h2>
                            <a href="{{route('product.edit',$product->id)}}">
                                {{$product->title}} <span @if($product->fulfilled_by == 'AliExpress') class="badge badge-info" @else class="badge badge-primary" @endif  style="font-size: 12px;vertical-align: super">{{$product->fulfilled_by}}</span>
                            </a>
                        </h2>
                        <div class="clearfix" style="margin-top: 5px;width: 100%">

                            @if($product->quantity > 0)
                                <span class="h5">
                                <span class="font-w600 text-success">IN STOCK</span><br><small>{{$product->varaint_count($product)}} Available in {{count($product->hasVariants)}} Variants</small>
                            </span>
                            @else
                                <span class="h5">
                                <span class="font-w600 text-danger">OUT OF STOCK</span><br><small>Not Available</small>
                            </span>
                            @endif
                            <div class="text-right d-inline-block" style="float: right">
                                <span class="h3 font-w700 text-success">${{number_format($product->price,2)}} </span>
                            </div>
                        </div>
                        <hr>
                        <p>{!! $product->description !!}</p>
                        <!-- END Vital Info -->
                    </div>
                    <div class="col-md-12">
                        <!-- Extra Info -->
                        <div class="block">
                            <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs">
                                <li class="nav-item active">
                                    <a class="nav-link" href="#ecom-product-comments">Varaints</a>
                                </li>
                            </ul>
                            <div class="block-content tab-content">
                                <div class="tab-pane pull-r-l active" id="ecom-product-comments">
                                    @if(count($product->hasVariants) > 0)
                                        <table class="table table-striped table-borderless remove-margin-b">
                                            <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Title</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Cost</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($product->hasVariants as $index => $variant)
                                                <tr>
                                                    <td>
                                                        <img class="img-avatar img-avatar-variant" style="border: 1px solid whitesmoke" data-form="#varaint_image_form_{{$index}}" data-input=".varaint_file_input"
                                                             @if($variant->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                             @else src="{{asset('images/variants')}}/{{$variant->has_image->image}}" @endif alt=""></td>
                                                    <td>
                                                        {{$variant->title}}
                                                    </td>
                                                    <td>
                                                        @if($variant->quantity >0)
                                                            {{$variant->quantity}}
                                                        @else
                                                            Out of Stock
                                                        @endif
                                                    </td>
                                                    <td>${{number_format($variant->price,2)}}</td>
                                                    <td>${{number_format($variant->cost,2)}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p>This Product has Zero Variants</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- END Extra Info -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

