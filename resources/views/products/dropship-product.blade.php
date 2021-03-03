@extends('layout.index')
@section('content')
    <style>
        iframe{
            width: 100%;
        }
    </style>
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    {{ \Illuminate\Support\Str::limit($product->title,20,'...') }}
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dropship Products</a>
                        </li>
                        <li class="breadcrumb-item">  {{ \Illuminate\Support\Str::limit($product->title,20,'...') }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block">
            <div class="block-content">
                <div class="row items-push">
                    <div class="col-sm-6">
                        <!-- Images -->
                        <div class="row js-gallery" >
                            <div class="col-md-12 mb2">
                                <a class="img-link img-link-zoom-in img-lightbox" href="{{asset('shipping-marks')}}/{{$product->has_images()->first()->image}}">
                                    <img class="img-fluid" data-src="{{asset('shipping-marks')}}/{{$product->has_images()->first()->image}}" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="clearfix" style="margin-top: 5px;width: 100%">

                            @if($product->quantity > 0)
                                @if($product->varaint_count($product) > 0 && count($product->hasVariants) > 0)
                                    <span class="h5">
                                        <span class="font-w600 text-success">IN STOCK</span><br><small>{{$product->varaint_count($product)}} Available in {{count($product->hasVariants)}} Variants</small>
                                    </span>
                                @elseif($product->quantity > 0)
                                    <span class="h5">
                                        <span class="font-w600 text-success">IN STOCK</span><br><small>{{$product->quantity}} Available  </small>
                                    </span>
                                @else
                                    <span class="h5">
                                        <span class="font-w600 text-danger">OUT OF STOCK</span><br><small>Not Available</small>
                                    </span>
                                @endif
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
                                    <a class="nav-link" href="#ecom-product-comments">Variants</a>
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
                                                             @if($variant->image == null)  data-src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                             data-src="{{asset('shipping-marks')}}/{{$variant->image}}" alt="">
                                                    </td>
                                                    <td class="variant_title">
                                                        {{ $variant->option }}
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
