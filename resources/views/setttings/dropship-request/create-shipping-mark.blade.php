@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Create Shipping Mark
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item"> Dropship Request</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Create Shipping Mark for {{$drop_request->product_name}}</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-header">
                        <h5 class="block-title">{{$drop_request->product_name}}
                        </h5>
                    </div>
                    <div class="block-content">
                        <form action="">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">Dropship Request Number</label>
                                        <input required class="form-control" type="text"  name="dropship_request_id" readonly value="{{ $drop_request->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">Store/User name</label>
                                        <input required class="form-control" type="text" readonly value="@if($drop_request->shop_id) {{ $drop_request->has_store->shopify_domain }} @else {{ $drop_request->has_user->name }} @endif">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">Contact</label>
                                        <input required class="form-control" type="text"  readonly value="@if($drop_request->shop_id) {{ $drop_request->has_store->has_user()->first()->email }} @else {{ $drop_request->has_user->email }} @endif">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">{{ $drop_request->product_name }}</label>
                                        <table class="table variants-div js-table-sections table-hover" style="overflow-x: hidden">
                                            <thead>
                                            <tr>
                                                <th style="vertical-align: top;">SKU</th>
                                                <th style="vertical-align: top;">Option</th>
                                                <th style="vertical-align: top;">Inventory</th>
                                                <th style="vertical-align: top;">Image</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody class="product-details-body">
                                                <tr class="single-product-details">
                                                    <td class="">
                                                        <input type="text" class="form-control" name="sku[]" value="" placeholder="Enter sku here..">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="option_count[]" placeholder="Enter number of options here..">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="inventory[]" placeholder="Enter product inventory here..">
                                                    </td>
                                                    <td class="text-center">
                                                        <img class="img-avatar " style="border: 1px solid whitesmoke"  data-input=".varaint_file_input" data-toggle="modal" data-target="#select_image_modal"
                                                               data-src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg" alt="">
                                                        <div class="modal fade" id="select_image_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                                <div class="modal-content">
                                                                    <div class="block block-themed block-transparent mb-0">
                                                                        <div class="block-header bg-primary-dark">
                                                                            <h3 class="block-title">Select Image For Product</h3>
                                                                            <div class="block-options">
                                                                                <button type="button" class="btn-block-option">
                                                                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="block-content font-size-sm">
                                                                            <a class="img-avatar-variant btn btn-sm btn-primary text-white mb2" data-form="#varaint_image_form">Upload New Picture</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-right">
                                                        <div class=" btn-group btn-group-sm" role="group">
                                                            <button type="button" class="btn btn-sm btn-primary add-product-details-tab-btn">+</button>
                                                            <button type="button" class="btn btn-sm btn-danger remove-product-details-tab-btn">-</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <div class="form-material">
                                        <button class="btn btn-primary" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
