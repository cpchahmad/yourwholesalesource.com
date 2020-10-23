@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Email Templates
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Email Templates</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
{{--        <div class="row mb-3">--}}
{{--            <div class="col-sm-3 text-right">--}}
{{--                <a href="{{ route('product.create') }}" class="btn btn-success btn-square ">Add New Product</a>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="block">
            <div class="block-content">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped table-vcenter">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-w600" style="vertical-align: middle">
                                        <a href="{{ route('admin.emails.show', 1) }}">New user Registration Template</a>
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{--                                        <div class="custom-control custom-switch custom-control-success mb-1">--}}
                                        {{--                                            <input @if($product->status ==1)checked="" @endif data-route="{{route('product.update',$product->id)}}" data-csrf="{{csrf_token()}}" type="checkbox" class="custom-control-input status-switch" id="status_product_{{ $product->id }}" name="example-sw-success2">--}}
                                        {{--                                            <label class="custom-control-label" for="status_product_{{ $product->id }}">@if($product->status ==1) Published @else Draft @endif</label>--}}
                                        {{--                                        </div>--}}
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" class="custom-control-input status-switch"  name="example-sw-success2">
                                            <label class="custom-control-label">Draft</label>
                                        </div>
                                    </td>
                                    <td class="text-right" style="vertical-align: middle">

                                        <div class="btn-group mr-2 mb-2" role="group" aria-label="Alternate Primary First group">
                                            <a class="btn btn-xs btn-sm btn-success" href="{{ route('admin.emails.show', 1) }}" type="button"  title="View Template">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a  class="btn btn-sm btn-warning" href="{{ route('admin.emails.show', 1) }}"
                                                type="button" data-toggle="tooltip" title=""
                                                data-original-title="Edit Tempalte"><i
                                                    class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600" style="vertical-align: middle">
                                        <a href="{{ route('admin.emails.show', 2) }}">Shopify user Registration Template</a>
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{--                                        <div class="custom-control custom-switch custom-control-success mb-1">--}}
                                        {{--                                            <input @if($product->status ==1)checked="" @endif data-route="{{route('product.update',$product->id)}}" data-csrf="{{csrf_token()}}" type="checkbox" class="custom-control-input status-switch" id="status_product_{{ $product->id }}" name="example-sw-success2">--}}
                                        {{--                                            <label class="custom-control-label" for="status_product_{{ $product->id }}">@if($product->status ==1) Published @else Draft @endif</label>--}}
                                        {{--                                        </div>--}}
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" class="custom-control-input status-switch"  name="example-sw-success2">
                                            <label class="custom-control-label">Draft</label>
                                        </div>
                                    </td>
                                    <td class="text-right" style="vertical-align: middle">

                                        <div class="btn-group mr-2 mb-2" role="group" aria-label="Alternate Primary First group">
                                            <a class="btn btn-xs btn-sm btn-success" href="{{ route('admin.emails.show', 2) }}" type="button"  title="View Template">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a  class="btn btn-sm btn-warning" href="{{ route('admin.emails.show', 2) }}"
                                                type="button" data-toggle="tooltip" title=""
                                                data-original-title="Edit Tempalte"><i
                                                    class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600" style="vertical-align: middle">
                                        <a href="{{ route('admin.emails.show', 3) }}">Order Place Template</a>
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{--                                        <div class="custom-control custom-switch custom-control-success mb-1">--}}
                                        {{--                                            <input @if($product->status ==1)checked="" @endif data-route="{{route('product.update',$product->id)}}" data-csrf="{{csrf_token()}}" type="checkbox" class="custom-control-input status-switch" id="status_product_{{ $product->id }}" name="example-sw-success2">--}}
                                        {{--                                            <label class="custom-control-label" for="status_product_{{ $product->id }}">@if($product->status ==1) Published @else Draft @endif</label>--}}
                                        {{--                                        </div>--}}
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" class="custom-control-input status-switch"  name="example-sw-success2">
                                            <label class="custom-control-label">Draft</label>
                                        </div>
                                    </td>
                                    <td class="text-right" style="vertical-align: middle">

                                        <div class="btn-group mr-2 mb-2" role="group" aria-label="Alternate Primary First group">
                                            <a class="btn btn-xs btn-sm btn-success" href="{{ route('admin.emails.show', 3) }}" type="button"  title="View Template">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a  class="btn btn-sm btn-warning" href="{{ route('admin.emails.show', 3) }}"
                                                type="button" data-toggle="tooltip" title=""
                                                data-original-title="Edit Tempalte"><i
                                                    class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600" style="vertical-align: middle">
                                        <a href="{{ route('admin.emails.show', 4) }}">Order Status Template</a>
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{--                                        <div class="custom-control custom-switch custom-control-success mb-1">--}}
                                        {{--                                            <input @if($product->status ==1)checked="" @endif data-route="{{route('product.update',$product->id)}}" data-csrf="{{csrf_token()}}" type="checkbox" class="custom-control-input status-switch" id="status_product_{{ $product->id }}" name="example-sw-success2">--}}
                                        {{--                                            <label class="custom-control-label" for="status_product_{{ $product->id }}">@if($product->status ==1) Published @else Draft @endif</label>--}}
                                        {{--                                        </div>--}}
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" class="custom-control-input status-switch"  name="example-sw-success2">
                                            <label class="custom-control-label">Draft</label>
                                        </div>
                                    </td>
                                    <td class="text-right" style="vertical-align: middle">

                                        <div class="btn-group mr-2 mb-2" role="group" aria-label="Alternate Primary First group">
                                            <a class="btn btn-xs btn-sm btn-success" href="{{ route('admin.emails.show', 4) }}" type="button"  title="View Template">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a  class="btn btn-sm btn-warning" href="{{ route('admin.emails.show', 4) }}"
                                                type="button" data-toggle="tooltip" title=""
                                                data-original-title="Edit Tempalte"><i
                                                    class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600" style="vertical-align: middle">
                                        <a href="{{ route('admin.emails.show', 5) }}">Wishlist Request Template</a>
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{--                                        <div class="custom-control custom-switch custom-control-success mb-1">--}}
                                        {{--                                            <input @if($product->status ==1)checked="" @endif data-route="{{route('product.update',$product->id)}}" data-csrf="{{csrf_token()}}" type="checkbox" class="custom-control-input status-switch" id="status_product_{{ $product->id }}" name="example-sw-success2">--}}
                                        {{--                                            <label class="custom-control-label" for="status_product_{{ $product->id }}">@if($product->status ==1) Published @else Draft @endif</label>--}}
                                        {{--                                        </div>--}}
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" class="custom-control-input status-switch"  name="example-sw-success2">
                                            <label class="custom-control-label">Draft</label>
                                        </div>
                                    </td>
                                    <td class="text-right" style="vertical-align: middle">

                                        <div class="btn-group mr-2 mb-2" role="group" aria-label="Alternate Primary First group">
                                            <a class="btn btn-xs btn-sm btn-success" href="{{ route('admin.emails.show', 5) }}" type="button"  title="View Template">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a  class="btn btn-sm btn-warning" href="{{ route('admin.emails.show', 5) }}"
                                                type="button" data-toggle="tooltip" title=""
                                                data-original-title="Edit Tempalte"><i
                                                    class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600" style="vertical-align: middle">
                                        <a href="{{ route('admin.emails.show', 6) }}">Wallet Request Template</a>
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{--                                        <div class="custom-control custom-switch custom-control-success mb-1">--}}
                                        {{--                                            <input @if($product->status ==1)checked="" @endif data-route="{{route('product.update',$product->id)}}" data-csrf="{{csrf_token()}}" type="checkbox" class="custom-control-input status-switch" id="status_product_{{ $product->id }}" name="example-sw-success2">--}}
                                        {{--                                            <label class="custom-control-label" for="status_product_{{ $product->id }}">@if($product->status ==1) Published @else Draft @endif</label>--}}
                                        {{--                                        </div>--}}
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" class="custom-control-input status-switch"  name="example-sw-success2">
                                            <label class="custom-control-label">Draft</label>
                                        </div>
                                    </td>
                                    <td class="text-right" style="vertical-align: middle">

                                        <div class="btn-group mr-2 mb-2" role="group" aria-label="Alternate Primary First group">
                                            <a class="btn btn-xs btn-sm btn-success" href="{{ route('admin.emails.show', 6) }}" type="button"  title="View Template">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a  class="btn btn-sm btn-warning" href="{{ route('admin.emails.show', 6) }}"
                                                type="button" data-toggle="tooltip" title=""
                                                data-original-title="Edit Tempalte"><i
                                                    class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-w600" style="vertical-align: middle">
                                        <a href="{{ route('admin.emails.show', 7) }}">Refund Request Template</a>
                                    </td>
                                    <td style="vertical-align: middle">
                                        {{--                                        <div class="custom-control custom-switch custom-control-success mb-1">--}}
                                        {{--                                            <input @if($product->status ==1)checked="" @endif data-route="{{route('product.update',$product->id)}}" data-csrf="{{csrf_token()}}" type="checkbox" class="custom-control-input status-switch" id="status_product_{{ $product->id }}" name="example-sw-success2">--}}
                                        {{--                                            <label class="custom-control-label" for="status_product_{{ $product->id }}">@if($product->status ==1) Published @else Draft @endif</label>--}}
                                        {{--                                        </div>--}}
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                            <input type="checkbox" class="custom-control-input status-switch"  name="example-sw-success2">
                                            <label class="custom-control-label">Draft</label>
                                        </div>
                                    </td>
                                    <td class="text-right" style="vertical-align: middle">

                                        <div class="btn-group mr-2 mb-2" role="group" aria-label="Alternate Primary First group">
                                            <a class="btn btn-xs btn-sm btn-success" href="{{ route('admin.emails.show', 7) }}" type="button"  title="View Template">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a  class="btn btn-sm btn-warning" href="{{ route('admin.emails.show', 7) }}"
                                                type="button" data-toggle="tooltip" title=""
                                                data-original-title="Edit Tempalte"><i
                                                    class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
{{--                <div class="row">--}}
{{--                    <div class="col-md-12 text-center" style="font-size: 17px">--}}
{{--                        {!! $products->links() !!}--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection
