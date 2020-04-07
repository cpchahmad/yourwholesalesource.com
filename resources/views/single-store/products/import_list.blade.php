@extends('layout.single')
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
        <div class="content-grid">
            <div class="row mb2">
                <h3 class="font-w700" style="display: contents">Import List</h3>
            </div>

            <div class="row mb2">
                <div class="col-md-8">
                    <input type="search" name="search" placeholder="Search By Keyword" class="form-control">
                </div>
                <div class="col-md-3">
                    <select name="source" class="form-control">
                        <option value="all">All Sources</option>
                        <option value="Fantasy">WeFullFill</option>
                        <option value="AliExpress">AliExpress</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary"><i class="fa fa-search"></i>Search</button>
                </div>
            </div>
            <div class="row block mb2">
                <div class="block-content push-10">
                    <div>
                        <label class="css-input css-checkbox css-checkbox-primary">
                            <input type="checkbox"><span></span>
                        </label>
                    </div>


                    <div class="">
                        <div class="btn-group btn-group-justified">
                            <div class="btn-group">
                                <button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i></button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-default" type="button"><i class="fa fa-check"></i></button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-default" type="button"><i class="fa fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            @foreach($products as $product)

                <div class="block mb-3">
                    <ul class="nav nav-tabs nav-tabs-alt js-tabs-enabled" data-toggle="tabs" role="tablist">
                        <li class="nav-item import_checkbox_select">
                        <div>
                            <label class="css-input css-checkbox css-checkbox-primary">
                                <input type="checkbox"><span></span>
                            </label>
                        </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#product_{{$product->id}}_products">Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#product_{{$product->id}}_variants">Variants</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#product_{{$product->id}}_images">Images</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#product_{{$product->id}}_description">Description</a>
                        </li>
                        <li class="nav-item ml-auto action_buttons_in_tabs">
                            <div class="block-options pl-3 pr-2">
                                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                                <button type="button" class="btn btn-sm btn-square btn-primary">
                                    <i class="fa fa-fw fa-envelope mr-1"></i>
                                    Import to store
                                </button>
                            </div>
                        </li>
                    </ul>
                    <div class="block-content tab-content">
                        <div class="tab-pane active" id="product_{{$product->id}}_products" role="tabpanel">
                            <div class="row">
                                <div class="col-md-3">
                                    <img class="img-fluid" src="https://nextschain.oss-us-west-1.aliyuncs.com/product-hunt/201910/6601631/1571902948981.jpg?fmt=webp&v=1">
                                </div>
                                <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    <div class="form-group">
                                        <label>Tags</label>
                                        <input type="text" class="form-control">
                                    </div>
                        <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <input type="text" class="form-control">
                                    </div>
                            </div>
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Vendor</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="product_{{$product->id}}_variants" role="tabpanel">
                            <h4 class="font-w400">Profile Content</h4>
                            <p>...</p>
                        </div>
                        <div class="tab-pane" id="product_{{$product->id}}_images" role="tabpanel">
                            <h4 class="font-w400">Profile Content</h4>
                            <p>...</p>
                        </div>
                        <div class="tab-pane" id="product_{{$product->id}}_description" role="tabpanel">
                            <h4 class="font-w400">Profile Content</h4>
                            <p>...</p>
                        </div>
                    </div>
                </div>


            @endforeach

        </div>
    </div>

@endsection
