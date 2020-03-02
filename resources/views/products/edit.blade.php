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
    <form action="{{ route('product.update', $product->id) }}" class="form-horizontal push-30-t push-30"
          id="my-awesome-dropzone" method="post" enctype="multipart/form-data">
        @csrf
        <div class="content">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="font-w700">Add Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button type="submit" class="btn btn-success btn-square">Update</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="block">
                        <div class="block-content block-content-full">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="product-name">Title</label>
                                    <input class="form-control" type="text" id="product-name" name="title"
                                           value="{{ $product->title }}"
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
                                              placeholder="Please Enter Description here !">{{ $product->description }}</textarea>
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
                                    <input type="file" name="images[]" class="push-30-t push-30 dz-clickable" multiple>
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
                                                   value="{{ $product->price }}"
                                                   placeholder="$ 0.00" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="col-xs-12 push-10">
                                            <label>Compare at Price</label>
                                            <input type="text" class="form-control" name="compare_price"
                                                   value="{{ $product->compare_price }}"
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
                                                   value="{{ $product->cost }}"
                                                   placeholder="$ 0.00">
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
                                               value="1"
                                               @if ($product->hasVariants != null)
                                               checked
                                            @endif><span></span> This product has multiple options, like
                                        different sizes or colors
                                    </label>
                                </div>
                            </div>
                            <div class="variant_options" @if ($product->hasVariants == null)
                            style="display: none;"
                                @endif>
                                <div class="variants_table">
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
                                                @foreach($product->hasVariants as $item)
                                                    <tr>
                                                        <td class="variant_title">{{ $item->title }}<input type="hidden"
                                                                                                           name="variant_title[]"
                                                                                                           value="{{ $item->title }}">
                                                            <input type="hidden" name="variant_id[]"
                                                                   value="{{ $item->id }}"></td>
                                                        <td><input type="text" class="form-control"
                                                                   name="variant_price[]" placeholder="$0.00"
                                                                   value="{{ $item->price }}">
                                                        </td>
                                                        <td><input type="text" class="form-control"
                                                                   name="variant_comparePrice[]"
                                                                   value="{{ $item->compare_price }}"
                                                                   placeholder="$0.00"></td>
                                                        <td><input type="text" class="form-control"
                                                                   name="variant_quantity[]"
                                                                   value="{{ $item->quantity }}" placeholder="0"></td>
                                                        <td><input type="text" class="form-control" name="variant_sku[]"
                                                                   value="{{ $item->sku }}"></td>
                                                        <td><input type="text" class="form-control"
                                                                   name="variant_barcode[]"
                                                                   value="{{ $item->barcode }}"></td>
                                                    </tr>
                                                @endforeach
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
                                    <?php $pcategories = json_decode($product->category);
                                    $psubcategories = json_decode($product->sub_category);
                                    ?>
                                    @foreach($pcategories as $category)
                                        <label class="css-input css-checkbox css-checkbox-primary">
                                            <input type="checkbox" class="category_checkbox" name="category[]" value="{{ $category }}"
                                                   checked><span></span>{{ $category }}
                                        </label>
                                        <div class="row product_sub_cat">
                                            <div class="col-xs-12 col-xs-push-1">
                                                @foreach($psubcategories as $sub)
                                                    <label class="css-input css-checkbox css-checkbox-primary">
                                                        <input type="checkbox" name="sub_cat[]"
                                                               class="sub_cat_checkbox"      value="{{ $sub }}" checked><span></span>{{ $sub }}
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
                                           placeholder="eg. Shirts" required value="{{ $product->type }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Vendor</label>
                                    <input type="text" class="form-control" name="vendor" placeholder="eg. Nike"
                                           value="{{ $product->vendor }}"
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
                                               id="product-meta-keywords" name="tags" value="{{ $product->tags }}"
                                               required>
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
                                    <input type="text" class="form-control" name="quantity"
                                           value="{{ $product->quantity }}" placeholder="0" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Weight</label>
                                    <input type="text" class="form-control" name="weight" placeholder="0.0Kg"
                                           value="{{ $product->weight }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>SKU</label>
                                    <input type="text" class="form-control" name="sku" value="{{ $product->sku }}"
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12 push-10">
                                    <label>Barcode</label>
                                    <input type="text" class="form-control" name="barcode"
                                           value="{{ $product->barcode }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="row ">
                        <div class="col-sm-12 text-right">
                            <hr>
                            <button type="submit" class="btn btn-success btn-square">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
