@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Payment Settings
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Payment Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">Payment Charge Percentage</h3>
                    </div>
                    <div class="block-content block-content-narrow">
                        <form class="form-horizontal push-10-t"
                              action="{{route('payment.charge.save')}}"
                              method="post">
                            @csrf
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label>Credit Card Percentage</label>
                                        <input class="form-control" type="number" step="any" name="payment_charge_percentage" required
                                         @if($settings != null)  value="{{$settings->payment_charge_percentage}}" @endif>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label>Paypal Percentage</label>
                                        <input class="form-control" type="number" step="any" name="paypal_percentage" required
                                               @if($settings != null)  value="{{$settings->paypal_percentage}}" @endif>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label>Stripe Public Key</label>
                                        <input class="form-control" type="input"  name="stripe_public" required
                                               @if($settings != null)  value="{{$settings->stripe_public}}" @endif>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label>Stripe Private Key</label>
                                        <input class="form-control" type="input"  name="stripe_private" required
                                               @if($settings != null)  value="{{$settings->stripe_private}}" @endif>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
