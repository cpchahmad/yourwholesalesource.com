@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Dropship Products
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Dropship Products</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="row mb-3">
            <div class="col-sm-9">
                <form action="" method="GET" class="d-flex">
                    <input type="search" class="form-control d-inline-block" value="{{$search}}" name="search" placeholder="Search By Title, SKU..">
                    <input type="submit" value="Search" class="btn btn-primary btn-sm  d-inline-block" style="margin-left: 10px">
                </form>
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
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="text-center">
                                        <a href="@role('wordpress-admin') {{ route('product.view', $product->id) }} @else {{ route('managers.products.view', $product->id) }} @endrole">
                                            @if(count($product->has_images) > 0)
                                                @foreach($product->has_images()->orderBy('position')->get() as $index => $image)
                                                    @if($index == 0)
                                                        @if($image->isV == 0)
                                                            <img class="img-avatar2" style="max-width:100px;border: 1px solid whitesmoke" data-src="{{asset('shipping-marks')}}/{{$image->image}}">
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @else
                                                <img class="img-avatar2" style="max-width:100px;border: 1px solid whitesmoke" data-src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                                            @endif
                                        </a>
                                    </td>
                                    <td class="font-w600" style="vertical-align: middle">
                                        <a href="@role('wordpress-admin') {{ route('product.view', $product->id) }} @else {{ route('managers.products.view', $product->id) }} @endrole">
                                            {{ $product->title }}
                                        </a>
                                    </td>

                                    <td style="vertical-align: middle">
                                        From.
                                        @if(count($product->hasVariants) > 0)
                                            ${{ number_format($product->hasVariants->min('price'), 2) }}
                                        @else
                                            ${{ number_format($product->price, 2) }}
                                        @endif

                                    </td>
                                    <td style="vertical-align: middle">
                                        @if($product->quantity > 0)
                                            @if($product->varaint_count($product) > 0 && count($product->hasVariants) > 0)
                                                {{$product->varaint_count($product)}}
                                            @else
                                                {{$product->quantity}}
                                            @endif
                                        @else
                                            {{$product->quantity}}
                                        @endif
                                    </td>
                                    <td class="text-right" style="vertical-align: middle">
                                        <div class="btn-group mr-2 mb-2" role="group" aria-label="Alternate Primary First group">
                                            <a class="btn btn-xs btn-sm btn-success" type="button" href="@role('wordpress-admin') {{ route('product.view', $product->id) }} @else {{ route('managers.products.view', $product->id) }} @endrole" title="View Product">
                                                <i class="fa fa-eye"></i>
                                            </a>
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
                <div class="row">
                    <div class="col-md-12 text-center" style="font-size: 17px">
                        {!! $products->appends(request()->input())->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
