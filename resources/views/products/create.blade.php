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

         .mb2{
             margin-bottom: 10px !important;
         }

    </style>
    <form id="create_product_form" action="{{ route('product.save') }}" class="form-horizontal {{--push-30-t--}} push-30" method="post" enctype="multipart/form-data">
        @csrf
        <div class="content">
            <div class="row mb2">
                <div class="col-sm-6">
                    <h3 class="font-w700">Add Product</h3>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('product.create') }}" class="btn btn-default btn-square ">Discard</a>
                    <button class="btn btn-primary btn-square submit-button">Save</button>
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
                                           placeholder="Short Sleeve Shirt" required>
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
                                              placeholder="Please Enter Description here !"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Images</h3>
                        </div>
                        <div class="block-content">


                            {{--                            <div class="row" style="padding: 20px">--}}
                            {{--                                <div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-5">--}}
                            {{--                                    <a style="cursor: pointer" class="btn btn-sm btn-primary upload-photo"> Upload Photos</a>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            <div class="row" {{--style="display: none"--}}>
                                <div class="{{--col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3--}} col-md-12" style="padding-bottom: 13px;">
                                    <div class="dropzone dz-clickable">
                                        <div class="dz-default dz-message"><span>Click here to upload images.</span></div>
                                        <div class="row preview-drop"></div>
                                    </div>

                                    <input style="display: none" accept="image/*"  type="file"  name="images[]" class="push-30-t dz-hidden-input push-30 images-upload" multiple required>
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
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <label>Price</label>
                                            <input type="text" class="form-control" name="price"
                                                   placeholder="$ 0.00" required>
                                        </div>
                                    </div>
                                </div>
{{--                                <div class="col-sm-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <div class="col-xs-12 ">--}}
{{--                                            <label>Compare at Price</label>--}}
{{--                                            <input type="text" class="form-control" name="compare_price"--}}
{{--                                                   placeholder="$ 0.00">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-xs-12 ">
                                            <label>Cost Per Item</label>
                                            <input type="text" class="form-control" name="cost"
                                                   placeholder="$ 0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 ">
                                            <label>Quantity</label>
                                            <input type="text" class="form-control" name="quantity" placeholder="0" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 ">
                                            <label>Weight</label>
                                            <input type="text" class="form-control" name="weight" placeholder="0.0Kg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 ">
                                            <label>SKU</label>
                                            <input type="text" class="form-control" name="sku" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">

                                        <div class="col-xs-12 ">
                                            <label>Barcode</label>
                                            <input type="text" class="form-control" name="barcode">
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
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label class="css-input css-checkbox css-checkbox-primary" for="val-terms">
                                        <input type="checkbox" id="val-terms" name="variants"
                                               value="1"><span></span> This product has multiple options, like
                                        different sizes or colors
                                    </label>
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
                                        <button type="button" class="btn btn-default btn-square option_btn_1 mt-2">
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
                                                    class="btn btn-default btn-square option_btn_2 mt-2">Add another
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
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th style="width: 20%;">Title</th>
                                                    <th style="width: 15%;">Price</th>
                                                    <th style="width: 17%;">Compare Price</th>
                                                    <th style="width: 17%;">Cost</th>
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
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="block">
                        <div class="block-header">
                            <div class="block-title">
                                Mark as Fulfilled
                            </div>
                        </div>
                        <div class="block-content" >
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label class="css-input css-radio css-radio-primary">
                                        <input type="radio" name="fulfilled-by" value="Fantasy" checked=""><span></span> By Fantasy Supplier
                                    </label>
                                </div>
                                <div class="col-xs-12">
                                    <label class="css-input css-radio  css-radio-primary push-10-r">
                                        <input type="radio" name="fulfilled-by" value="AliExpress" ><span></span> By AliExpress
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <div class="block-title">
                                Product Category
                            </div>
                            <div class="block-content" style="height: 200px;overflow: auto;overflow-x: hidden;">
                                <div class="form-group product_category">
                                    @foreach($categories as $category)
                                        <span class="category_down" data-value="0" style="margin-right: 5px;font-size: 16px"> <i class="fa fa-angle-right"></i></span>
                                        <label class="css-input css-checkbox css-checkbox-primary">
                                            <input type="checkbox" name="category[]" class="category_checkbox"
                                                   value="{{ $category->id }}"><span></span>{{ $category->title }}
                                        </label>
                                        <div class="row product_sub_cat" style="display: none">
                                            <div class="col-xs-12 col-xs-push-1">
                                                @foreach($category->hasSub as $sub)
                                                    <label class="css-input css-checkbox css-checkbox-primary">
                                                        <input type="checkbox" class="sub_cat_checkbox" name="sub_cat[]"
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
                        </div>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Organization</h3>
                        </div>
                        <div class="block-content">
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Product Type</label>
                                    <input type="text" class="form-control" name="product_type"
                                           placeholder="eg. Shirts" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Vendor</label>
                                    <input type="text" class="form-control" name="vendor" placeholder="eg. Nike"
                                           required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <div class="form-material form-material-primary">
                                        <h5>Tags</h5>
                                        <br>
                                        <input class="js-tags-input form-control" type="text"
                                               id="product-meta-keywords" name="tags" value="" required>
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
                                <div class="col-xs-12">
                                    <label>Warned Platform</label>
                                    <br>
                                    @foreach($platforms as $platform)
                                    <label class="css-input css-checkbox css-checkbox-primary">
                                        <input type="checkbox" name="platforms[]"
                                               value="{{ $platform->id }}"><span></span>{{ $platform->name }}
                                    </label>
                                        <br>
                                        @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Product Status</label>
                                    <br>
                                    <label class="css-input css-radio  css-radio-primary push-10-r">
                                        <input type="radio" name="status" value="1" checked=""><span></span> Published
                                    </label>
                                    <br>
                                    <label class="css-input css-radio  css-radio-primary">
                                        <input type="radio" name="status" value="0"><span></span> Draft
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="row ">
                        <div class="col-sm-12 text-right">
                            <hr>
                            <a href="{{ route('product.create') }}" class="btn btn-default btn-square ">Discard</a>
                            <button class="btn btn-primary btn-square submit-button">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('input[type="checkbox"][name="variants"]').click(function () {
                if ($(this).prop("checked") == true) {
                    $('.variant_options').show();
                } else if ($(this).prop("checked") == false) {
                    $('.variant_options').hide();
                }
            });
            $('.option_btn_1').click(function () {
                $('.option_2').show();
                $('.option_btn_1').hide();
            });
            $('.option_btn_2').click(function () {
                $('.option_3').show();
                $('.option_btn_2').hide();
            });
        });
    </script>
@endsection
