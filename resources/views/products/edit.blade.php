@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div id="notification" data-route="{{route('product.notification.update',$product->id)}}"></div>
        <div class="content content-full pt-3 pb-3">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h5 my-2">
                    Edit Product
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Products</a>
                        </li>

                        <li class="breadcrumb-item">Edit Product</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div data-action="{{ route('product.update', $product->id) }}" class="form-horizontal push-30" data-method="post" data-enctype="multipart/form-data">
        <div class="content edit-content" data-route="{{route('product.update', $product->id)}}">
            <div id="forms-div" class="row">
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
                                <div class="row editable" id="image-sortable" data-product="{{$product->id}}" data-route="{{route('product.update_image_position',$product->id)}}">
                                    @foreach($product->has_images()->orderBy('position')->get() as $image)
                                        <div class="col-lg-4 preview-image animated fadeIn" data-id="{{$image->id}}">
                                            <div class="options-container fx-img-zoom-in fx-opt-slide-right">
                                                @if($image->isV == 0)
                                                    <img class="img-fluid options-item" src="{{asset('images')}}/{{$image->image}}" alt="">
                                                @else
                                                    <img class="img-fluid options-item" src="{{asset('images/variants')}}/{{$image->image}}" alt="">
                                                @endif
                                                <div class="options-overlay bg-black-75">
                                                    <div class="options-overlay-content">
                                                        <a class="btn btn-sm btn-light delete-file" data-type="existing-product-image-delete" data-token="{{csrf_token()}}" data-route="{{route('product.update',$product->id)}}" data-file="{{$image->id}}"><i class="fa fa-times"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                                <hr>
                            @endif
                            <div class="row">
                                <form class="product-images-form " action="{{route('product.update',$product->id)}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="existing-product-image-add">
                                    <div class="col-md-12" style="padding-bottom: 13px;width: 682px">
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
                    @if(count($product->hasVariants) == 0)
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Pricing</h3>
                        </div>
                        <form action="{{route('product.update',$product->id)}}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="pricing">
                            <div class="block-content">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-md-6 push-10">
                                                <label>Price</label>
                                                <input type="text" class="form-control" name="price"
                                                       value="{{$product->price}}"  placeholder="$ 0.00" required>
                                            </div>
                                            <div class="col-md-6">
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
                    @endif
                    @if($product->variants == 1)
                        <div class="block">
                            <div class="block-header d-inline-flex" style="width: 100%" >
                                <h3 class="block-title">Variant</h3>
                                <div class="text-right d-inline-block">

                                    <a class="btn btn-sm btn-light" style="margin-left: 10px;" data-toggle="modal" data-target="#edit_options">Edit Options</a>
                                </div>
                            </div>
                            <div class="block-content" style="padding-top: 0 !important;">
                                <table class="table variants-div js-table-sections table-hover table-responsive">
                                    <thead>
                                    <tr>
                                        <th style="vertical-align: top">Title</th>
                                        <th style="vertical-align: top">Image</th>
                                        <th style="vertical-align: top">Price</th>
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
                                                    <td class="variant_title">
                                                       @if($v->option1 != null) {{$v->option1}} @endif    @if($v->option2 != null) / {{$v->option2}} @endif    @if($v->option3 != null) / {{$v->option3}} @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <img class="img-avatar " style="border: 1px solid whitesmoke"  data-input=".varaint_file_input" data-toggle="modal" data-target="#select_image_modal{{$v->id}}"
                                                             @if($v->has_image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                                             @else @if($v->has_image->isV == 0) src="{{asset('images')}}/{{$v->has_image->image}}" @else src="{{asset('images/variants')}}/{{$v->has_image->image}}" @endif @endif alt="">
                                                        <div class="modal fade" id="select_image_modal{{ $v->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                                <div class="modal-content">
                                                                    <div class="block block-themed block-transparent mb-0">
                                                                        <div class="block-header bg-primary-dark">
                                                                            <h3 class="block-title">Select Image For Variant</h3>
                                                                            <div class="block-options">
                                                                                <button type="button" class="btn-block-option">
                                                                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="block-content font-size-sm">
                                                                            <div class="row">
                                                                                @foreach($product->has_images as $image)
                                                                                    <div class="col-md-4">
                                                                                        @if($image->isV == 0)
                                                                                            <img class="img-fluid options-item" src="{{asset('images')}}/{{$image->image}}" alt="">
                                                                                        @else
                                                                                            <img class="img-fluid options-item" src="{{asset('images/variants')}}/{{$image->image}}" alt="">
                                                                                        @endif
                                                                                        <p style="color: #ffffff;cursor: pointer" data-image="{{$image->id}}" data-variant="{{$v->id}}" data-type="product" class="rounded-bottom bg-info choose-variant-image text-center">Choose</p>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                            <p class="text-center font-weight-bold">OR</p>
                                                                            <hr>
                                                                            <a class="img-avatar-variant btn btn-sm btn-primary text-white mb2" data-form="#varaint_image_form_{{$index}}">Upload New Picture</a>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="price" placeholder="$0.00" value="{{$v->price}}">
                                                    </td>

                                                    <td><input type="text" class="form-control" name="cost" value="{{$v->cost}}" placeholder="$0.00"></td>
                                                    <td><input type="text" class="form-control" value="{{$v->quantity}}" name="quantity" placeholder="0"></td>
                                                    <td><input type="text" class="form-control" name="sku" value="{{$v->sku}}"></td>
                                                    <td><input type="text" class="form-control" name="barcode" value="{{$v->barcode}}" placeholder="">
                                                    </td>

                                                </tr>
                                                </tbody>
                                                <tbody>
                                                <tr>

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
                                                                    <a class="btn btn-light add-option-div">Add Other Option</a>
                                                                </div>
                                                                <div class="div2 row col-md-12" style="display: none">
                                                                    <div class="col-md-3">
                                                                        <input type="text" class="form-control" readonly value="Option2">
                                                                    </div>
                                                                    <form class="new-option-add col-md-7" action="{{route('product.update',$product->id)}}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="type" value="new-option-add">
                                                                        <div class="">
                                                                            <input type="hidden" name="option" value="option2">
                                                                            <input type="text" class="form-control option-value" name="value" value="" placeholder="Enter Only One Option Value">
                                                                        </div>
                                                                    </form>

                                                                    <div class="col-md-2">
                                                                        <a class="btn btn-light delete-option-value"><i class="fa fa-times"></i></a>
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
                                                                    <a class="btn btn-light add-option-div">Add Other Option</a>
                                                                </div>
                                                                <div class="div2 row col-md-12" style="display: none">
                                                                    <div class="col-md-3">
                                                                        <input type="text" class="form-control" readonly value="Option3">
                                                                    </div>
                                                                    <form class="new-option-add col-md-7"  action="{{route('product.update',$product->id)}}" method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="type" value="new-option-add">
                                                                        <div class="">
                                                                            <input type="hidden" name="option" value="option3">
                                                                            <input type="text" class="form-control option-value" name="value" value="" placeholder="Enter Only One Option Value">
                                                                        </div>
                                                                    </form>
                                                                    <div class="col-md-2">
                                                                        <a class="btn btn-light delete-option-value"><i class="fa fa-times"></i></a>
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
                            <div class="block-header d-inline-flex" style="width: 100%" >
                                <h3 class="block-title">No Variant For This Products</h3>
                                <div class="text-right d-inline-block" >
                                    <a href="{{route('product.existing_product_new_variants',$product->id)}}">Add New Variants</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="block">
                        <div class="block-header d-inline-flex" style="width: 100%" >
                            <h3 class="block-title">Additional Tabs</h3>
                            <div class="text-right d-inline-block">
                                <a style="margin-left: 10px;" class="btn btn-sm btn-light" data-toggle="modal" data-target="#add_tabs_modal">Add Tab</a>
                            </div>
                        </div>
                        <div class="block-content">
                            @if(count($product->has_tabs) > 0)
                                <div class="block">
                                    <ul class=" nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
                                        @foreach($product->has_tabs as $index => $tab)
                                            <li  class="nav-item" >
                                                <a class="nav-link @if($index == 0) active @endif" href="#tab{{$index}}">{{$tab->title}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="block-content tab-content">
                                        @foreach($product->has_tabs as $index => $tab)
                                            <div class="tab-pane @if($index == 0) active @endif" role="tabpanel" id="tab{{$index}}">
                                                <div class="row">
                                                    <div class="col-md-12 text-right">
                                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#edit_tabs_modal{{$index}}"> Edit Tab</button>
                                                        <button class="btn btn-sm btn-danger" onclick="window.location.href='{{route('product.tab.delete',$tab->id)}}'"> Delete Tab</button>
                                                    </div>
                                                </div>
                                                <p>{!! $tab->description !!}</p>
                                            </div>
                                            <div class="modal fade" id="edit_tabs_modal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-popout" role="document">
                                                    <div class="modal-content">
                                                        <div class="block block-themed block-transparent mb-0">
                                                            <div class="block-header bg-primary-dark">
                                                                <h3 class="block-title">Edit {{$tab->title}} tab</h3>
                                                                <div class="block-options">
                                                                    <button type="button" class="btn-block-option">
                                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <form action="{{route('product.update',$product->id)}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="type" value="edit-additional-tab">
                                                                <input type="hidden" name="tab_id" value="{{$tab->id}}">
                                                                <div class="block-content" style="padding: 20px !important;">
                                                                    <div class="row">
                                                                        <div class="col-md-12" style="margin-bottom: 10px">
                                                                            <label for="product-name">Title</label>
                                                                            <input class="form-control" type="text" id="" name="title"
                                                                                   value="{{$tab->title}}" placeholder=""  required>
                                                                        </div>
                                                                        <div class="col-md-12">
                                    <textarea class="js-summernote" name="description" required
                                              placeholder="Please Enter Description here !">{{$tab->description}}</textarea>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <button class="btn btn-primary">Save</button>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </form>
                                                            <div class="block-content block-content-full text-right border-top">

                                                                <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                                                    Discard
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p>No Tabs Found!</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal fade" id="add_tabs_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-popout" role="document">
                            <div class="modal-content">
                                <div class="block block-themed block-transparent mb-0">
                                    <div class="block-header bg-primary-dark">
                                        <h3 class="block-title">Add Additional Tabs</h3>
                                        <div class="block-options">
                                            <button type="button" class="btn-block-option">
                                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <form action="{{route('product.update',$product->id)}}" method="post">
                                        @csrf
                                        <input type="hidden" name="type" value="add-additional-tab">
                                        <div class="block-content" style="padding: 20px !important;">
                                            <div class="row">
                                                <div class="col-md-12" style="margin-bottom: 10px">
                                                    <label for="product-name">Title</label>
                                                    <input class="form-control" type="text" id="" name="title"
                                                           value=""  placeholder="" required>
                                                </div>
                                                <div class="col-md-12">
                                    <textarea class="js-summernote" name="description" required
                                              placeholder="Please Enter Description here !"></textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <button class="btn btn-primary">Save</button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                    <div class="block-content block-content-full text-right border-top">

                                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">
                                            Discard
                                        </button>
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
                        <form action="{{route('product.update',$product->id)}}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="fulfilled">
                            <div class="block-content" >
                                <div class="form-group">
                                    <div class="custom-control custom-radio mb-1">
                                        <input type="radio" required class="custom-control-input" id="example-radio-customFantasy"  name="fulfilled-by" value="Fantasy" @if($product->fulfilled_by == 'Fantasy') checked @endif >
                                        <label class="custom-control-label" for="example-radio-customFantasy">By WeFullFill</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-1">
                                        <input type="radio" required class="custom-control-input" id="example-radio-customAliExpress" name="fulfilled-by" value="AliExpress" @if($product->fulfilled_by == 'AliExpress') checked @endif >
                                        <label class="custom-control-label" for="example-radio-customAliExpress">By AliExpress</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <div class="block-title">
                                Marketing Video
                            </div>
                        </div>
                        <form action="{{route('product.update',$product->id)}}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="marketing_video_update">
                            <div class="block-content pt-0" >
                                <div class="form-group">
                                        <input type="text" name="marketing_video" value="{{$product->marketing_video}}" placeholder="Embedded Youtube Code to Marketing Video">
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="block">
                        <div class="block-header">
                            <div class="block-title">
                                Product Category
                            </div>
                        </div>
                        <form action="{{route('product.update',$product->id)}}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="category">
                            <div class="block-content" style="height: 200px;overflow: auto;overflow-x: hidden;">
                                <div class="form-group product_category">
                                    @foreach($categories as $category)
                                        <span class="category_down" data-value="0" style="margin-right: 5px;font-size: 16px"> <i class="fa fa-angle-right"></i></span>
                                        <div class="custom-control custom-checkbox d-inline-block">
                                            <input type="checkbox" name="category[]" value="{{$category->id}}" class="custom-control-input category_checkbox"
                                                   @if(in_array($category->id,$product->category($product))) checked @endif
                                                   id="rowcat_{{$category->title}}">
                                            <label class="custom-control-label" for="rowcat_{{$category->title}}">{{$category->title}}</label>
                                        </div>

                                        <div class="row product_sub_cat" style="display: none">
                                            <div class="col-xs-12 col-xs-push-1">
                                                @foreach($category->hasSub as $sub)
                                                    <div class="custom-control custom-checkbox d-inline-block">
                                                        <input type="checkbox" name="sub_cat[]" value="{{$sub->id}}" class="custom-control-input sub_cat_checkbox"
                                                               @if(in_array($sub->id,$product->subcategory($product))) checked @endif
                                                               id="rowsub_{{$sub->title}}">
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
                        </form>
                        <div class="block-footer" style="height: 15px">

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
                                        <label>Processing Time</label>
                                        <input type="text" class="form-control" name="processing_time" placeholder="eg. 7 working days" value="{{$product->processing_time}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12 push-10">
                                        <label>Warned Platform</label>
                                        <br>
                                        @foreach($platforms as $platform)
                                            <div class="custom-control custom-checkbox d-inline-block">
                                                <input type="checkbox" name="platforms[]" value="{{$platform->id}}"
                                                       @if(in_array($platform->id,$product->warned_platforms($product))) checked @endif
                                                       class="custom-control-input" id="row_{{$platform->name}}">
                                                <label class="custom-control-label" for="row_{{$platform->name}}">{{$platform->name}}</label>
                                            </div>
                                            <br>
                                        @endforeach
                                    </div>
                                    {{--                                        <input type="submit" value="save">--}}
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <label>Product Status</label>
                                        <br>
                                        <div class="custom-control custom-radio mb-1">
                                            <input type="radio" class="custom-control-input" id="example-radio-customPublished" @if($product->status == 1) checked="" @endif name="status" value="1" checked="">
                                            <label class="custom-control-label" for="example-radio-customPublished">Published</label>
                                        </div>
                                        <div class="custom-control custom-radio mb-1">
                                            <input type="radio" class="custom-control-input" id="example-radio-customDraft" @if($product->status == 0) checked="" @endif name="status" value="0" >
                                            <label class="custom-control-label" for="example-radio-customDraft">Draft</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Preferences</h3>
                        </div>
                        <form action="{{route('product.update',$product->id)}}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="shop-preferences">
                            <div class="block-content">
                                <div class="form-group">
                                    <div class="custom-control custom-radio mb-1">
                                        <input type="radio" required class="custom-control-input preference-check" id="prefer-global" name="global" value="1" @if($product->global == 1) checked="" @endif>
                                        <label class="custom-control-label " for="prefer-global">Global</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-1">
                                        <input type="radio" required class="custom-control-input preference-check" id="prefer-store" name="global" value="0"  @if($product->global == 0) checked="" @endif>
                                        <label class="custom-control-label" for="prefer-store">Selected Stores</label>
                                    </div>
                                </div>
                                <div class="form-group" @if($product->global == 1) style="display: none" @endif>
                                    <div class="form-material">
                                        <label for="material-error">Stores <i class="fa fa-question-circle"  title="Store where product you want to show."> </i></label>
                                        <select class="form-control shop-preference js-select2" style="width: 100%;" data-placeholder="Choose multiple markets.." name="shops[]"   multiple="">
                                            <option></option>

                                            @foreach($shops as $shop)
                                                <option @if(in_array($shop->id,$product->has_preferences->pluck('id')->toArray())) selected @endif
                                                value="{{$shop->id}}">{{explode('.',$shop->shopify_domain)[0]}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
{{--            <div class="row">--}}
{{--                <div class="col-md-8">--}}
{{--                   --}}

{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-8">--}}

{{--                 --}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="content" style="margin-bottom: 10px">
                <div class="row ">
                    <div class="col-sm-12 text-right">
                        <hr>
                        <a class="btn btn-primary text-white btn-square submit_all">Update</a>
                        <a href="{{ route('product.edit',$product->id) }}" class="btn btn-default btn-square">Discard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
