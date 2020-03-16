@extends('layout.index')
@section('content')
    <style>
        div.tagsinput span.tag {
            padding: 2px 5px;
            height: 22px;
            line-height: 18px;
            color: #fff;
            font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight: 600;
            background-color: #5c90d2;
            border: none;
        }

        div.tagsinput span.tag a {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
        }
        .swal2-popup {
            font-size: 1.5rem !important;
        }
        .img-avatar {
            display: inline-block !important;
            width: 50px !important;
            height: 50px !important;
            border-radius: 0;
        }
    </style>
    <div data-action="{{ route('product.update', $product->id) }}" class="form-horizontal push-30" data-method="post" data-enctype="multipart/form-data">
        <div class="content edit-content" data-route="{{route('product.update', $product->id)}}">
            <div class="row" style="margin-bottom: 10px">
                <div class="col-sm-6">
                    <h3 class="font-w700">Update Product</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('product.view',$product->id) }}" class="btn btn-primary btn-square ">Preview</a>
                    <a href="{{ route('product.create') }}" class="btn btn-success btn-square ">Add New</a>
                </div>
            </div>
            <!-- Info -->
            <div class="row">
                <div id="forms-div">
                    <div class="col-sm-8">
                        <div class="block">
                            <form action="{{route('product.update',$product->id)}}" method="post">
                                @csrf
                                <input type="hidden" name="type" value="basic-info">
                                <div class="block-content block-content-full">

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label for="product-name">Title</label>
                                            <input class="form-control" type="text" id="product-name" name="title"
                                                   value="{{$product->title}}"  placeholder="Short Sleeve Shirt" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <div class="form-material form-material-primary">
                                                <label>Description</label>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                    <textarea class="js-summernote" name="description"
                                              placeholder="Please Enter Description here !">{{$product->description}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="block">
                            <div class="block-header">
                                <h3 class="block-title">Images</h3>
                            </div>
                            <div class="block-content">
                                @if(count($product->has_images) >0)
                                    <div class="row editable">

                                        @foreach($product->has_images as $image)
                                            <div class="col-lg-4 preview-image animated fadeIn">
                                                <div class="img-container fx-img-zoom-in fx-opt-slide-right">
                                                    @if($image->isV == 0)
                                                    <img class="img-responsive" src="{{asset('images')}}/{{$image->image}}" alt="">
                                                    @else
                                                        <img class="img-responsive" src="{{asset('images/variants')}}/{{$image->image}}" alt="">
                                                        @endif
                                                    <div class="img-options">
                                                        <div class="img-options-content">
                                                            <a class="btn btn-sm btn-default delete-file" data-type="existing-product-image-delete" data-token="{{csrf_token()}}" data-route="{{route('product.update',$product->id)}}" data-file="{{$image->id}}"><i class="fa fa-times"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                    <hr>
                                @endif
{{--                                <div class="row preview-drop"></div>--}}
                                <div class="row">
                                    <form class="product-images-form " action="{{route('product.update',$product->id)}}" method="post" enctype="multipart/form-data">
                                      @csrf
                                        <input type="hidden" name="type" value="existing-product-image-add">
                                    <div class="{{--col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3--}} col-md-12 " style="padding-bottom: 13px;">
                                        <div class="dropzone dz-clickable">
                                            <div class="dz-default dz-message"><span>Click here to upload images.</span></div>
                                            <div class="row preview-drop"></div>
                                        </div>
                                        <input style="display: none" type="file"  name="images[]" accept="image/*" class="push-30-t push-30 dz-clickable images-upload" multiple required>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="block">
                            <div class="block-header">
                                <h3 class="block-title">Pricing</h3>
                            </div>
                            <form action="{{route('product.update',$product->id)}}" method="post">
                                @csrf
                                <input type="hidden" name="type" value="pricing">
                                <div class="block-content">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-xs-12 push-10">
                                                    <label>Price</label>
                                                    <input type="text" class="form-control" name="price"
                                                           value="{{$product->price}}"  placeholder="$ 0.00" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-xs-12 ">
                                                    <label>Compare at Price</label>
                                                    <input type="text" class="form-control" name="compare_price"
                                                           value="{{$product->compare_price}}"   placeholder="$ 0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="col-xs-12 ">
                                                    <label>Cost Per Item</label>
                                                    <input type="text" class="form-control" name="cost"
                                                           value="{{$product->cost}}"  placeholder="$ 0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-xs-12 ">
                                                    <label>Quantity</label>
                                                    <input type="text" class="form-control" name="quantity" value="{{$product->quantity}}" placeholder="0" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-xs-12 ">
                                                    <label>Weight</label>
                                                    <input type="text" class="form-control" value="{{$product->weight}}" name="weight" placeholder="0.0Kg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="col-xs-12 ">
                                                    <label>SKU</label>
                                                    <input type="text" class="form-control" name="sku" value="{{$product->sku}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">

                                                <div class="col-xs-12 ">
                                                    <label>Barcode</label>
                                                    <input type="text" class="form-control" value="{{$product->barcode}}" name="barcode">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="col-sm-4">
                        <div class="block">
                            <div class="block-header">
                                <div class="block-title">
                                    Mark as Fulfilled
                                </div>
                            </div>
                            <form action="{{route('product.update',$product->id)}}" method="post">
                                @csrf
                                <input type="hidden" name="type" value="fulfilled">
                                <div class="block-content" >
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label class="css-input css-radio css-radio-primary">
                                                <input @if($product->fulfilled_by == 'Fantasy') checked @endif type="radio" name="fulfilled-by" value="Fantasy" ><span></span> By Fantasy Supplier
                                            </label>
                                        </div>
                                        <div class="col-xs-12">
                                            <label class="css-input css-radio  css-radio-primary push-10-r">
                                                <input @if($product->fulfilled_by == 'AliExpress') checked @endif type="radio" name="fulfilled-by" value="AliExpress" ><span></span> By AliExpress
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="block">
                            <div class="block-header">
                                <div class="block-title">
                                    Product Category
                                </div>
                                <form action="{{route('product.update',$product->id)}}" method="post">
                                    @csrf
                                    <input type="hidden" name="type" value="category">
                                    <div class="block-content" style="height: 200px;overflow: auto;overflow-x: hidden;">
                                        <div class="form-group product_category">
                                            @foreach($categories as $category)
                                                <span class="category_down" data-value="0" style="margin-right: 5px;font-size: 16px"> <i class="fa fa-angle-right"></i></span>
                                                <label class="css-input css-checkbox css-checkbox-primary">
                                                    <input type="checkbox" name="category[]" class="category_checkbox"
                                                         @if(in_array($category->id,$product->category($product))) checked @endif  value="{{ $category->id }}"><span></span>{{ $category->title }}
                                                </label>
                                                <div class="row product_sub_cat" style="display: none">
                                                    <div class="col-xs-12 col-xs-push-1">
                                                        @foreach($category->hasSub as $sub)
                                                            <label class="css-input css-checkbox css-checkbox-primary">
                                                                <input type="checkbox" class="sub_cat_checkbox" name="sub_cat[]"
                                                                       @if(in_array($sub->id,$product->subcategory($product))) checked @endif
                                                                       value="{{ $sub->id }}"><span></span>{{ $sub->title }}
                                                            </label>
                                                            <br>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <br>
                                            @endforeach
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="block">
                            <div class="block-header">
                                <h3 class="block-title">Organization</h3>
                            </div>
                            <form action="{{route('product.update',$product->id)}}" method="post">
                                @csrf
                                <input type="hidden" name="type" value="organization">
                                <div class="block-content">
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <label>Product Type</label>
                                            <input type="text" class="form-control" name="product_type"
                                                 value="{{$product->type}}"  placeholder="eg. Shirts" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <label>Vendor</label>
                                            <input type="text" class="form-control" name="vendor" placeholder="eg. Nike"
                                                   value="{{$product->vendor}}"   required>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <div class="form-material form-material-primary">
                                                <h5>Tags</h5>
                                                <br>
                                                <input class="js-tags-input form-control" type="text"
                                                       value="{{$product->tags}}"    id="product-meta-keywords" name="tags" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="block">
                            <div class="block-header">
                                <h3 class="block-title">More Details</h3>
                            </div>
                            <form action="{{route('product.update',$product->id)}}" method="post">
                                @csrf
                                <input type="hidden" name="type" value="more-details">
                                <div class="block-content">
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <label>Warned Platform</label>
                                            <br>
                                            @foreach($platforms as $platform)
                                                <label class="css-input css-checkbox css-checkbox-primary">
                                                    <input type="checkbox" name="platforms[]"
                                                     @if(in_array($platform->id,$product->warned_platforms($product))) checked @endif
                                                           value="{{ $platform->id }}"><span></span>{{ $platform->name }}
                                                </label>
                                                <br>
                                            @endforeach
                                        </div>
{{--                                        <input type="submit" value="save">--}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{--Variants Block--}}
                <div class="col-md-12">
                    @if($product->variants == 1)
                        <div class="block">
                            <div class="block-header d-inline-flex" >
                                <h3 class="block-title">Variant</h3>
                                <div class="float-right d-inline-block" style="float: right">
                                    {{--                                    <a href="">Add Varaint</a>--}}
                                    <a style="margin-left: 10px;" data-toggle="modal" data-target="#edit_options">Edit Options</a>
                                </div>
                            </div>
                            <div class="block-content" style="padding-top: 0 !important;">
                                <table class="table variants-div js-table-sections table-hover table-responsive">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th style="vertical-align: top">Title</th>
                                        <th style="vertical-align: top">Image</th>
                                        <th style="vertical-align: top">Price</th>
                                        <th style="vertical-align: top">Compare Price</th>
                                        <th style="vertical-align: top">Cost</th>
                                        <th style="vertical-align: top">Quantity</th>
                                        <th style="vertical-align: top">SKU</th>
                                        <th style="vertical-align: top">Barcode</th>
                                    </tr>
                                    </thead>
                                        @if(count($product->hasVariants) > 0)
                                            @foreach($product->hasVariants as $index => $v)
                                                <form action="{{route('product.update',$product->id)}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="type" value="single-variant-update">
                                                    <input type="hidden" name="variant_id" value="{{$v->id}}">
                                                    <tbody class="js-table-sections-header">
                                                    <tr>
                                                        <td class="text-center">
                                                            <label class="css-input css-checkbox css-checkbox-primary">
                                                                <input type="checkbox" value="{{$v->id}}" id="row_{{$index}}" name="variants[]"><span></span>
                                                            </label>
                                                        </td>
                                                        <td class="variant_title">
                                                            {{$v->title}}
                                                        </td>
                                                        <td class="text-center">
                                                            <img class="img-avatar img-avatar-variant" style="border: 1px solid whitesmoke" data-form="#varaint_image_form_{{$index}}" data-input=".varaint_file_input"
                                                                 @if($v->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                                 @else src="{{asset('images/variants')}}/{{$v->has_image->image}}" @endif alt="">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="price" placeholder="$0.00" value="{{$v->price}}">
                                                        </td>
                                                        <td><input type="text" class="form-control" name="compare_price" value="{{$v->compare_price}}" placeholder="$0.00"></td>
                                                        <td><input type="text" class="form-control" name="cost" value="{{$v->cost}}" placeholder="$0.00"></td>
                                                        <td><input type="text" class="form-control" value="{{$v->quantity}}" name="quantity" placeholder="0"></td>
                                                        <td><input type="text" class="form-control" name="sku" value="{{$v->sku}}"></td>
                                                        <td><input type="text" class="form-control" name="barcode" value="{{$v->barcode}}" placeholder="">
                                                        </td>

                                                    </tr>
                                                    </tbody>
                                                    <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td style="vertical-align: middle"> @if($v->option1 != null) Option1: @endif</td>
                                                        <td>
                                                            @if($v->option1 != null)
                                                                <input type="text" class="form-control" name="option1" placeholder="$0.00" value="{{$v->option1}}">
                                                            @endif
                                                        </td>
                                                        <td style="vertical-align: middle">@if($v->option2 != null) Option2: @endif</td>
                                                        <td>
                                                            @if($v->option2 != null)
                                                                <input type="text" class="form-control" name="option2" placeholder="$0.00" value="{{$v->option2}}">
                                                            @endif
                                                        </td>
                                                        <td style="vertical-align: middle">@if($v->option3 != null) Option3: @endif</td>
                                                        <td>
                                                            @if($v->option3 != null)
                                                                <input type="text" class="form-control" name="option3" placeholder="$0.00" value="{{$v->option3}}">
                                                            @endif
                                                        </td>
                                                        <td></td>

                                                    </tr>
                                                    </tbody>
                                                </form>
                                            @endforeach
                                        @endif
                                </table>
                            </div>
                            <div class="form-image-src" style="display: none">
                                @if(count($product->hasVariants) > 0)
                                    @foreach($product->hasVariants as $index => $v)
                                        <form id="varaint_image_form_{{$index}}" action="{{route('product.update',$product->id)}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="type" value="variant-image-update">
                                            <input type="hidden" name="variant_id" value="{{$v->id}}">
                                            <input type="file" name="varaint_src" class="varaint_file_input" accept="image/*">
                                        </form>
                                    @endforeach
                                    @endif
                            </div>
                        </div>
                        <div class="modal fade" id="edit_options" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-popout" role="document">
                                <div class="modal-content">
                                    <div class="block block-themed block-transparent mb-0">
                                        <div class="block-header bg-primary-dark">
                                            <h3 class="block-title">Edit Options</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content" style="padding: 20px !important;">
                                            <div class="row">
                                                @if(count($product->option1($product))>0)
                                                    <div class="col-md-12" style="margin-bottom: 10px">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control" readonly value="Option1">
                                                            </div>
                                                            <div class="col-md-9">
                                                                @foreach($product->option1($product) as $a)
                                                                    <span class="badge badge-info">
                                                                        <span >{{$a}}</span>
                                                                        <a><i data-option="option1" class="remove-option fa fa-times" style="color: white"></i></a>
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top:10px ">
                                                            @if(count($product->option2($product)) == 0)
                                                                <div class="col-md-12 add-option-button">
                                                                    <a class="btn btn-info add-option-div">Add Other Option</a>
                                                                </div>
                                                                <div class="div2" style="display: none">
                                                                    <div class="col-md-3">
                                                                        <input type="text" class="form-control" readonly value="Option2">
                                                                    </div>
                                                                    <form class="new-option-add" action="{{route('product.update',$product->id)}}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="type" value="new-option-add">
                                                                        <div class="col-md-7">
                                                                            <input type="hidden" name="option" value="option2">
                                                                            <input type="text" class="form-control option-value" name="value" value="" placeholder="Enter Only One Option Value">
                                                                        </div>
                                                                    </form>

                                                                    <div class="row">
                                                                        <a class="btn btn-default delete-option-value"><i class="fa fa-times"></i></a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(count($product->option2($product))>0)
                                                    <div class="col-md-12" style="margin-bottom: 10px">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control" readonly value="Option2">
                                                            </div>
                                                            <div class="col-md-9">

                                                                @foreach($product->option2($product) as $a)
                                                                    <span class="badge badge-info">
                                                                        <span>{{$a}}</span>
                                                                        <a><i data-option="option2" class="remove-option fa fa-times" style="color: white"></i></a>
                                                                    </span>
                                                                @endforeach
                                                            </div>

                                                        </div>
                                                        <div class="row" style="margin-top:10px ">
                                                            @if(count($product->option3($product)) == 0)
                                                                <div class="col-md-12 add-option-button" style="">
                                                                    <a class="btn btn-info add-option-div">Add Other Option</a>
                                                                </div>
                                                                <div class="div2" style="display: none">
                                                                    <div class="col-md-3">
                                                                        <input type="text" class="form-control" readonly value="Option3">
                                                                    </div>
                                                                    <form class="new-option-add" action="{{route('product.update',$product->id)}}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="type" value="new-option-add">
                                                                        <div class="col-md-7">
                                                                            <input type="hidden" name="option" value="option3">
                                                                            <input type="text" class="form-control option-value" name="value" value="" placeholder="Enter Only One Option Value">
                                                                        </div>
                                                                    </form>
                                                                    <div class="row">
                                                                        <a class="btn btn-default delete-option-value"><i class="fa fa-times"></i></a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(count($product->option3($product))>0)
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control" readonly value="Option3">
                                                            </div>
                                                            <div class="col-md-9">
                                                                @foreach($product->option3($product) as $a)
                                                                    <span class="badge badge-info">
                                                                        <span>{{$a}}</span>
                                                                        <a><i data-option="option3" class="remove-option fa fa-times" style="color: white"></i></a>
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="deleted-data">
                                            <form id="variant-options-update" action="{{route('product.update',$product->id)}}" method="post">
                                                @csrf
                                                <input type="hidden" name="type" value="variant-option-delete">
                                            </form>
                                        </div>

                                        <div class="block-content block-content-full text-right border-top">
                                            <button data-option1="" data-option2="" data-option3="" data-deleted="0" class="variant-options-update-save btn btn-primary">Save</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                                Discard
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="block">
                            <div class="block-header d-inline-flex" >
                                <h3 class="block-title">No Variant For This Products</h3>
                                <div class="float-right d-inline-block" style="float: right">
                                    <a href="{{route('product.existing_product_new_variants',$product->id)}}">Add New Variants</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="content">
                    <div class="row ">
                        <div class="col-sm-12 text-right">
                            <hr>
                            <a class="btn btn-primary btn-square submit_all">Update</a>
                            <a href="{{ route('product.edit',$product->id) }}" class="btn btn-default btn-square">Discard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
