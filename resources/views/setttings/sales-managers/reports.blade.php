@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    {{$manager->email}}
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Sales Managers </li>
                        <li class="breadcrumb-item">Report </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row mb-2" style="padding-bottom:1.875rem">
            <div class="col-md-4 d-flex">
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
{{--                    <span>{{$date_range}}</span> <i class="fa fa-caret-down"></i>--}}
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
                <button class="btn btn-primary filter_by_date" data-url="{{route('sales-managers.report', $manager->id)}}" style="margin-left: 10px"> Filter </button>
            </div>
        </div>
{{--        <div class="row">--}}
{{--            <div class="col-6 col-md-3 col-lg-6 col-xl-3">--}}
{{--                <a class="block block-rounded block-link-pop" href="javascript:void(0)">--}}
{{--                    <div class="block-content block-content-full">--}}
{{--                        <div class="font-size-sm font-w600 text-uppercase text-muted">Orders</div>--}}
{{--                        <div class="font-size-h2 font-w400 text-dark">{{$orders}}</div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="col-6 col-md-3 col-lg-6 col-xl-3">--}}
{{--                <a class="block block-rounded block-link-pop" href="javascript:void(0)">--}}
{{--                    <div class="block-content block-content-full">--}}
{{--                        <div class="font-size-sm font-w600 text-uppercase text-muted">Sales</div>--}}
{{--                        <div class="font-size-h2 font-w400 text-dark">${{number_format($sales,2)}}</div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="col-6 col-md-3 col-lg-6 col-xl-3">--}}
{{--                <a class="block block-rounded block-link-pop" href="javascript:void(0)">--}}
{{--                    <div class="block-content block-content-full">--}}
{{--                        <div class="font-size-sm font-w600 text-uppercase text-muted">Refunds</div>--}}
{{--                        <div class="font-size-h2 font-w400 text-dark">${{number_format($refunds,2)}}</div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="col-6 col-md-3 col-lg-6 col-xl-3">--}}
{{--                <a class="block block-rounded block-link-pop" href="javascript:void(0)">--}}
{{--                    <div class="block-content block-content-full">--}}
{{--                        <div class="font-size-sm font-w600 text-uppercase text-muted">New Store</div>--}}
{{--                        <div class="font-size-h2 font-w400 text-dark">{{$stores}}</div>--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-6">--}}
{{--                <div class="block block-rounded block-link-pop">--}}
{{--                    <div class="block-content block-content-full">--}}
{{--                        <canvas id="canvas-graph-one" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_one_values)}}"></canvas>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-6">--}}
{{--                <div class="block block-rounded block-link-pop">--}}
{{--                    <div class="block-content block-content-full">--}}
{{--                        <canvas id="canvas-graph-two" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_two_values)}}"></canvas>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-6">--}}
{{--                <div class="block block-rounded block-link-pop">--}}
{{--                    <div class="block-content block-content-full">--}}
{{--                        <canvas id="canvas-graph-three" data-labels="{{json_encode($graph_three_labels)}}" data-values="{{json_encode($graph_three_values)}}"></canvas>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-md-6">--}}
{{--                <div class="block block-rounded block-link-pop">--}}
{{--                    <div class="block-content block-content-full">--}}
{{--                        <canvas id="canvas-graph-four" data-labels="{{json_encode($graph_four_labels)}}" data-values="{{json_encode($graph_four_values)}}"></canvas>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="row">
            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Store Sales</h3>
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
                                            <a href="{{route('stores.view',$store->id)}}"> {{explode('.',$store->shopify_domain)[0]}} </a>

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
                            <p  class="text-center"> No Top Stores Found </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Non Shopify Users Sales</h3>
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

                                {{--                                @dd($top_users)--}}
                                @foreach($top_users as $user)
                                    <tr>
                                        <td class="font-w600">
                                            <a href="{{route('users.view',$user->id)}}"> {{$user->name}} {{$user->last_name}}</a>
                                        </td>
                                        <td class="font-w600">
                                            <a href="{{route('users.view',$user->id)}}">  {{$user->email}} </a>
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
            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Active Stores</h3>
                    </div>
                    <div class="block-content ">
                        @if(count($active_stores) > 0)
                            <table class="table table-striped table-hover table-borderless table-vcenter">
                                <thead>
                                <tr class="text-uppercase">
                                    <th class="font-w700">Store</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($active_stores as $store)
                                    <tr>
                                        <td class="font-w600">
                                            <a href="{{route('stores.view',$store->id)}}"> {{explode('.',$store->shopify_domain)[0]}} </a>
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

{{--            <div class="col-md-6">--}}
{{--                <div class="block block-rounded">--}}
{{--                    <div class="block-header block-header-default">--}}
{{--                        <h3 class="block-title">New Stores</h3>--}}
{{--                    </div>--}}
{{--                    <div class="block-content ">--}}
{{--                        @if(count($new_stores) > 0)--}}
{{--                            <table class="table table-striped table-hover table-borderless table-vcenter">--}}
{{--                                <thead>--}}
{{--                                <tr class="text-uppercase">--}}
{{--                                    <th class="font-w700">Store</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                @foreach($new_stores as $store)--}}
{{--                                    <tr>--}}
{{--                                        <td class="font-w600">--}}
{{--                                            <a href="{{route('stores.view',$store->id)}}"> {{explode('.',$store->shopify_domain)[0]}} </a>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        @else--}}
{{--                            <p  class="text-center"> No Top Users Found </p>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>

    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        if($('body').find('#reportrange').length > 0){
            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
            if($('#reportrange span').text() === ''){
                $('#reportrange span').html('Select Date Range');
            }


            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            // cb(start, end);
        }

        $('body').on('click','.filter_by_date', function() {
            let daterange_string = $('#reportrange').find('span').text();
            if(daterange_string !== '' && daterange_string !== 'Select Date Range'){
                window.location.href = $(this).data('url')+'?date-range='+daterange_string;
            }
            else{
                alertify.error('Please Select Range');
            }
        });
    </script>

@endsection
