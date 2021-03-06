@extends('layout.single')
@section('content')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    @php
        $admin_settings = \App\AdminSetting::first();
    @endphp
    {!! $admin_settings->paypal_script_tag !!}
{{--    <script src="https://www.paypal.com/sdk/js?client-id=AV6qhCigre8RgTt8E6Z0KNesHxr1aDyJ2hmsk2ssQYmlaVxMHm2JFJvqDCsU15FhoCJY0mDzOu-jbFPY&currency=USD"></script>--}}
    <script>

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: parseFloat($('#bank_transfer_modal').find('.amount-to-be-added').val())
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    $('.ajax_paypal_wallet_form_submit').find('textarea').val(JSON.stringify(details));
                    $('.ajax_paypal_wallet_form_submit').submit();
                });
            }
        }).render('#paypal-button-container');
    </script>

    <div class="modal" id="paypal_pay_trigger" tabindex="-1" role="dialog" aria-labelledby="modal-block-vcenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-content cst_content_wrapper font-size-sm text-center">
                        <h2>Are your sure?</h2>
                        <div class="text-center">
                            <p>
                                An Amount: <span class="amount-to-be-paid"></span> USD is going to processed during this session
                            </p>

                        </div>
                        <div class="paypal_btn_trigger">
                            <div id="paypal-button-container"></div>
                        </div>

                    </div>
                    <div class="block-content block-content-full text-center border-top">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Wallet
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Wallet</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @if(!isset($wallet) && !isset($user))
        <div class="block ">
            <div class="block-content ">
                <p class="text-center"> No Account Associated With This Store Found ! <a href="{{route('store.index')}}"> Click Here For Account Association :) </a></p>
            </div>
        </div>
    @else
        @if($wallet != null)
            <div class="content">
                <div class="content-grid">
                    <div class="row mb2">
                        <div class="col-md-3">
                            <div class="block ">
                                <div class="block-header">
                                    <h3 class="block-title ">Available</h3>
                                </div>
                                <div class="block-content ">
                                    <p class="font-size-h2"> {{number_format($wallet->available,2)}} USD</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block ">
                                <div class="block-header">
                                    <h3 class="block-title">Pending</h3>
                                </div>
                                <div class="block-content ">
                                    <p class=" font-size-h2"> {{number_format($wallet->pending,2)}} USD</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block ">
                                <div class="block-header">
                                    <h3 class="block-title">Transferred</h3>
                                </div>
                                <div class="block-content ">
                                    <p class="font-size-h2"> {{number_format($wallet->transferred,2)}} USD</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block ">
                                <div class="block-header">
                                    <h3 class="block-title">Used</h3>
                                </div>
                                <div class="block-content ">
                                    <p class=" font-size-h2"> {{number_format($wallet->used,2)}} USD</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="block">
                                <div class="block-header">
                                    <h3 class="block-title ">Wallet ID
                                        <span style="float: right" ><i class="fa fa-info-circle" title="This ID used for wallet-to-wallet transferred"></i> {{$wallet->wallet_token}}</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{--                        <div class="col-md-6">--}}
                        {{--                            <div class="block pay-options" data-toggle="modal" data-target="#paypal_topup_modal">--}}
                        {{--                               <div class="block-content">--}}
                        {{--                                   <p class="text-center"> Top-up with Paypal </p>--}}
                        {{--                               </div>--}}
                        {{--                            </div>--}}
                        {{--                            <div class="modal fade" id="paypal_topup_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">--}}
                        {{--                                <div class="modal-dialog modal-dialog-popout" role="document">--}}
                        {{--                                    <div class="modal-content">--}}
                        {{--                                        <div class="block block-themed block-transparent mb-0">--}}
                        {{--                                            <div class="block-header bg-primary-dark">--}}
                        {{--                                                <h3 class="block-title">TOPUP VIA PAYPAL</h3>--}}
                        {{--                                                <div class="block-options">--}}
                        {{--                                                    <button type="button" class="btn-block-option">--}}
                        {{--                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>--}}
                        {{--                                                    </button>--}}
                        {{--                                                </div>--}}
                        {{--                                            </div>--}}

                        {{--                                            <form action="{{route('store.wallet.paypal.topup',$wallet->id)}}" method="post">--}}
                        {{--                                                @csrf--}}
                        {{--                                                <input type="hidden" value="{{$user->id}}" name="user_id">--}}
                        {{--                                                <input type="hidden" value="{{$wallet->id}}" name="wallet_id">--}}
                        {{--                                                <div class="block-content font-size-sm">--}}
                        {{--                                                    <div class="form-group">--}}
                        {{--                                                        <div class="col-sm-12">--}}
                        {{--                                                            <div class="form-material">--}}
                        {{--                                                                <label for="material-error">Amount</label>--}}
                        {{--                                                                <input required class="form-control" type="number"  name="amount"--}}
                        {{--                                                                       value=""  placeholder="Enter Top-up Amount here">--}}
                        {{--                                                            </div>--}}
                        {{--                                                        </div>--}}
                        {{--                                                    </div>--}}
                        {{--                                                </div>--}}

                        {{--                                                <div class="block-content block-content-full text-right border-top">--}}
                        {{--                                                    <button type="submit" class="btn btn-sm btn-primary">Request Top-up</button>--}}
                        {{--                                                </div>--}}
                        {{--                                            </form>--}}
                        {{--                                        </div>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}

                        {{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="block pay-options" data-toggle="modal" data-target="#alibaba_topup_modal">--}}
{{--                                <div class="block-content">--}}
{{--                                    <p class="text-center"> Top-up with AliBaba Order </p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="modal fade" id="alibaba_topup_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">--}}
{{--                                <div class="modal-dialog modal-dialog-popout" role="document">--}}
{{--                                    <div class="modal-content">--}}
{{--                                        <div class="block block-themed block-transparent mb-0">--}}
{{--                                            <div class="block-header bg-primary-dark">--}}
{{--                                                <h3 class="block-title">TOPUP VIA Alibaba</h3>--}}
{{--                                                <div class="block-options">--}}
{{--                                                    <button type="button" class="btn-block-option">--}}
{{--                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <form action="{{route('store.user.wallet.request.topup',$wallet->id)}}" method="post">--}}
{{--                                                @csrf--}}
{{--                                                <input type="hidden" value="{{$user->id}}" name="user_id">--}}
{{--                                                <input type="hidden" value="{{$wallet->id}}" name="wallet_id">--}}
{{--                                                <input type="hidden" value="alibaba" name="type">--}}
{{--                                                <input type="hidden" value="alibaba" name="bank_name">--}}
{{--                                                <div class="block-content font-size-sm">--}}
{{--                                                    <div class="text-center" style="margin-bottom: 20px">--}}
{{--                                                        <a target="_blank" href="https://www.alibaba.com/product-detail/Drop-shipping-service-with-fast-delivery_62322670218.html?spm=a2747.manage.0.0.6d6d71d2pQDQTq">--}}
{{--                                                            <img style="width: 100%; max-width: 200px" src="{{asset('assets/alibaba_trademark.png')}}" alt="">--}}
{{--                                                        </a>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Alibaba Order Number <i class="fa fa-question-circle" title="Order Number of Alibaba"></i></label>--}}
{{--                                                                <input  class="form-control" type="text"  name="cheque"--}}
{{--                                                                        value="" required  placeholder="Enter Order Number here">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Company/Sender Title <i class="fa fa-question-circle" title="Name of company or sender who place the order"></i></label>--}}
{{--                                                                <input  class="form-control" type="text"  name="cheque_title"--}}
{{--                                                                        value="" required  placeholder="Enter Company/Sender Title here">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Amount <i class="fa fa-question-circle" title="Amount of Order"></i></label>--}}
{{--                                                                <input required class="form-control" type="number"  name="amount"--}}
{{--                                                                       value=""  placeholder="Enter Top-up Amount here">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Alibaba Proof Copy <i class="fa fa-question-circle" title="Proof of alibaba receipt of your order (optional)"></i></label>--}}
{{--                                                                <input  class="form-control" type="file"  name="attachment" placeholder="Provide Bank Proof Copy ">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Notes <i class="fa fa-question-circle" title="Optional notes according to this order"></i></label>--}}
{{--                                                                <input  class="form-control" type="text"  name="notes"--}}
{{--                                                                        value=""   placeholder="Enter Notes here">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                <div class="block-content block-content-full text-right border-top">--}}
{{--                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>--}}
{{--                                                </div>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <div class="col-md-6 ">--}}
{{--                            <div class="block pay-options"  data-toggle="modal" data-target="#bank_transfer_modal">--}}
{{--                                <div class="block-content">--}}
{{--                                    <p class="text-center" > Top-up with Bank Transfer </p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="modal fade" id="bank_transfer_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">--}}
{{--                                <div class="modal-dialog modal-dialog-popout" role="document">--}}
{{--                                    <div class="modal-content">--}}
{{--                                        <div class="block block-themed block-transparent mb-0">--}}
{{--                                            <div class="block-header bg-primary-dark">--}}
{{--                                                <h3 class="block-title">TOPUP through Bank Transfer</h3>--}}
{{--                                                <div class="block-options">--}}
{{--                                                    <button type="button" class="btn-block-option">--}}
{{--                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>--}}
{{--                                                    </button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <form action="{{route('store.user.wallet.request.topup')}}" method="post" enctype="multipart/form-data">--}}
{{--                                                @csrf--}}
{{--                                                <input type="hidden" value="{{$user->id}}" name="user_id">--}}
{{--                                                <input type="hidden" value="{{$wallet->id}}" name="wallet_id">--}}
{{--                                                <input type="hidden" name="type" value="bank transfer">--}}
{{--                                                <div class="block-content font-size-sm">--}}

{{--                                                    <div class="info-box">--}}
{{--                                                        <p style="padding: 10px">--}}
{{--                                                            BENEFICIAL NAME: Your Wholesale Source <i class="fa fa-question-circle" title="Your Wholesale Source is the mother company of YourWholesaleSource"></i><br>--}}
{{--                                                            BANK NAME: xxxxxxxxxxxxxxxxxxxxxxxx<br>--}}
{{--                                                            SWFIT CODE:xxxxxxxxxxxxxx<br>--}}
{{--                                                            Bank Account: xxxxxxxxxxxxxxx<br>--}}
{{--                                                            Bank Address: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br>--}}
{{--                                                            Intermeidary Bank: xxxxxxxxxxxxxxxxxxxxx<br>--}}
{{--                                                            SWIFIT CODE:xxxxxxxxxx<br>--}}

{{--                                                        </p>--}}
{{--                                                    </div>--}}

{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Cheque Number <i class="fa fa-question-circle" title="Cheque number of the deposit (optional)"></i></label>--}}
{{--                                                                <input  class="form-control" type="text"  name="cheque"--}}
{{--                                                                        value=""  placeholder="Enter Cheque Number here">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Company/Sender Title <i class="fa fa-question-circle" title="Name of Company or Sender who made this deposit"></i></label>--}}
{{--                                                                <input  class="form-control" type="text"  name="cheque_title"--}}
{{--                                                                        value="" required  placeholder="Enter Company/Sender Title here">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Bank Name <i class="fa fa-question-circle" title="Name of the bank where you deposit amount"></i></label>--}}
{{--                                                                <input required class="form-control" type="text"  name="bank_name"--}}
{{--                                                                       value=""  placeholder="Enter Bank Title here">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Amount (USD) <i class="fa fa-question-circle" title="Deposit amount in USD"></i></label>--}}
{{--                                                                <input required class="form-control" type="number"  name="amount"--}}
{{--                                                                       value=""  placeholder="Enter Cheque Amount here">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Bank Proof Copy <i class="fa fa-question-circle" title="Proof of bank receipt of deposit"></i></label>--}}
{{--                                                                <input required class="form-control" type="file"  name="attachment" placeholder="Provide Bank Proof Copy ">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="form-group">--}}
{{--                                                        <div class="col-sm-12">--}}
{{--                                                            <div class="form-material">--}}
{{--                                                                <label for="material-error">Notes <i class="fa fa-question-circle" title="Optional notes for this deposit"></i></label>--}}
{{--                                                                <input  class="form-control" type="text"  name="notes"--}}
{{--                                                                        value=""   placeholder="Enter Notes here">--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}

{{--                                                <div class="block-content block-content-full text-right border-top">--}}
{{--                                                    <button type="submit" class="btn btn-sm btn-primary" >Save</button>--}}
{{--                                                </div>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}


                            <div class="col-md-6">
                                <div class="block pay-options" data-toggle="modal" data-target="#alibaba_topup_modal">
                                    <div class="block-content">
                                        <p class="text-center"> Top-up with Credit Card </p>
                                    </div>
                                </div>
                                <div class="modal fade" id="alibaba_topup_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-popout" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">TOPUP VIA Credit Card</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option">
                                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="block-content">
                                                    <form
                                                        role="form"
                                                        action="{{ route('store.user.wallet.request.topup',$wallet->id) }}"
                                                        method="post"
                                                        class="require-validation"
                                                        data-cc-on-file="false"
                                                        data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                                                        id="payment-form">
                                                        @csrf
                                                        <input type="hidden" value="{{$user->id}}" name="user_id">
                                                        <input type="hidden" value="{{$wallet->id}}" name="wallet_id">
                                                        <input type="hidden" value="stripe" name="type">
                                                        <input type="hidden" value="stripe" name="bank_name">

                                                        <div>
                                                            <div class='form-group required'>
                                                                <label class='control-label'>Name on Card</label>
                                                                <input
                                                                    class='form-control' size='4' type='text'>
                                                            </div>
                                                        </div>
                                                        <div class=''>
                                                            <div class='form-group card required'>
                                                                <label class='control-label'>Card Number</label> <input
                                                                    autocomplete='off' class='form-control card-number' size='20'
                                                                    type='text'>
                                                            </div>
                                                        </div>
                                                        <div class='row'>
                                                            <div class='col-4 form-group cvc required'>
                                                                <label class='control-label'>CVC</label> <input autocomplete='off'
                                                                                                                class='form-control card-cvc' placeholder='ex. 311' size='4'
                                                                                                                type='text'>
                                                            </div>
                                                            <div class='col-4 form-group expiration required'>
                                                                <label class='control-label'>Expiration Month</label> <input
                                                                    class='form-control card-expiry-month' placeholder='MM' size='2'
                                                                    type='text'>
                                                            </div>
                                                            <div class='col-4 form-group expiration required'>
                                                                <label class='control-label'>Expiration Year</label> <input
                                                                    class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                                                    type='text'>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12 px-0">
                                                                <div class="form-material">
                                                                    <label for="material-error">Amount <i class="fa fa-question-circle" title="Amount of Order"></i></label>
                                                                    <input required class="form-control" type="number"  name="amount"
                                                                           value=""  placeholder="Enter Top-up Amount here">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12 px-0">
                                                                <div class="form-material">
                                                                    <label for="material-error">Company/Sender Title <i class="fa fa-question-circle" title="Name of company or sender who place the order"></i></label>
                                                                    <input  class="form-control" type="text"  name="cheque_title"
                                                                            value="" required  placeholder="Enter Company/Sender Title here">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-12 px-0">
                                                                <div class="form-material">
                                                                    <label for="material-error">Notes <i class="fa fa-question-circle" title="Optional notes according to this order"></i></label>
                                                                    <input  class="form-control" type="text"  name="notes"
                                                                            value=""   placeholder="Enter Notes here">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <div class='error form-group' style="display: none;">
                                                                <div class='alert-danger alert'>Please correct the errors and try
                                                                    again.
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-right mb-2">
                                                            <div class="">
                                                                <img class="w-100" src="https://en.paylei.md/wp-content/uploads/cards-logo-pci.jpg">
                                                                <button class="btn btn-primary pay-btn" type="submit">Save</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                {{--                                            <form action="{{route('store.user.wallet.request.topup',$wallet->id)}}" method="post">--}}
                                                {{--                                                @csrf--}}
                                                {{--                                                <input type="hidden" value="{{$user->id}}" name="user_id">--}}
                                                {{--                                                <input type="hidden" value="{{$wallet->id}}" name="wallet_id">--}}
                                                {{--                                                <input type="hidden" value="stripe" name="type">--}}
                                                {{--                                                <input type="hidden" value="stripe" name="bank_name">--}}
                                                {{--                                                <div class="block-content font-size-sm">--}}
                                                {{--                                                    <div class="text-center" style="margin-bottom: 20px">--}}
                                                {{--                                                        <a target="_blank" href="https://www.alibaba.com/product-detail/Drop-shipping-service-with-fast-delivery_62322670218.html?spm=a2747.manage.0.0.6d6d71d2pQDQTq">--}}
                                                {{--                                                            <img style="width: 100%; max-width: 200px" src="{{asset('assets/alibaba_trademark.png')}}" alt="">--}}
                                                {{--                                                        </a>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                    <div class="form-group">--}}
                                                {{--                                                        <div class="col-sm-12">--}}
                                                {{--                                                            <div class="form-material">--}}
                                                {{--                                                                <label for="material-error">Alibaba Order Number <i class="fa fa-question-circle" title="Order Number of Alibaba"></i></label>--}}
                                                {{--                                                                <input  class="form-control" type="text"  name="cheque"--}}
                                                {{--                                                                        value="" required  placeholder="Enter Order Number here">--}}
                                                {{--                                                            </div>--}}
                                                {{--                                                        </div>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                    <div class="form-group">--}}
                                                {{--                                                        <div class="col-sm-12">--}}
                                                {{--                                                            <div class="form-material">--}}
                                                {{--                                                                <label for="material-error">Company/Sender Title <i class="fa fa-question-circle" title="Name of company or sender who place the order"></i></label>--}}
                                                {{--                                                                <input  class="form-control" type="text"  name="cheque_title"--}}
                                                {{--                                                                        value="" required  placeholder="Enter Company/Sender Title here">--}}
                                                {{--                                                            </div>--}}
                                                {{--                                                        </div>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                    <div class="form-group">--}}
                                                {{--                                                        <div class="col-sm-12">--}}
                                                {{--                                                            <div class="form-material">--}}
                                                {{--                                                                <label for="material-error">Amount <i class="fa fa-question-circle" title="Amount of Order"></i></label>--}}
                                                {{--                                                                <input required class="form-control" type="number"  name="amount"--}}
                                                {{--                                                                       value=""  placeholder="Enter Top-up Amount here">--}}
                                                {{--                                                            </div>--}}
                                                {{--                                                        </div>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                    <div class="form-group">--}}
                                                {{--                                                        <div class="col-sm-12">--}}
                                                {{--                                                            <div class="form-material">--}}
                                                {{--                                                                <label for="material-error">Alibaba Proof Copy <i class="fa fa-question-circle" title="Proof of alibaba receipt of your order (optional)"></i></label>--}}
                                                {{--                                                                <input  class="form-control" type="file"  name="attachment" placeholder="Provide Bank Proof Copy ">--}}
                                                {{--                                                            </div>--}}
                                                {{--                                                        </div>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                    <div class="form-group">--}}
                                                {{--                                                        <div class="col-sm-12">--}}
                                                {{--                                                            <div class="form-material">--}}
                                                {{--                                                                <label for="material-error">Notes <i class="fa fa-question-circle" title="Optional notes according to this order"></i></label>--}}
                                                {{--                                                                <input  class="form-control" type="text"  name="notes"--}}
                                                {{--                                                                        value=""   placeholder="Enter Notes here">--}}
                                                {{--                                                            </div>--}}
                                                {{--                                                        </div>--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                </div>--}}

                                                {{--                                                <div class="block-content block-content-full text-right border-top">--}}
                                                {{--                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>--}}
                                                {{--                                                </div>--}}
                                                {{--                                            </form>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6 ">
                                <div class="block pay-options"  data-toggle="modal" data-target="#bank_transfer_modal">
                                    <div class="block-content">
                                        <p class="text-center" > Top-up with Paypal </p>
                                    </div>
                                </div>
                                <div class="modal fade" id="bank_transfer_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-popout" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">TOPUP through Paypal</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option">
                                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form action="{{route('store.user.wallet.request.topup')}}" class="ajax_paypal_wallet_form_submit" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" value="{{$user->id}}" name="user_id">
                                                    <input type="hidden" value="{{$wallet->id}}" name="wallet_id">
                                                    <input type="hidden" name="type" value="paypal">
                                                    <textarea style="display: none;" name="response"></textarea>
                                                    <div class="block-content font-size-sm">
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
                                                                    <label for="material-error">Amount (USD) <i class="fa fa-question-circle" title="Deposit amount in USD"></i></label>
                                                                    <input required class="form-control amount-to-be-added" type="number"  name="amount"
                                                                           value=""  placeholder="Enter Cheque Amount here">
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
                                                        <button type="button" class="btn btn-sm btn-primary paypal-wallet-pay-btn" >Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                    </div>
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <div class="block">--}}
{{--                                <div class="block-header">--}}
{{--                                    <div class="block-title">--}}
{{--                                        Bank Transfer Top-up Requests--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="block-content">--}}
{{--                                    @if (count($wallet->requests()->where('type','bank transfer')->get()) > 0)--}}
{{--                                        <table class="table table-hover table-borderless table-striped table-vcenter">--}}
{{--                                            <thead>--}}
{{--                                            <tr>--}}
{{--                                                <th>Bank</th>--}}
{{--                                                <th>Cheque</th>--}}
{{--                                                <th>Company/Sender Title</th>--}}
{{--                                                <th>Amount</th>--}}
{{--                                                <th>Bank Proof Copy</th>--}}
{{--                                                <th>Notes</th>--}}
{{--                                                <th>Status</th>--}}
{{--                                            </tr>--}}
{{--                                            </thead>--}}

{{--                                            @foreach($wallet->requests()->where('type','bank transfer')->get() as $index => $req)--}}
{{--                                                <tbody class="">--}}
{{--                                                <tr>--}}
{{--                                                    <td class="font-w600">{{ $req->bank_name }}</td>--}}
{{--                                                    <td>--}}
{{--                                                        @if($req->cheque != null)--}}
{{--                                                            {{$req->cheque}}--}}
{{--                                                        @else--}}
{{--                                                            <span class="text-primary-dark">No Cheque Provided</span>--}}
{{--                                                        @endif--}}

{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        {{$req->cheque_title}}--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        {{number_format($req->amount,2)}} USD--}}
{{--                                                    </td>--}}
{{--                                                    <td class="js-gallery">--}}
{{--                                                        @if($req->attachment != null)--}}
{{--                                                            <a class="img-link img-link-zoom-in img-lightbox" href="{{asset('wallet-attachment')}}/{{$req->attachment}}">--}}
{{--                                                                View Proof--}}
{{--                                                            </a>--}}
{{--                                                        @else--}}
{{--                                                            No Proof Provided--}}
{{--                                                        @endif--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        @if($req->notes != null)--}}
{{--                                                            {{$req->notes}}--}}
{{--                                                        @else--}}
{{--                                                            No Notes--}}
{{--                                                        @endif--}}
{{--                                                    </td>--}}

{{--                                                    <td>--}}
{{--                                                        @if($req->status == 0)--}}
{{--                                                            <span class="badge badge-warning">Pending</span>--}}
{{--                                                        @else--}}
{{--                                                            <span class="badge badge-success">Approved</span>--}}
{{--                                                        @endif--}}
{{--                                                    </td>--}}

{{--                                                </tr>--}}
{{--                                                </tbody>--}}

{{--                                            @endforeach--}}
{{--                                        </table>--}}
{{--                                    @else--}}
{{--                                        <p>No  Bank Transfer Requests Found</p>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <div class="block">--}}
{{--                                <div class="block-header">--}}
{{--                                    <div class="block-title">--}}
{{--                                        AliBaba Top-up Requests--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="block-content">--}}
{{--                                    @if (count($wallet->requests()->where('type','alibaba')->get()) > 0)--}}
{{--                                        <table class="table table-hover table-borderless table-striped table-vcenter">--}}
{{--                                            <thead>--}}
{{--                                            <tr>--}}
{{--                                                <th>Company/Sender Title</th>--}}
{{--                                                <th>Alibaba Order Number </th>--}}
{{--                                                <th>Amount</th>--}}
{{--                                                <th>Bank Proof Copy</th>--}}
{{--                                                <th>Notes</th>--}}
{{--                                                <th>Status</th>--}}
{{--                                            </tr>--}}
{{--                                            </thead>--}}

{{--                                            @foreach($wallet->requests()->where('type','alibaba')->get() as $index => $req)--}}
{{--                                                <tbody class="">--}}
{{--                                                <tr>--}}

{{--                                                    <td>--}}
{{--                                                        {{$req->cheque_title}}--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        {{$req->cheque}}--}}
{{--                                                    </td>--}}

{{--                                                    <td>--}}
{{--                                                        {{number_format($req->amount,2)}} USD--}}
{{--                                                    </td>--}}
{{--                                                    <td class="js-gallery">--}}
{{--                                                        @if($req->attachment != null)--}}
{{--                                                            <a class="img-link img-link-zoom-in img-lightbox" href="{{asset('wallet-attachment')}}/{{$req->attachment}}">--}}
{{--                                                                View Proof--}}
{{--                                                            </a>--}}
{{--                                                        @else--}}
{{--                                                            No Proof Provided--}}
{{--                                                        @endif--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        @if($req->notes != null)--}}
{{--                                                            {{$req->notes}}--}}
{{--                                                        @else--}}
{{--                                                            No Notes--}}
{{--                                                        @endif--}}
{{--                                                    </td>--}}

{{--                                                    <td>--}}
{{--                                                        @if($req->status == 0)--}}
{{--                                                            <span class="badge badge-warning">Pending</span>--}}
{{--                                                        @else--}}
{{--                                                            <span class="badge badge-success">Approved</span>--}}
{{--                                                        @endif--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}


{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                                </tbody>--}}

{{--                                            @endforeach--}}
{{--                                        </table>--}}
{{--                                    @else--}}
{{--                                        <p>No AliBaba Top-up Requests Found</p>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="block">
                                <div class="block-header">
                                    <div class="block-title">
                                        Credit Card Top-up Requests
                                    </div>
                                </div>
                                <div class="block-content">
                                    @if (count($wallet->requests()->where('type','stripe')->get()) > 0)
                                        <table class="table table-hover table-borderless table-striped table-vcenter">
                                            <thead>
                                            <tr>
                                                <th>Company/Sender Title</th>
                                                <th>Amount</th>
                                                <th>Notes</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>

                                            @foreach($wallet->requests()->where('type','stripe')->get() as $index => $req)
                                                <tbody class="">
                                                <tr>
                                                    <td>
                                                        {{$req->cheque_title}}
                                                    </td>

                                                    <td>
                                                        {{number_format($req->amount,2)}} USD
                                                    </td>
                                                    <td>
                                                        @if($req->notes != null)
                                                            {{$req->notes}}
                                                        @else
                                                            No Notes
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if($req->status == 0)
                                                            <span class="badge badge-warning">Pending</span>
                                                        @else
                                                            <span class="badge badge-success">Approved</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                                </tbody>

                                            @endforeach
                                        </table>
                                    @else
                                        <p>No Credit Card Top-up Requests Found</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="block">
                                <div class="block-header">
                                    <div class="block-title">
                                        Paypal Top-up Requests
                                    </div>
                                </div>
                                <div class="block-content">
                                    @if (count($wallet->requests()->where('type','paypal')->get()) > 0)
                                        <table class="table table-hover table-borderless table-striped table-vcenter">
                                            <thead>
                                            <tr>
                                                <th>Company/Sender Title</th>
                                                <th>Amount</th>
                                                <th>Notes</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>

                                            @foreach($wallet->requests()->where('type','paypal')->get() as $index => $req)
                                                <tbody class="">
                                                <tr>
                                                    <td>
                                                        {{$req->cheque_title}}
                                                    </td>

                                                    <td>
                                                        {{number_format($req->amount,2)}} USD
                                                    </td>
                                                    <td>
                                                        @if($req->notes != null)
                                                            {{$req->notes}}
                                                        @else
                                                            No Notes
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if($req->status == 0)
                                                            <span class="badge badge-warning">Pending</span>
                                                        @else
                                                            <span class="badge badge-success">Approved</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>
                                                </tbody>

                                            @endforeach
                                        </table>
                                    @else
                                        <p>No Paypal Top-up Requests Found</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('inc.wallet_log')
                </div>
            </div>
        @endif
    @endif
@endsection
