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
            <div class="d-flex justify-content-between flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2 pr-3">
                    Dashboard
                </h1>
                @include('inc.latest_news')
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
                <div class="row block p-4">
                    <h3 class="text-danger">Wallet: {{number_format($balance,2)}} USD</h3>
                </div>
                <div class="row block">
                    <div class="col-md-12">
                        <div class="block-title  p-3">My Orders</div>
                    </div>
                    <div class="col-md-4">
                        <a class="block block-rounded block-link-pop"  href="javascript:void(0)">
                            <div class="block-content block-content-full border border-primary bg-gray-lighter">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Total Orders</div>
                                <div class="font-size-h2 font-w400 text-dark">{{$orders}}</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="block block-rounded block-link-pop"  href="javascript:void(0)">
                            <div class="block-content block-content-full border border-primary bg-gray-lighter">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Sales</div>
                                <div class="font-size-h2 font-w400 text-dark">${{number_format($sales,2)}}</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="block block-rounded block-link-pop"  href="javascript:void(0)">
                            <div class="block-content block-content-full border border-primary bg-gray-lighter">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Profit</div>
                                <div class="font-size-h2 font-w400 text-success">${{number_format($profit,2)}}</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="block block-rounded block-link-pop" href="{{ route('users.custom.orders', ['unpaid'=>1]) }}" >
                            <div class="block-content block-content-full border border-primary bg-gray-lighter">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Unpaid</div>
                                <div class="font-size-h2 font-w400 text-danger">{{$unpaid_orders_count}}</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="block block-rounded block-link-pop" href="{{ route('users.custom.orders', ['unfulfilled'=>1]) }}" >
                            <div class="block-content block-content-full border border-primary bg-gray-lighter">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Unfulfilled</div>
                                <div class="font-size-h2 font-w400 text-dark">{{$unfullfilled_orders_count}}</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="block block-rounded block-link-pop" href="{{ route('users.custom.orders', ['cancel'=>1]) }}" >
                            <div class="block-content block-content-full border border-primary bg-gray-lighter">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">Cancel/Refund</div>
                                <div class="font-size-h2 font-w400 text-dark">{{$canceled_order_count}}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Wishlist -->
{{--                <div class="row block">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="block-title  p-3">My Wishlist</div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <a class="block block-rounded block-link-pop" href="/users/wishlist?status=1" >--}}
{{--                            <div class="block-content block-content-full border border-primary bg-gray-lighter">--}}
{{--                                <div class="font-size-sm font-w600 text-uppercase text-muted">Outstanding</div>--}}
{{--                                <div class="font-size-h2 font-w400 text-dark">{{ $open_wishlist }}</div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <a class="block block-rounded block-link-pop" href="/users/wishlist?status=2" >--}}
{{--                            <div class="block-content block-content-full border border-primary bg-gray-lighter">--}}
{{--                                <div class="font-size-sm font-w600 text-uppercase text-muted">Approved</div>--}}
{{--                                <div class="font-size-h2 font-w400 text-success">{{ $approved_wishlist }}</div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <a class="block block-rounded block-link-pop" href="/users/wishlist?status=4&read=1" >--}}
{{--                            <div class="block-content block-content-full border border-primary bg-gray-lighter">--}}
{{--                                <div class="font-size-sm font-w600 text-uppercase text-muted">Rejected</div>--}}
{{--                                <div class="font-size-h2 font-w400 text-dark">{{$unread_rejected_wishlist}}</div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <a class="block block-rounded block-link-pop" href="/users/wishlist?status=5&read=2" >--}}
{{--                            <div class="block-content block-content-full border border-primary bg-gray-lighter">--}}
{{--                                <div class="font-size-sm font-w600 text-uppercase text-muted">Completed</div>--}}
{{--                                <div class="font-size-h2 font-w400 text-success">{{$unread_completed_wishlist}}</div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="row">
                    <div class="col-md-12">
                        <div class="block block-rounded block-link-pop">
                            <div class="block-content block-content-full">
                                <div class="d-flex justify-content-between">
                                    <div class="block-title p-3">Your Growth</div>
                                    <div class="custom-control custom-switch custom-control-success mb-1 my-auto">
                                        <input type="checkbox" class="custom-control-input status-switch" id="graph_checkbox" name="example-sw-success2">
                                        <label class="custom-control-label" for="graph_checkbox">Orders / Sales</label>
                                    </div>
                                </div>
                                <canvas id="canvas-graph-one-users" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_one_values)}}"></canvas>
                                <canvas id="canvas-graph-two-users" style="display: none;" data-labels="{{json_encode($graph_one_labels)}}" data-values="{{json_encode($graph_two_values)}}"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="row">--}}
{{--                    <div class="col-md-6">--}}
{{--                        <div class="block block-rounded block-link-pop">--}}
{{--                            <div class="block-content block-content-full">--}}
{{--                                <canvas id="canvas-graph-three-users" data-labels="{{json_encode($graph_three_labels)}}" data-values="{{json_encode($graph_three_values)}}"></canvas>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <div class="block block-rounded block-link-pop">--}}
{{--                            <div class="block-content block-content-full">--}}
{{--                                <canvas id="canvas-graph-four-users" data-labels="{{json_encode($graph_four_labels)}}" data-values="{{json_encode($graph_four_values)}}"></canvas>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
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
                        <div class="block-title ">
                            <i class="fa fa-user text-primary"></i>
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
                                <img class="img-avatar200 img-avatar-rounded w-25" @if($manager->profile == null) src="{{ asset('assets/media/avatars/avatar10.jpg') }}" @else  src="{{asset('managers-profiles')}}/{{$manager->profile}}" @endif alt="Header Avatar" style="width: 100px !important; height: 100px !important;">
                                <div class="font-w600">{{$manager->name}} {{$manager->last_name}}</div>
                                <div class="font-w600">{{$manager->email}}</div>
                                <div class="text-info">
                                    <i class="fa fa-phone"></i>
                                    <span class="font-w600">{{$manager->phone}}</span>
                                </div>
{{--                                <div class="text-info">--}}
{{--                                    <i class="fab fa-skype text-info fa-lg"></i>--}}
{{--                                    <a href="skype:{{$manager->skype}}?chat">Skype: {{ $manager->skype }}</a>--}}
{{--                                </div>--}}

                            </div>
                        </div>
                    @endif
                </div>
                <div class="block">
                    <div class="block-header">
                        <div class="block-title ">
                            <i class="fa fa-wallet text-success text-primary"></i>
                            Your Wallet
                        </div>
                    </div>
                    <div class="block-content pb-4 text-center" >
                        <div class="font-w600">Your Balance</div>
                        <div class="font-size-h3 font-w700 mt-2 text-danger">{{number_format($balance,2)}} USD</div>
                        <button data-target="#bank_transfer_modal" data-toggle="modal" class="btn btn-outline-success btn-block d-block mt-4 p-2">Top up</button>

                        <div class="modal fade" id="bank_transfer_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-popout" role="document">
                                <div class="modal-content text-left">
                                    <div class="block block-themed block-transparent mb-0">
                                        <div class="block-header bg-primary-dark">
                                            <h3 class="block-title">TOPUP through Bank Transfer</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <form action="{{route('store.user.wallet.request.topup')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" value="{{ \Illuminate\Support\Facades\Auth::user()->id}}" name="user_id">
                                            <input type="hidden" value="{{ \Illuminate\Support\Facades\Auth::user()->has_wallet->id}}" name="wallet_id">
                                            <input type="hidden" name="type" value="bank transfer">
                                            <div class="block-content font-size-sm">

                                                <div class="info-box">
                                                    <p style="padding: 10px">
                                                        BENEFICIAL NAME: Fantasy Supply Limited <i class="fa fa-question-circle" title="Fantasy Supply Limited is the mother company of YourWholesaleSource"></i><br>
                                                        BANK NAME: Oversea-Chinese Banking Corporation Limited Singapore<br>
                                                        SWFIT CODE:OCBCSGSG<br>
                                                        Bank Account: 501246136301<br>
                                                        Bank Address: OCBC Bank,65 Chulia Street, OCBC Centre, Singapore 049513<br>
                                                        Intermeidary Bank: JP Morgan Chase Bank, New York, USA<br>
                                                        SWIFIT CODE:CHASUS33<br>

                                                    </p>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="form-material">
                                                            <label for="material-error">Cheque Number <i class="fa fa-question-circle" title="Cheque number of the deposit (optional)"></i></label>
                                                            <input  class="form-control" type="text"  name="cheque"
                                                                    value=""  placeholder="Enter Cheque Number here">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="form-material">
                                                            <label for="material-error">Company/Sender Title <i class="fa fa-question-circle" title="Name of Company or Sender who made this deposit"></i></label>
                                                            <input  class="form-control" type="text"  name="cheque_title"
                                                                    value="" required  placeholder="Enter Company/Sender Title here">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="form-material">
                                                            <label for="material-error">Bank Name <i class="fa fa-question-circle" title="Name of the bank where you deposit amount"></i></label>
                                                            <input required class="form-control" type="text"  name="bank_name"
                                                                   value=""  placeholder="Enter Bank Title here">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="form-material">
                                                            <label for="material-error">Amount (USD) <i class="fa fa-question-circle" title="Deposit amount in USD"></i></label>
                                                            <input required class="form-control" type="number"  name="amount"
                                                                   value=""  placeholder="Enter Cheque Amount here">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="form-material">
                                                            <label for="material-error">Bank Proof Copy <i class="fa fa-question-circle" title="Proof of bank receipt of deposit"></i></label>
                                                            <input required class="form-control" type="file"  name="attachment" placeholder="Provide Bank Proof Copy ">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="form-material">
                                                            <label for="material-error">Notes <i class="fa fa-question-circle" title="Optional notes for this deposit"></i></label>
                                                            <input  class="form-control" type="text"  name="notes"
                                                                    value=""   placeholder="Enter Notes here">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="block-content block-content-full text-right border-top">
                                                <button type="submit" class="btn btn-sm btn-primary" >Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
{{--                <div class="block">--}}
{{--                    <div class="block-header">--}}
{{--                        <div class="block-title ">--}}
{{--                            <i class="fa fa-pencil-alt text-danger"></i>--}}
{{--                            Help us improve!--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="block-content pb-4" >--}}
{{--                        <div class="font-size-sm   text-muted">Help us improve our application by providing your thoughts and opinions!</div>--}}
{{--                        <button data-target="#feedback" data-toggle="modal" class="btn btn-outline-danger btn-block d-block mt-4 p-2">Feedback</button>--}}

{{--                    </div>--}}
{{--                </div>--}}
                <div class="modal fade" id="feedback" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                        <div class="modal-content text-left">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">We Would Like To Have Your Valueable Suggesstions</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                        </button>
                                    </div>
                                </div>
                                <form action="{{ route('suggestion.create') }}" method="post"  enctype="multipart/form-data">
                                    @csrf
                                    <div class="block-content font-size-sm">
                                        <div class="form-group">
                                            <div class="col-sm-12 px-0">
                                                <div class="form-material">
                                                    <div class="form-group">
                                                        <label for="material-error">Feedbacks</label>
                                                        <textarea class="form-control" name="suggestion"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="material-error">Attach a File <span class="text-muted">(image,video,pdf)</span></label>
                                                        <input type="file" name="file" class="form-control" accept="video/*,image/*,.pdf">
                                                    </div>
                                                    <input type="hidden" name="user_email" value="{{ \Illuminate\Support\Facades\Auth::user()->email }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block-content block-content-full text-right border-top">

                                        <button type="submit" class="btn btn-sm btn-primary" >Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

{{--                <div class="block">--}}
{{--                    <div class="block-header">--}}
{{--                        <div class="block-title ">--}}
{{--                            <i class="fa fa-newspaper text-city"></i>--}}
{{--                            Announcements--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="block-content pb-4 text-left" >--}}
{{--                        @forelse(\App\News::latest()->limit(5)->get() as $news)--}}
{{--                            <i class="fa fa-volume-up text-city" ></i>--}}
{{--                            <span class="font-size-sm  font-w600 " data-toggle="modal" data-target="#news_modal_{{$news->id}}">{{ \Illuminate\Support\Str::limit($news->title, $limit = 20, $end = '.. (view details)') }} </span>--}}
{{--                            <div class="modal fade" id="news_modal_{{$news->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">--}}
{{--                                <div class="modal-dialog modal-dialog-popout" role="document">--}}
{{--                                    <div class="modal-content text-left">--}}
{{--                                        <div class="block block-themed block-transparent mb-0">--}}
{{--                                            <div class="block-header bg-primary-dark">--}}
{{--                                                <h3 class="block-title">Announcement</h3>--}}
{{--                                                <div class="block-options">--}}
{{--                                                    <button type="button" class="btn-block-option">--}}
{{--                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="block-content font-size-sm">--}}
{{--                                                <h5>{{ $news->title }}</h5>--}}
{{--                                                <p>{{ $news->description }}</p>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <hr>--}}
{{--                        @empty--}}
{{--                            <strong >--}}
{{--                                No Announcements--}}
{{--                            </strong>--}}
{{--                        @endforelse--}}
{{--                    </div>--}}
{{--                </div>--}}
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
