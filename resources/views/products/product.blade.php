@extends('layout.index')
@section('content')
    <style>
        .img-avatar {
            border-radius: 0;
        }
        .mb2{
             margin-bottom: 10px !important;

        }
    </style>
    <div class="content">
        <div class="row mb2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('product.edit',$product->id) }}" class="btn btn-primary btn-square ">Edit Product</a>
            </div>
        </div>
        <div class="block">
            <div class="block-content">
                <div class="row items-push">
                    <div class="col-sm-6">
                        <!-- Images -->
                        <div class="row js-gallery">
                            <?php
                            if(count($product->has_images) > 0){
                                $images = $product->has_images;
                            }
                            else{
                                $images = [];
                            }

                            ?>

                            <div class="col-xs-12 push-10">
                                @if(count($images) > 0)
                                    @if($images[0]->isV == 0)
                                        <a class="img-link" href="{{asset('images')}}/{{$images[0]->image}}">
                                            <img class="img-responsive" src="{{asset('images')}}/{{$images[0]->image}}" alt="">
                                        </a>
                                    @else
                                        <a class="img-link" href="{{asset('images/variants')}}/{{$images[0]->image}}">
                                            <img class="img-responsive" src="{{asset('images/variants')}}/{{$images[0]->image}}" alt="">
                                        </a>
                                    @endif

                                @endif
                            </div>
                            @if(count($images) > 0)
                                @foreach($images as $image)
                                    <div class="col-xs-4">
                                        @if($image->isV == 0)
                                            <a class="img-link" href="{{asset('images')}}/{{$image->image}}">
                                                <img class="img-responsive" src="{{asset('images')}}/{{$image->image}}" alt="">
                                            </a>
                                        @else
                                            <a class="img-link" href="{{asset('images/variants')}}/{{$image->image}}">
                                                <img class="img-responsive" src="{{asset('images/variants')}}/{{$image->image}}" alt="">
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
                                {{$product->title}} <span @if($product->fulfilled_by == 'AliExpress') class="badge badge-info" @else class="badge badge-primary" @endif >{{$product->fulfilled_by}}</span>
                            </a>
                        </h2>
                        <div class="clearfix" style="margin-top: 5px">
                            <div class="pull-right" style="display: flex">
                                <span class="h2 font-w700 text-success">${{number_format($product->price,2)}} </span>
{{--                                <span style="font-size: 17px;top: 23px;position: absolute;right: 17px;"  class="font-w100 text-danger"><del>${{number_format($product->compare_price,2)}}</del></span>--}}
                            </div>
                            @if($product->quantity > 0)
                                <span class="h5">
                                <span class="font-w600 text-success">IN STOCK</span><br><small>{{$product->varaint_count($product)}} Available in {{count($product->hasVariants)}} Variants</small>
                            </span>
                            @else
                                <span class="h5">
                                <span class="font-w600 text-danger">OUT OF STOCK</span><br><small>Not Available</small>
                            </span>
                            @endif
                        </div>
                        <hr>
                        <p>{!! $product->description !!}</p>
                        <!-- END Vital Info -->
                    </div>
                    <div class="col-xs-12">
                        <!-- Extra Info -->
                        <div class="block">
                            <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs">
                                {{--                                <li class="active">--}}
                                {{--                                    <a href="#ecom-product-info">Info</a>--}}
                                {{--                                </li>--}}
                                <li class="active">
                                    <a href="#ecom-product-comments">Varaints</a>
                                </li>
                                {{--                                <li>--}}
                                {{--                                    <a href="#ecom-product-reviews">Reviews</a>--}}
                                {{--                                </li>--}}
                            </ul>
                            <div class="block-content tab-content">
                                <!-- Info -->
                            {{--                                <div class="tab-pane pull-r-l active" id="ecom-product-info">--}}
                            {{--                                    <table class="table table-striped table-borderless remove-margin-b">--}}
                            {{--                                        <thead>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <th colspan="2">File Formats</th>--}}
                            {{--                                        </tr>--}}
                            {{--                                        </thead>--}}
                            {{--                                        <tbody>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <td style="width: 20%;">Sketch</td>--}}
                            {{--                                            <td>--}}
                            {{--                                                <i class="fa fa-check text-success"></i>--}}
                            {{--                                            </td>--}}
                            {{--                                        </tr>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <td>PSD</td>--}}
                            {{--                                            <td>--}}
                            {{--                                                <i class="fa fa-check text-success"></i>--}}
                            {{--                                            </td>--}}
                            {{--                                        </tr>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <td>PDF</td>--}}
                            {{--                                            <td>--}}
                            {{--                                                <i class="fa fa-times text-danger"></i>--}}
                            {{--                                            </td>--}}
                            {{--                                        </tr>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <td>AI</td>--}}
                            {{--                                            <td>--}}
                            {{--                                                <i class="fa fa-check text-success"></i>--}}
                            {{--                                            </td>--}}
                            {{--                                        </tr>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <td>EPS</td>--}}
                            {{--                                            <td>--}}
                            {{--                                                <i class="fa fa-check text-success"></i>--}}
                            {{--                                            </td>--}}
                            {{--                                        </tr>--}}
                            {{--                                        </tbody>--}}
                            {{--                                    </table>--}}
                            {{--                                    <table class="table table-striped table-borderless remove-margin-b">--}}
                            {{--                                        <thead>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <th colspan="2">Extra</th>--}}
                            {{--                                        </tr>--}}
                            {{--                                        </thead>--}}
                            {{--                                        <tbody>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <td style="width: 20%;"><i class="fa fa-calendar text-muted push-5-r"></i> Date</td>--}}
                            {{--                                            <td>January 15, 2016</td>--}}
                            {{--                                        </tr>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <td><i class="fa fa-anchor text-muted push-5-r"></i> File Size</td>--}}
                            {{--                                            <td>265.36 MB</td>--}}
                            {{--                                        </tr>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <td><i class="si si-vector text-muted push-5-r"></i> Vector</td>--}}
                            {{--                                            <td>--}}
                            {{--                                                <i class="fa fa-check text-success"></i>--}}
                            {{--                                            </td>--}}
                            {{--                                        </tr>--}}
                            {{--                                        <tr>--}}
                            {{--                                            <td><i class="fa fa-expand text-muted push-5-r"></i> Dimensions</td>--}}
                            {{--                                            <td>--}}
                            {{--                                                <div class="push-5">16 x 16 px</div>--}}
                            {{--                                                <div class="push-5">32 x 32 px</div>--}}
                            {{--                                                <div class="push-5">64 x 64 px</div>--}}
                            {{--                                                <div class="push-5">128 x 128 px</div>--}}
                            {{--                                                <div>256 x 256 px</div>--}}
                            {{--                                            </td>--}}
                            {{--                                        </tr>--}}
                            {{--                                        </tbody>--}}
                            {{--                                    </table>--}}
                            {{--                                </div>--}}
                            <!-- END Info -->

                                <!-- Comments -->
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
                                <!-- END Comments -->

                                <!-- Reviews -->
                            {{--                                <div class="tab-pane pull-r-l" id="ecom-product-reviews">--}}
                            {{--                                    <!-- Average Rating -->--}}
                            {{--                                    <div class="block block-rounded">--}}
                            {{--                                        <div class="block-content bg-gray-lighter text-center">--}}
                            {{--                                            <p class="h2 text-warning push-10">--}}
                            {{--                                                <i class="fa fa-star"></i>--}}
                            {{--                                                <i class="fa fa-star"></i>--}}
                            {{--                                                <i class="fa fa-star"></i>--}}
                            {{--                                                <i class="fa fa-star"></i>--}}
                            {{--                                                <i class="fa fa-star"></i>--}}
                            {{--                                            </p>--}}
                            {{--                                            <p>--}}
                            {{--                                                <strong>5.0</strong>/5.0 out of <strong>5</strong> Ratings--}}
                            {{--                                            </p>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <!-- END Average Rating -->--}}

                            {{--                                    <!-- Ratings -->--}}
                            {{--                                    <div class="media push-15">--}}
                            {{--                                        <div class="media-left">--}}
                            {{--                                            <a href="javascript:void(0)">--}}
                            {{--                                                <img class="img-avatar img-avatar32" src="assets/img/avatars/avatar10.jpg" alt="">--}}
                            {{--                                            </a>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="media-body font-s13">--}}
                            {{--                                                                <span class="text-warning">--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                </span>--}}
                            {{--                                            <a class="font-w600" href="javascript:void(0)">George Stone</a>--}}
                            {{--                                            <div class="push-5">Awesome Quality!</div>--}}
                            {{--                                            <div class="font-s12">--}}
                            {{--                                                <span class="text-muted"><em>2 hours ago</em></span>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="media push-15">--}}
                            {{--                                        <div class="media-left">--}}
                            {{--                                            <a href="javascript:void(0)">--}}
                            {{--                                                <img class="img-avatar img-avatar32" src="assets/img/avatars/avatar8.jpg" alt="">--}}
                            {{--                                            </a>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="media-body font-s13">--}}
                            {{--                                                                <span class="text-warning">--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                </span>--}}
                            {{--                                            <a class="font-w600" href="javascript:void(0)">Judy Alvarez</a>--}}
                            {{--                                            <div class="push-5">So cool badges!</div>--}}
                            {{--                                            <div class="font-s12">--}}
                            {{--                                                <span class="text-muted"><em>5 hours ago</em></span>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="media push-15">--}}
                            {{--                                        <div class="media-left">--}}
                            {{--                                            <a href="javascript:void(0)">--}}
                            {{--                                                <img class="img-avatar img-avatar32" src="assets/img/avatars/avatar12.jpg" alt="">--}}
                            {{--                                            </a>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="media-body font-s13">--}}
                            {{--                                                                <span class="text-warning">--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                </span>--}}
                            {{--                                            <a class="font-w600" href="javascript:void(0)">Jack Greene</a>--}}
                            {{--                                            <div class="push-5">They look great, thank you!</div>--}}
                            {{--                                            <div class="font-s12">--}}
                            {{--                                                <span class="text-muted"><em>15 hours ago</em></span>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="media push-15">--}}
                            {{--                                        <div class="media-left">--}}
                            {{--                                            <a href="javascript:void(0)">--}}
                            {{--                                                <img class="img-avatar img-avatar32" src="assets/img/avatars/avatar16.jpg" alt="">--}}
                            {{--                                            </a>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="media-body font-s13">--}}
                            {{--                                                                <span class="text-warning">--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                </span>--}}
                            {{--                                            <a class="font-w600" href="javascript:void(0)">Craig Stone</a>--}}
                            {{--                                            <div class="push-5">Badges for life!</div>--}}
                            {{--                                            <div class="font-s12">--}}
                            {{--                                                <span class="text-muted"><em>20 hours ago</em></span>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="media push-15">--}}
                            {{--                                        <div class="media-left">--}}
                            {{--                                            <a href="javascript:void(0)">--}}
                            {{--                                                <img class="img-avatar img-avatar32" src="assets/img/avatars/avatar5.jpg" alt="">--}}
                            {{--                                            </a>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="media-body font-s13">--}}
                            {{--                                                                <span class="text-warning">--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                    <i class="fa fa-star"></i>--}}
                            {{--                                                                </span>--}}
                            {{--                                            <a class="font-w600" href="javascript:void(0)">Linda Moore</a>--}}
                            {{--                                            <div class="push-5">So good, keep it up!</div>--}}
                            {{--                                            <div class="font-s12">--}}
                            {{--                                                <span class="text-muted"><em>22 hours ago</em></span>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <!-- END Ratings -->--}}
                            {{--                                </div>--}}
                            <!-- END Reviews -->
                            </div>
                        </div>
                        <!-- END Extra Info -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
