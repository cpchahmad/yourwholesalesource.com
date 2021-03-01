@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Shipping Mark
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item">Shipping Mark</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Shipping Mark for {{$drop_request->product_name}}</a>
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
                    <div class="block-content shipping-mark-body p-5">
                        <div class="p-4 bg-white">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="">Dropship Request Number</label>
                                        <span class="d-block font-w600">{{$drop_request->id}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">Store/User name</label>
                                        <span class="d-block font-w600">@if($drop_request->shop_id) {{ $drop_request->has_store->shopify_domain }} @else {{ $drop_request->has_user->name }} @endif</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">Contact</label>
                                        <span class="d-block font-w600">@if($drop_request->shop_id) {{ $drop_request->has_store->has_user()->first()->email }} @else {{ $drop_request->has_user->email }} @endif</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">Product title</label>
                                        <span class="d-block font-w600">{{$mark->dropship_product->title}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <table class="table variants-div js-table-sections table-hover" style="overflow-x: hidden">
                                            <thead>
                                            <tr colspan="4" class="text-center font-w600">{{ $mark->dropship_product->title }}</tr>
                                            <tr>
                                                <th style="vertical-align: top;">SKU</th>
                                                <th style="vertical-align: top;">Option</th>
                                                <th style="vertical-align: top;">Inventory</th>
                                                <th style="vertical-align: top;">Image</th>
                                            </tr>
                                            </thead>
                                            <tbody class="product-details-body">
                                                @foreach($mark->dropship_product->dropship_product_variants as $variant)
                                                    <tr class="single-product-details">
                                                        <td class="">
                                                            <span>{{ $variant->sku }}</span>
                                                        </td>
                                                        <td>
                                                            <span>{{ $variant->option }}</span>
                                                        </td>
                                                        <td>
                                                            <span>{{ $variant->inventory }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <img style="width: 100%;max-width: 250px" src="{{asset('shipping-marks')}}/{{$variant->image}}" alt="">
                                                        </td>
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
        </div>
    </div>

@endsection
