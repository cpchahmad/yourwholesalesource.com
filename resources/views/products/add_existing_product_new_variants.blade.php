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

    <input type="text" name="cost" value="{{$product->cost}}" style="display: none">
    <input type="text" name="price" value="{{$product->price}}" style="display: none">
    <input type="text" name="compare_price" value="{{$product->compare_price}}" style="display: none">
    <input type="text" name="sku" value="{{$product->sku}}" style="display: none">


    <div class="form-horizontal push-30">
        <form action="{{route('product.update',$product->id)}}" method="post">
            @csrf
            <input type="hidden" name="type" value="existing-product-new-variants">
            <div class="content">
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-sm-6">
                        <h3 class="font-w700">New Variants</h3>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('product.edit',$product->id) }}" class="btn btn-default btn-square ">Back to Editing</a>
                        <a href="{{ route('product.create') }}" class="btn btn-success btn-square ">Add New Product</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
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
                </div>
                <div class="content submit-div" style="display: none">
                    <div class="row ">
                        <div class="col-sm-12 text-right">
                            <hr>
                            <a href="{{ route('product.edit',$product->id) }}" class="btn btn-default btn-square ">Discard</a>
                            <button class="btn btn-primary btn-square">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('input[type="checkbox"][name="variants"]').click(function () {
                $('.submit-div').toggle();
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
