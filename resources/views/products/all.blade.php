@extends('layout.index')
@section('content')
    <div class="content">
        <div class="row" style="margin-bottom: 10px">
            <div class="col-sm-6">
                <h3 class="font-w700">All Products</h3>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('product.create') }}" class="btn btn-info btn-square ">Add New Product</a>
            </div>
        </div>
        <div class="block">
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
                            <td class="text-center">

                                    <a class="btn btn-xs btn-primary" type="button" href="{{ route('product.view', $product->id) }}" {{-- data-toggle="modal"
                                            data-target="#modal-popin{{$product->id}}"--}} title="View Product"><i
                                            class="fa fa-eye"></i></a>
                                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-xs btn-warning"
                                       type="button" data-toggle="tooltip" title=""
                                       data-original-title="Edit Product"><i
                                            class="fa fa-pencil"></i></a>
                                    <a href="{{ route('product.delete', $product->id) }}" class="btn btn-xs btn-danger"
                                       type="button" data-toggle="tooltip" title=""
                                       data-original-title="Delete Product"><i class="fa fa-times"></i></a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
