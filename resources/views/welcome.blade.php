@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
               Dashboard
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row mb-2">
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <form action="" method="get" class="d-flex">
                    <input type="text" required class="js-flatpickr form-control bg-white flatpickr-input"  name="date-range" placeholder="@if($date_range != null) {{$date_range}}  @else Select Date Range For Filtering @endif " data-mode="range"  readonly="readonly">
                    <input type="submit" class="btn btn-primary" style="margin-left: 10px" value="Filter">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Orders</div>
                        <div class="font-size-h2 font-w400 text-dark">{{$orders}}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Sales</div>
                        <div class="font-size-h2 font-w400 text-dark">${{number_format($sales,2)}}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Refunds</div>
                        <div class="font-size-h2 font-w400 text-dark">${{number_format($refunds,2)}}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">New Store</div>
                        <div class="font-size-h2 font-w400 text-dark">{{$stores}}</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="block block-rounded block-link-pop">
                    <div class="block-content block-content-full">
                        <canvas id="canvas-graph-one" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_one_values)}}"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded block-link-pop">
                    <div class="block-content block-content-full">
                        <canvas id="canvas-graph-two" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_two_values)}}"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="block block-rounded block-link-pop">
                    <div class="block-content block-content-full">
                        <canvas id="canvas-graph-three" data-labels="{{json_encode($graph_three_labels)}}" data-values="{{json_encode($graph_three_values)}}"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded block-link-pop">
                    <div class="block-content block-content-full">
                        <canvas id="canvas-graph-four" data-labels="{{json_encode($graph_four_labels)}}" data-values="{{json_encode($graph_four_values)}}"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Top Products</h3>
                    </div>
                    <div class="block-content ">
                        @if(count($top_products) > 0)
                            <table class="table table-striped table-hover table-borderless table-vcenter">
                                <thead>
                                <tr class="text-uppercase">
                                    <th class="font-w700">Product</th>
                                    <th class="d-none d-sm-table-cell font-w700 text-center" style="width: 80px;">Quantity</th>
                                    <th class="font-w700 text-center" style="width: 60px;">Sales</th>
                                </tr>
                                </thead>
                                <tbody>

                                    @foreach($top_products as $product)
                                        <tr>
                                    <td class="font-w600">
                                        @foreach($product->has_images()->orderBy('position')->get() as $index => $image)
                                            @if($index == 0)
                                                @if($image->isV == 0)
                                                    <img class="img-avatar img-avatar32" style="margin-right: 5px" src="{{asset('images')}}/{{$image->image}}" alt="">
                                                @else
                                                    <img class="img-avatar img-avatar32" style="margin-right: 5px" src="{{asset('images/variants')}}/{{$image->image}}" alt="">
                                                @endif
                                            @endif
                                        @endforeach
                                      {{$product->title}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center">
                                        {{$product->sold}}
                                    </td>
                                    <td class="">
                                       ${{number_format($product->selling_cost,2)}}
                                    </td>
                                        </tr>
                                        @endforeach

                                </tbody>
                                @else
                                    <p  class="text-center"> No Top Users Found </p>
                                @endif
                            </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Top Stores</h3>
                    </div>
                    <div class="block-content ">
                        @if(count($top_stores) > 0)
                        <table class="table table-striped table-hover table-borderless table-vcenter">
                            <thead>
                            <tr class="text-uppercase">
                                <th class="font-w700">Store</th>
                                <th class="d-none d-sm-table-cell font-w700 text-center" style="width: 80px;">Orders</th>
                                <th class="font-w700 text-center" style="width: 60px;">Sales</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($top_stores as $store)
                                <tr>
                                    <td class="font-w600">
                                        {{explode('.',$store->shopify_domain)[0]}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center">
                                        {{$store->sold}}
                                    </td>
                                    <td class="">
                                        ${{number_format($store->selling_cost,2)}}
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        @else
                            <p  class="text-center"> No Top Users Found </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Top Non Shopify Users</h3>
                    </div>
                    <div class="block-content ">
                        @if(count($top_users) > 0)
                        <table class="table table-striped table-hover table-borderless table-vcenter">
                            <thead>
                            <tr class="text-uppercase">
                                <th class="font-w700">User</th>
                                <th class="font-w700">Email</th>
                                <th class="d-none d-sm-table-cell font-w700 text-center" style="width: 80px;">Orders</th>
                                <th class="font-w700 text-center" style="width: 60px;">Sales</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($top_users as $user)
                                <tr>
                                    <td class="font-w600">
                                        {{$user->name}} {{$user->last_name}}
                                    </td>
                                    <td class="font-w600">
                                        {{$user->email}}
                                    </td>
                                    <td class="d-none d-sm-table-cell text-center">
                                        {{$user->sold}}
                                    </td>
                                    <td class="">
                                        ${{number_format($user->selling_cost,2)}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @else
                            <p  class="text-center"> No Top Users Found </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
