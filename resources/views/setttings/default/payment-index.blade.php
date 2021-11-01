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
                        <h3 class="block-title">Payment API Settings</h3>
                    </div>
                    <div class="block-content block-content-narrow">
                        <form class="form-horizontal push-10-t"
                              action="{{route('payment.charge.save')}}"
                              method="post">
                            @csrf

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
                                        <label>Paypal Script Tag</label>
                                        <textarea class="form-control" name="paypal_script_tag" required>@if($settings != null){{$settings->paypal_script_tag}}@endif</textarea>
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

            <div class="col-md-12">
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">Other API Settings</h3>
                    </div>
                    <div class="block-content block-content-narrow">
                        <form class="form-horizontal push-10-t"
                              action="{{route('api.settings.save')}}"
                              method="post">
                            @csrf

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label>OmniSend Api Key</label>
                                        <input class="form-control" type="text" name="omni_key" required
                                               @if($settings != null)  value="{{$settings->omni_key}}" @endif>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label>USPS User ID</label>
                                        <input class="form-control" type="text" name="usps_user_id" required
                                               @if($settings != null)  value="{{$settings->usps_user_id}}" @endif>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label>USPS Origin Zip</label>
                                        <input class="form-control" type="text" name="usps_origin_zip" required
                                               @if($settings != null)  value="{{$settings->usps_origin_zip}}" @endif>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label>ShipStation Key</label>
                                        <input class="form-control" type="text" name="ship_station_key" required
                                               @if($settings != null)  value="{{$settings->ship_station_key}}" @endif>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label>Inflow Company Id</label>
                                        <input class="form-control" type="text" name="inflow_company_id" required
                                               @if($settings != null)  value="{{$settings->inflow_company_id}}" @endif>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label>Inflow API Key</label>
                                        <input class="form-control" type="text" name="inflow_api_key" required
                                               @if($settings != null)  value="{{$settings->inflow_api_key}}" @endif>

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
