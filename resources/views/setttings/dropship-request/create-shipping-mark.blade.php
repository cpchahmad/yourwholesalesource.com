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
                            <a class="link-fx" href=""> {{$item->product_name}} Create Shipping Mark</a>
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
                        <h5 class="block-title">{{$item->product_name}}
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
                                        <input required class="form-control" type="text"  readonly value="@if($drop_request->shop_id) {{ $drop_request->has_store->has_user->email }} @else {{ $drop_request->has_user->email }} @endif">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">{{ $drop_request->product_name }}</label>
                                        <table class="table variants-div js-table-sections table-hover table-responsive" style="overflow-x: hidden">
                                            <thead>
                                            <tr>
                                                <th style="vertical-align: top;width: 10%;">SKU</th>
                                                <th style="vertical-align: top;width: 12%;">Option</th>
                                                <th style="vertical-align: top;width: 15%;">Inventory</th>
                                            </tr>
                                            </thead>
                                            <tbody class="product-details-body">
                                                <tr>
                                                    <td class="">
                                                        <input type="text" class="form-control" name="sku[]" value="" placeholder="Enter sku here..">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="option_count[]" placeholder="Enter number of options here..">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="inventory[]" placeholder="Enter product inventory here..">
                                                    </td>
                                                    <td>
                                                        <div class="col-md-2 btn-group btn-group-sm" role="group">
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
                                <div class="col-sm-12">
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
