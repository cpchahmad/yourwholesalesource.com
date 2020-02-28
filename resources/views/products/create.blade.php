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
    </style>
    <form action="{{ route('product.save') }}" class="form-horizontal push-30-t push-30"
          id="my-awesome-dropzone" method="post" enctype="multipart/form-data">
        @csrf
        <div class="content">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="font-w700">Add Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('product.create') }}" class="btn btn-default btn-square ">Discard</a>
                    <button type="submit" class="btn btn-primary btn-square">Save</button>
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

                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
                                    <input type="file" name="images[]" class="push-30-t push-30 dz-clickable" multiple
                                           required>
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
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <label>Price</label>
                                            <input type="text" class="form-control" name="price"
                                                   placeholder="$ 0.00" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <label>Compare at Price</label>
                                            <input type="text" class="form-control" name="compare_price"
                                                   placeholder="$ 0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <label>Cost Per Item</label>
                                            <input type="text" class="form-control" name="cost"
                                                   placeholder="$ 0.00">
                                        </div>
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
                                    <label for="ship_info">Shipping Info</label>
                                    <input class="form-control" type="text" id="ship_info" name="ship_info"
                                           placeholder="Shipping Information (Optional)">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="ship_info">Processing Time</label>
                                    <input class="form-control" type="text" id="processing_time" name="ship_info"
                                           placeholder="Shipping Information (Optional)">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Shipping Price</label>
                                    <input type="text" class="form-control" name="shipping_price"
                                           placeholder="$ 0.00">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Warned Platform</label>
                                    <textarea name="warning_info" class="form-control" cols="5" rows="5"></textarea>
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
                                                <input class="js-tags-input form-control" type="text"
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
                                                    <input class="js-tags-input form-control" type="text"
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
                                                    <input class="js-tags-input form-control" type="text"
                                                           id="product-meta-keywords" name="option3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12 push-10 text-right">
                                        <button type="button"
                                                class="btn btn-primary btn-sm btn-square variants_preview_btn">
                                            Preview
                                        </button>
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
                                Product Category
                            </div>
                            <div class="block-content">
                                <div class="form-group product_category">
                                    @foreach($categories as $category)
                                        <label class="css-input css-checkbox css-checkbox-primary">
                                            <input type="checkbox" name="category[]"
                                                   value="{{ $category->title }}"><span></span>{{ $category->title }}
                                        </label>
                                        <div class="row product_sub_cat">
                                            <div class="col-xs-12 col-xs-push-1">
                                                @foreach($category->hasSub as $sub)
                                                    <label class="css-input css-checkbox css-checkbox-primary">
                                                        <input type="checkbox" name="sub_cat[]"
                                                               value="{{ $sub->title }}"><span></span>{{ $sub->title }}
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
                            <h3 class="block-title">Inventory</h3>
                        </div>
                        <div class="block-content">
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Quantity</label>
                                    <input type="text" class="form-control" name="quantity" placeholder="0" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Weight</label>
                                    <input type="text" class="form-control" name="weight" placeholder="0.0Kg">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>SKU</label>
                                    <input type="text" class="form-control" name="sku" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Barcode</label>
                                    <input type="text" class="form-control" name="barcode">
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
                            <button type="submit" class="btn btn-primary btn-square">Save</button>
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
                    $('.variants_preview_btn').click(function () {
                        var price = $('input[type="text"][name="price"]').val();
                        var comparePrice = $('input[type="text"][name="compare_price"]').val();
                        var sku = $('input[type="text"][name="sku"]').val();
                        var option1 = $('input[type="text"][name="option1"]').val();
                        var option2 = $('input[type="text"][name="option2"]').val();
                        var option3 = $('input[type="text"][name="option3"]').val();
                        var substr1 = option1.split(',');
                        var substr2 = option2.split(',');
                        var substr3 = option3.split(',');
                        $('.variants_table').show();
                        $("tbody").empty();
                        jQuery.each(substr1, function (index1, item1) {
                            jQuery.each(substr2, function (index2, item2) {
                                jQuery.each(substr3, function (index3, item3) {
                                    $('tbody').append('   <tr>\n' +
                                        '                                                    <td class="variant_title">' + item1 + '/' + item2 + '/' + item3 + '<input type="hidden" name="variant_title[]" value="' + item1 + '/' + item2 + '/' + item3 + '"></td>\n' +
                                        '                                                    <td><input type="text" class="form-control" name="variant_price[]" placeholder="$0.00" value="' + price + '">\n' +
                                        '                                                    </td>\n' +
                                        '                                                    <td><input type="text" class="form-control" name="variant_comparePrice[]" value="' + comparePrice + '" placeholder="$0.00"></td>\n' +
                                        '                                                    <td><input type="text" class="form-control" name="variant_quantity[]" placeholder="0"></td>\n' +
                                        '                                                    <td><input type="text" class="form-control" name="variant_sku[]" value="' + sku++ + '"></td>\n' +
                                        '                                                    <td><input type="text" class="form-control" name="variant_barcode[]" placeholder=""></td>\n' +
                                        '                                                </tr>');
                                });
                            });
                        });
                        // $('.variants_preview_btn').hide();
                    });
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
