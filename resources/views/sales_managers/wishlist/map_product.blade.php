@extends('layout.manager')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-3 pb-3">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h5 my-2">
                    Sync Product
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Wishlist</a>
                        </li>

                        <li class="breadcrumb-item"> Sync Product</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <form id="create_product_form" action="{{route('wishlist.completed.map_product')}}" class="form-horizontal  push-30" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="wishlist_id" value="{{$wishlist->id}}">
        <input type="hidden" name="product_shopify_id" value="{{$product_shopify_id}}">

        <div class="content">
            <div class="row mb2">
                <div class="col-sm-12 text-right mb-3">
                    <a href="{{ route('sales_managers.wishlist') }}" class="btn btn-default btn-square ">Discard</a>
                    <button class="btn btn-success btn-square submit-button">Save</button>
                </div>
            </div>
            <!-- Info -->
            <div class="row">
                <div class="col-sm-8">
                    <div class="block">
                        <div class="block-content block-content-full">

                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="product-name">Title</label>
                                    <input class="form-control" type="text" id="product-name" name="title"
                                           placeholder="Short Sleeve Shirt" value="{{$product->title}}" required>
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
                                              placeholder="Please Enter Description here !">{!! $product->body_html !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Images</h3>
                        </div>
                        <div class="block-content">
                            <div class="row mb2">
                                @foreach($product->images as $image)
                                    <div class="col-lg-4 preview-image animated fadeIn" >
                                        <div class="options-container fx-img-zoom-in fx-opt-slide-right">
                                            <img class="img-fluid options-item" src="{{$image->src}}" alt="">
                                        </div>
                                    </div>

                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="padding-bottom: 13px;">

                                    <div class="dropzone dz-clickable">
                                        <div class="dz-default dz-message"><span>Click here to upload images.</span></div>
                                        <div class="row preview-drop"></div>
                                    </div>

                                    <input style="display: none" accept="image/*"  type="file"  name="images[]" class="push-30-t dz-hidden-input push-30 images-upload" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Pricing</h3>
                        </div>
                        <div class="block-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="text" class="form-control" value="{{$product->variants[0]->price}}" name="price" placeholder="$ 0.00" required>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label>Cost Per Item</label>
                                        <input type="text" class="form-control" value="{{$product->variants[0]->price}}" name="cost"
                                               placeholder="$ 0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 ">
                                            <label>Quantity</label>
                                            <input type="text" class="form-control"  value="{{$product->variants[0]->inventory_quantity}}" name="quantity" placeholder="0" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 ">
                                            <label>Weight</label>
                                            <input type="text" class="form-control" value="{{$product->variants[0]->weight}}" name="weight" placeholder="0.0Kg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 ">
                                            <label>SKU</label>
                                            <input type="text" class="form-control" value="{{$product->variants[0]->sku}}" name="sku" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">

                                        <div class="col-xs-12 ">
                                            <label>Barcode</label>
                                            <input type="text" class="form-control" value="{{$product->variants[0]->barcode}}" name="barcode">
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Variant</h3>
                        </div>
                        <div class="block-content">
                            @if(count($product->variants) == 0)
                                <div class="form-group">
                                    <div class="col-xs-12 push-10">
                                        <div class="custom-control custom-checkbox d-inline-block">
                                            <input type="checkbox" name="variants" class="custom-control-input" id="val-terms"  value="1">
                                            <label class="custom-control-label" for="val-terms">This product has multiple options, like
                                                different sizes or colors</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="variant_options" style="display: none;">
                                    <hr>
                                    <h3 class="font-w300">Options</h3>
                                    <br>
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <h5>Option 1</h5>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <input type="text" class="form-control" value="Size">
                                                </div>
                                                <div class="col-sm-9">
                                                    <input class="js-tags-options options-preview form-control" type="text"
                                                           id="product-meta-keywords" name="option1" value="">
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-light btn-square option_btn_1 mt-2">
                                                Add another option
                                            </button>
                                        </div>
                                    </div>
                                    <div class="option_2" style="display: none;">
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-xs-12 push-10">
                                                <h5>Option 2</h5>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" value="Color">
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input class="js-tags-options options-preview form-control" type="text"
                                                               id="product-meta-keywords" name="option2">
                                                    </div>
                                                </div>
                                                <button type="button"
                                                        class="btn btn-light btn-square option_btn_2 mt-2">Add another
                                                    option
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="option_3" style="display: none;">
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-xs-12 push-10">
                                                <h5>Option 3</h5>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" value="Material">
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <input class="js-tags-options options-preview form-control" type="text"
                                                               id="product-meta-keywords" name="option3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="variants_table" style="display: none;">
                                        <hr>
                                        <h3 class="block-title">Preview</h3>
                                        <br>
                                        <div class="form-group">
                                            <div class="col-xs-12 push-10">
                                                <table class="table table-hover table-responsive">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 10%;">Title</th>
                                                        <th style="width: 20%;">Price</th>
                                                        <th style="width: 23%;">Cost</th>
                                                        <th style="width: 10%;">Quantity</th>
                                                        <th style="width: 20%;">SKU</th>
                                                        <th style="width: 20%;">Barcode</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="variants_table">
                                    <input type="hidden" name="variants" value="1">
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                <tr>
                                                    <th style="width: 10%;">Title</th>
                                                    <th style="width: 20%;">Price</th>
                                                    <th style="width: 23%;">Cost</th>
                                                    <th style="width: 10%;">Quantity</th>
                                                    <th style="width: 20%;">SKU</th>
                                                    <th style="width: 20%;">Barcode</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($product->variants as $variant)
                                                <tr>
                                                    <td class="variant_title"> {{$variant->title}} <input type="hidden" name="variant_title[]" value="{{$variant->title}}"></td>
                                                    <td><input type="text" class="form-control" name="variant_price[]" placeholder="$0.00" value="{{$variant->price}}">
                                                    </td>
                                                    <td><input type="text" class="form-control" name="variant_cost[]" value="{{$variant->price}}" placeholder="$0.00"></td>
                                                    <td><input type="text" class="form-control" name="variant_quantity[]" value="{{$variant->inventory_quantity}}" placeholder="0"></td>
                                                    <td><input type="text" class="form-control" name="variant_sku[]" value="{{$variant->sku}}"></td>
                                                    <td><input type="text" class="form-control" name="variant_barcode[]" value="{{$variant->barcode}}" placeholder=""></td>
                                                </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="block">
                        <div class="block-header">
                            <div class="block-title">
                                Status
                            </div>
                        </div>
                        <div class="block-content pt-0">
                            <div class="form-group">
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" class="custom-control-input" id="example-radio-customPublished" name="status" value="1" checked="">
                                    <label class="custom-control-label" for="example-radio-customPublished">Published</label>
                                </div>
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" class="custom-control-input" id="example-radio-customDraft" name="status" value="0" >
                                    <label class="custom-control-label" for="example-radio-customDraft">Draft</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="block-header">
                            <div class="block-title">
                                Mark as Fulfilled
                            </div>
                        </div>
                        <div class="block-content pt-0" >
                            <div class="form-group">
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" required class="custom-control-input" id="example-radio-customfulfilled" name="fulfilled-by" value="Fantasy" checked="">
                                    <label class="custom-control-label" for="example-radio-customfulfilled">By Awareness Drop Shipping</label>
                                </div>
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" required class="custom-control-input" id="example-radio-customAliExpress" name="fulfilled-by" value="AliExpress" >
                                    <label class="custom-control-label" for="example-radio-customAliExpress">By AliExpress</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <div class="block-title">
                                <h3 class="block-title">Product Category</h3>
                            </div>
                        </div>
                        <div class="block-content" style="height: 300px;overflow-y: auto;overflow-x: hidden;">
                            <div class="form-group product_category">
                                @foreach($categories as $category)
                                    <span class="category_down" data-value="0" style="margin-right: 5px;font-size: 16px;vertical-align: middle"><i class="fa fa-angle-right"></i></span>
                                    <div class="custom-control custom-checkbox d-inline-block">
                                        <input type="checkbox" name="category[]" value="{{$category->id}}" class="custom-control-input category_checkbox" id="rowcat_{{$category->title}}">
                                        <label class="custom-control-label" for="rowcat_{{$category->title}}">{{$category->title}}</label>
                                    </div>
                                    <div class="row product_sub_cat" style="display: none">
                                        <div class="col-xs-12 col-xs-push-1">
                                            @foreach($category->hasSub as $sub)
                                                <div class="custom-control custom-checkbox d-inline-block">
                                                    <input type="checkbox" name="sub_cat[]" value="{{$sub->id}}" class="custom-control-input sub_cat_checkbox" id="rowsub_{{$sub->title}}">
                                                    <label class="custom-control-label" for="rowsub_{{$sub->title}}">{{$sub->title}}</label>
                                                </div>
                                                <br>
                                            @endforeach
                                        </div>
                                    </div>
                                    <br>
                                @endforeach
                            </div>
                        </div>
                        <div class="block-footer" style="height: 15px"></div>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Organization</h3>
                        </div>
                        <div class="block-content pt-0">
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Product Type</label>
                                    <input type="text" class="form-control" name="product_type"
                                           placeholder="eg. Shirts" value="{{$product->product_type}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Vendor</label>
                                    <input type="text" class="form-control" name="vendor" placeholder="eg. Nike" value="{{$product->vendor}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material form-material-primary">
                                        <label>Tags</label>
                                        <input class="js-tags-input form-control" type="text"
                                               id="product-meta-keywords" name="tags" value="{{$product->tags}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">More Details</h3>
                        </div>
                        <div class="block-content">
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Processing Time</label>
                                    <input type="text" class="form-control" name="processing_time" placeholder="eg. 7 working days" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Warned Platform</label>
                                    <br>
                                    @foreach($platforms as $platform)
                                        <div class="custom-control custom-checkbox d-inline-block">
                                            <input type="checkbox" name="platforms[]" value="{{$platform->id}}" class="custom-control-input" id="row_{{$platform->name}}">
                                            <label class="custom-control-label" for="row_{{$platform->name}}">{{$platform->name}}</label>
                                        </div>
                                        <br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Preferences</h3>
                        </div>
                        <div class="block-content">
                            <div class="form-group">
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" required class="custom-control-input preference-check" id="prefer-global" name="global" value="1" checked="">
                                    <label class="custom-control-label " for="prefer-global">Global</label>
                                </div>
                                <div class="custom-control custom-radio mb-1">
                                    <input type="radio" required class="custom-control-input preference-check" id="prefer-store" name="global" value="0" >
                                    <label class="custom-control-label" for="prefer-store">Selected Stores</label>
                                </div>
                            </div>
                            <div class="form-group" style="display: none">
                                <div class="form-material">
                                    <label for="material-error">Stores <i class="fa fa-question-circle"  title="Store where product you want to show."> </i></label>
                                    <select class="form-control shop-preference js-select2" style="width: 100%;" data-placeholder="Choose multiple markets.." name="shops[]"   multiple="">
                                        <option></option>
                                        @foreach($shops as $shop)
                                            <option value="{{$shop->id}}">{{explode('.',$shop->shopify_domain)[0]}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="row ">
                        <div class="col-sm-12 text-right">
                            <hr>
                            <a href="{{ route('sales_managers.wishlist') }}" class="btn btn-default btn-square ">Discard</a>
                            <button class="btn btn-primary btn-square submit-button">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
