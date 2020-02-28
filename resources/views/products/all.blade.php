@extends('layout.index')
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="font-w700">All Products</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('product.create') }}" class="btn btn-primary btn-square">Add New Product</a>
            </div>
        </div>
        <div class="block">
            <div class="block-header">
                <div class="block-options">
                </div>
                <h3 class="block-title">All Products</h3>
            </div>
            <div class="block-content">
                <table class="js-table-sections table table-hover">
                    <thead>
                    <tr>
                        <th style="width: 30px;"></th>
                        <th>Title</th>
                        <th>Vendor</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>SKU</th>
                        <th>Barcode</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <?php $i = 1;?>

                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td class="text-center">
                                {{ $i++ }}
                            </td>
                            <td class="font-w600">{{ $product->title }}</td>
                            <td>{{ $product->vendor }}</td>
                            <td>{{ $product->type }}</td>
                            <td>
                                ${{ $product->price }}
                            </td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->barcode }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-primary" type="button" data-toggle="modal"
                                            data-target="#modal-popin{{$product->id}}" title="View Product"><i
                                            class="fa fa-clipboard"></i></button>
                                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-xs btn-warning"
                                       type="button" data-toggle="tooltip" title=""
                                       data-original-title="Edit Product"><i
                                            class="fa fa-pencil"></i></a>
                                    <a href="{{ route('product.delete', $product->id) }}" class="btn btn-xs btn-danger"
                                       type="button" data-toggle="tooltip" title=""
                                       data-original-title="Delete Product"><i class="fa fa-times"></i></a>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="modal-popin{{$product->id}}" tabindex="-1" role="dialog"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-popin">
                                <div class="modal-content">
                                    <div class="block block-themed block-transparent remove-margin-b">
                                        <div class="block-header bg-primary">
                                            <ul class="block-options">
                                                <li>
                                                    <button data-dismiss="modal" type="button"><i
                                                            class="si si-close"></i></button>
                                                </li>
                                            </ul>
                                            <h3 class="block-title">Product Details</h3>
                                        </div>
                                        <div class="block-content">
                                            <div class="row">
                                                <?php $img = json_decode($product->images)?>
                                                <div class="col-sm-5">
                                                    <img src="{{ asset('images/'.$img[1]) }}" alt=""
                                                         style="width: 100%;height: 100%;">
                                                </div>
                                                <div class="col-sm-7">
                                                    <h3 class="font-w300 text-center text-capitalize">{{ $product->title }}</h3>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <span>Type</span>
                                                        </div>
                                                        <div class="col-sm-8 text-center">
                                                            <span
                                                                class="text-capitalize">{{ $product->type }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <span>Vendor</span>
                                                        </div>
                                                        <div class="col-sm-8 text-center">
                                                            <span
                                                                class="text-capitalize">{{ $product->vendor }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <span>Price</span>
                                                        </div>
                                                        <div class="col-sm-6 text-center">
                                                            <span
                                                                class="text-capitalize">${{ $product->price }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <span>Quantity</span>
                                                        </div>
                                                        <div class="col-sm-6 text-center">
                                                            <span
                                                                class="text-capitalize">{{ $product->quantity }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <span>SKU</span>
                                                        </div>
                                                        <div class="col-sm-6 text-center">
                                                            <span
                                                                class="text-capitalize">{{ $product->sku }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <span>Barcode</span>
                                                        </div>
                                                        <div class="col-sm-6 text-center">
                                                            <span
                                                                class="text-capitalize">{{ $product->barcode }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <span>Total Variants</span>
                                                        </div>
                                                        <div class="col-sm-6 text-center">
                                                            <span
                                                                class="text-capitalize">{{ count($product->hasVariants) }}</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h3 class="font-w300">Description</h3>
                                                    @if ($product->description)
                                                        <p>{{ strip_tags($product->description) }}</p>
                                                    @else
                                                        <p class="text-warning text-center">No Description Avialable</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
