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
                    <input type="text" required class="js-flatpickr form-control bg-white flatpickr-input"  name="date-range" placeholder="Select Date Range For Filtering" data-mode="range" value="{{$date_range}}"  readonly="readonly">
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
    </div>

@endsection
