@extends('layout.index')
@section('content')
    <style>
        .mb2{
            margin-bottom: 10px !important;
        }
        .img-avatar2 {
            display: inline-block !important;
            width: 50px;
            height: 50px;
            /* border-radius: 50%; */
        }
    </style>
    <div class="content">
        <div class="row mb2">
            <div class="col-sm-6">
                <h3 class="font-w700">All Products</h3>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('product.create') }}" class="btn btn-info btn-square ">Add New Product</a>
            </div>
        </div>
        <div class="block">
            <div class="block-content">
                @if(count($products) >0)
                <table class="js-table-sections table table-hover">
                    <thead>
                    <tr>
                        <th style="width: 30px;">#</th>
                        <th style="width:5% "></th>
                        <th>Title</th>
                        <th>Vendor</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <?php $i = 1;?>

                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td class="text-center" style="vertical-align: middle">
                                {{ $i++ }}
                            </td>
                            <td class="text-center">
                                <img class="img-avatar2" style="border: 1px solid whitesmoke"
                                     @if(count($product->has_images) > 0)
                                     @if($product->has_images[0]->isV == 0)
                                     src="{{asset('images')}}/{{$product->has_images[0]->image}}"
                                     @else src="{{asset('images/variants')}}/{{$product->has_images[0]->image}}"
                                         @endif
                                     @else
                                      src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                     @endif
                                     alt="">
                            </td>
                            <td class="font-w600" style="vertical-align: middle">
                                {{ $product->title }}</td>
                            <td style="vertical-align: middle">{{ $product->vendor }}</td>
                            <td style="vertical-align: middle">
                                ${{ $product->price }}
                            </td>
                            <td style="vertical-align: middle">{{ $product->quantity }}</td>
                            <td style="vertical-align: middle">
                                <label class="css-input switch  switch-sm switch-primary">
                                    <input data-route="{{route('product.update',$product->id)}}" data-csrf="{{csrf_token()}}"  class="status-switch" type="checkbox" @if($product->status ==1)checked="" @endif><span></span>
                                   <span class="status-text">@if($product->status ==1) Published @else Draft @endif</span>
                                </label>
                            </td>
                            <td class="text-right" style="vertical-align: middle">

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
                    @else
                <p>No Products Created</p>
                    @endif
            </div>
        </div>
    </div>
@endsection
