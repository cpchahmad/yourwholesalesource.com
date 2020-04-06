@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Products
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Products</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="row mb-3">
            <div class="col-sm-6">

            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('product.create') }}" class="btn btn-success btn-square ">Add New Product</a>
            </div>
        </div>
        <div class="block">
            <div class="block-content">
                @if(count($products) >0)
                    <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                    <thead>
                    <tr>
                        <th style="width:5% "></th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td class="text-center">
                                <a href="{{ route('product.view', $product->id) }}">
                                <img class="img-avatar2" style="max-width:100px;border: 1px solid whitesmoke"
                                     @if(count($product->has_images) > 0)
                                     @if($product->has_images[0]->isV == 0)
                                     src="{{asset('images')}}/{{$product->has_images[0]->image}}"
                                     @else src="{{asset('images/variants')}}/{{$product->has_images[0]->image}}"
                                         @endif
                                     @else
                                      src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                     @endif
                                     alt="">
                                </a>
                            </td>
                            <td class="font-w600" style="vertical-align: middle">
                                <a href="{{ route('product.view', $product->id) }}">
                                {{ $product->title }}
                                </a>
                            </td>

                            <td style="vertical-align: middle">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td style="vertical-align: middle">{{ $product->quantity }}</td>
                            <td style="vertical-align: middle">
                                <div class="custom-control custom-switch custom-control-success mb-1">
                                    <input @if($product->status ==1)checked="" @endif data-route="{{route('product.update',$product->id)}}" data-csrf="{{csrf_token()}}" type="checkbox" class="custom-control-input status-switch" id="status_product_{{ $product->id }}" name="example-sw-success2">
                                    <label class="custom-control-label" for="status_product_{{ $product->id }}">@if($product->status ==1) Published @else Draft @endif</label>
                                </div>

                            </td>
                            <td class="text-right" style="vertical-align: middle">

                    <div class="btn-group mr-2 mb-2" role="group" aria-label="Alternate Primary First group">
                                    <a class="btn btn-xs btn-sm btn-success" type="button" href="{{ route('product.view', $product->id) }}" title="View Product">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-warning"
                                       type="button" data-toggle="tooltip" title=""
                                       data-original-title="Edit Product"><i
                                            class="fa fa-edit"></i></a>
                                    <a href="{{ route('product.delete', $product->id) }}" class="btn btn-sm btn-danger"
                                       type="button" data-toggle="tooltip" title=""
                                       data-original-title="Delete Product"><i class="fa fa-times"></i></a>
                    </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                    </div>
                    @else
                    <p>No Products created.</p>
                    @endif
            </div>
        </div>
    </div>
@endsection
