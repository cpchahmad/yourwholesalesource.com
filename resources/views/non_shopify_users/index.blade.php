@extends('layout.shopify')
@section('content')

    <style>
        .daterangepicker .right{
            color: inherit !important;
        }
        .daterangepicker {
            width: 668px !important;
        }

    </style>
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
        <div class="row mb-2" style="padding-bottom:1.875rem">
            <div class="col-md-4 d-flex">
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span>{{$date_range}}</span> <i class="fa fa-caret-down"></i>
                </div>
                <button class="btn btn-primary filter_by_date" data-url="{{route('users.dashboard')}}" style="margin-left: 10px"> Filter </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                        <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                            <div class="block-content block-content-full">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Total Orders</div>
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
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Profit</div>
                                <div class="font-size-h2 font-w400 text-dark">${{number_format($profit,2)}}</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="block block-rounded block-link-pop">
                            <div class="block-content block-content-full">
                                <canvas id="canvas-graph-one-users" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_one_values)}}"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="block block-rounded block-link-pop">
                            <div class="block-content block-content-full">
                                <canvas id="canvas-graph-two-users" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_two_values)}}"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="block block-rounded block-link-pop">
                            <div class="block-content block-content-full">
                                <canvas id="canvas-graph-three-users" data-labels="{{json_encode($graph_three_labels)}}" data-values="{{json_encode($graph_three_values)}}"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="block block-rounded block-link-pop">
                            <div class="block-content block-content-full">
                                <canvas id="canvas-graph-four-users" data-labels="{{json_encode($graph_four_labels)}}" data-values="{{json_encode($graph_four_values)}}"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
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
                                                                <img class="img-avatar img-avatar32" style="margin-right: 5px" data-src="{{asset('images')}}/{{$image->image}}" alt="">
                                                            @else
                                                                <img class="img-avatar img-avatar32" style="margin-right: 5px" data-src="{{asset('images/variants')}}/{{$image->image}}" alt="">
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
                </div>
            </div>
            <div class="col-md-3">
                <div class="block">
                    <div class="block-header">
                        <div class="block-title">
                            Your Account Manager
                        </div>
                    </div>

                    @php
                        if(auth()->user()->has_manager != null){
                            $manager = auth()->user()->has_manager;
                        }
                        else{
                            $manager = null;
                        }
                    @endphp
                    @if($manager)
                        <div class="block-content" >
                            <div class="media-body pb-3 text-center">
                                <img class="img-avatar-rounded" @if($manager->profile == null) src="{{ asset('assets/media/avatars/avatar10.jpg') }}" @else  src="{{asset('managers-profiles')}}/{{$manager->profile}}" @endif alt="Header Avatar" style="width: 18px;">
                                <div class="font-w600">{{$manager->name}} {{$manager->last_name}}</div>
                                <div class="font-w600">{{$manager->email}}</div>
                                <div class="text-info">
                                    <i class="fab fa-whatsapp text-success fa-lg"></i>
                                    <a target="_blank" href="https://api.whatsapp.com/send?phone={{$manager->whatsapp}}">Whatsapp {{$manager->whatsapp}}</a>
                                </div>
                                <div class="text-info">
                                    <i class="fab fa-skype text-info fa-lg"></i>
                                    <a href="skype:{{$manager->skype}}?chat">{{ $manager->skype }}</a>
                                </div>

                            </div>
                        </div>
                    @endif
                </div>
                <div class="block">
                    <div class="block-header">
                        <div class="block-title">
                            Your Wallet Balance
                        </div>
                    </div>
                    <div class="block-content pb-4 text-center" >
                        <div class="font-w600">Wallet Balance</div>
                        <div class="mt-2 p-2 bg-primary text-white">{{number_format($balance,2)}} USD</div>
                    </div>
                </div>
                <div class="block">
                    <div class="block-header">
                        <div class="block-title">
                            Help us improve!
                        </div>
                    </div>
                    <input type="hidden" name="type[]" value="fulfilled">
                    <div class="block-content pb-4 text-right" >
                        <form method="POST" action="{{ route('suggestion.create') }}" class="mt-2">
                            @csrf
                            <textarea class="form-control" name="suggestion"></textarea>
                            <input type="hidden" name="user_email" value="{{ auth()->user()->email }}">
                            <button class="btn btn-sm btn-success mt-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
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
