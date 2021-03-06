@extends('layout.shopify')
@section('content')
    <div class="content content-narrow">
        <div class="row mb2">
            <div class="col-md-4">
                <h3 class="font-w700">Settings </h3>
            </div>
            <div class="col-md-8">

                <div class="row">
                    <div class="col-md-12">
                        <div class="block">
                            <div class="block-header">
                                <h3 class="block-title">List of Shopify stores

                                    <button class="btn btn-success btn-sm ml-2 float-right" data-target="#add_store_modal" data-toggle="modal"> Add Store </button>
                                    <div class="modal fade" id="add_store_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-popout" role="document">
                                            <div class="modal-content">
                                                <div class="block block-themed block-transparent mb-0">
                                                    <div class="block-header bg-primary-dark">
                                                        <h3 class="block-title">Add Store</h3>
                                                        <div class="block-options">
                                                            <button type="button" class="btn-block-option">
                                                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="block-content pb-3">
                                                        <div class="text-center push-10-t push-30">
                                                            <img class="w-50" src="https://png.pngitem.com/pimgs/s/173-1738304_shopify-hd-png-download.png" alt="">
                                                        </div>
                                                        <div class="push-30">
                                                            <form method="POST" action="{{ route('authenticate') }}">
                                                                {{ csrf_field() }}
                                                                <div class="form-material" style="margin-bottom: 10px">
                                                                    <label for="shop">Store Domain</label>
                                                                    <input id="shop" name="shop" class="form-control" type="text" autofocus="autofocus" placeholder="example.myshopify.com">
                                                                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">

                                                                </div>

                                                                <button class="btn btn-primary" type="submit">Connect </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{route('users.stores')}}" class="btn btn-primary btn-sm" style="float: right"> Manage Stores</a>
                                </h3>
                            </div>

                            <div class="block-content ">
                                <table class="js-table-sections table table-hover">
                                    <tbody>
                                    <form method="POST" action="{{ route('authenticate') }}" class="shop-login-form">
                                        @csrf
                                        @foreach($associated_user->has_shops as $index => $shop)
                                        <tr>
                                            <td style="vertical-align: middle">{{ $shop->shopify_domain }}</td>
                                            <td class="text-right" style="vertical-align: middle">
                                                <button type="button" class="btn btn-sm btn-success settings-shop-log-btn" >
                                                    <input type="hidden" class="shop-domain-name" value="{{$shop->shopify_domain}}">
                                                    <input type="hidden" name="shop" value="" class="shop-domain-input">
                                                    Switch View
                                                </button>
{{--                                                <a href="{{url('/shop/install?shop='.$shop->shopify_domain)}}" class="">Switch View</a>--}}

                                                <a data-href="{{route('store.user.de-associate',$shop->id)}}" class="de-associate-button btn btn-xs btn-danger text-white"
                                                    title="Remove Store" ><i class="fa fa-trash"></i></a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </form>
                                    </tbody>
                                </table>
                            </div>
                        </div>


{{--                        <div class="block">--}}
{{--                            <div class="block-header">--}}
{{--                                <h3 class="block-title">--}}
{{--                                    List of Woocommerce stores--}}
{{--                                    <a href="{{route('system.woocommerce.store.connect')}}" class="btn btn-success btn-sm" style="float: right;margin-left: 10px"> Add Store</a>--}}
{{--                                    <a href="{{route('users.woocommerce.stores')}}" class="btn btn-primary btn-sm" style="float: right"> Manage Stores</a>--}}
{{--                                </h3>--}}
{{--                            </div>--}}

{{--                            <div class="block-content ">--}}
{{--                                <table class="js-table-sections table table-hover">--}}
{{--                                    <tbody>--}}
{{--                                    <form method="POST" action="{{ route('switch.woocommerce') }}" class="woo-shop-login-form">--}}
{{--                                        @csrf--}}
{{--                                        @foreach($associated_user->has_woocommerce_shops as $index => $shop)--}}
{{--                                            <tr>--}}
{{--                                                <td style="vertical-align: middle">{{ $shop->woocommerce_domain }}</td>--}}
{{--                                                <td class="text-right" style="vertical-align: middle">--}}
{{--                                                    <button type="button" class="btn btn-sm btn-success settings-woo-shop-log-btn" >--}}
{{--                                                        <input type="hidden" class="woo-shop-domain-name" value="{{$shop->woocommerce_domain}}">--}}
{{--                                                        <input type="hidden" name="woocommerce_domain" value="" class="woo-shop-domain-input">--}}
{{--                                                        Switch View--}}
{{--                                                    </button>--}}
{{--                                                    <a data-href="{{route('store.user.de-associate',$shop->id)}}" class="de-associate-button btn btn-xs btn-danger text-white"--}}
{{--                                                       title="Remove Store" ><i class="fa fa-trash"></i></a>--}}

{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                    </form>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                        </div>--}}


                        <div class="block">
                            <div class="block-header">
                                <h3 class="block-title">Account Details</h3>
                            </div>
                            <div class="block-content">
                                <form action="{{route('users.save_personal_info')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$associated_user->id}}">
                                    <div class="image-profile text-center mb2">
                                         @if($associated_user->tax_certificate !== null)
                                            <a class="btn btn-primary" href="{{asset('managers-profiles')}}/{{$associated_user->tax_certificate}}" target="_blank">View Tax Exempt Certificate</a>
                                         @endif
                                    </div>
                                    <div class="image-profile text-center mb2">
                                        <a  class="btn btn-primary text-white upload-manager-profile" style="margin: 10px">Upload Tax Exempt Certificate</a>
                                        <input type="file" name="tax_certificate" class="manager-profile form-control" style="display: none">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="text" name="email"  class="form-control  @error('email') is-invalid @enderror" value="{{$associated_user->email}}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Company Name</label>
                                        <input type="text" name="company_name"  class="form-control  @error('company_name') is-invalid @enderror" value="{{$associated_user->company_name}}">
                                        @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phone Number</label>
                                        <input type="text" name="phone"  class="form-control  @error('phone') is-invalid @enderror" value="{{$associated_user->phone}}">
                                        @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Username</label>
                                        <input type="text" required name="name" class="form-control" value="{{$associated_user->name}}">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Save">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="block">
                            <div class="block-header">
                                <h3 class="block-title">Change Password</h3>
                            </div>
                            <div class="block-content">
                                <form id="change_password_manager_form" action="{{route('users.change.password')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$associated_user->id}}">
                                    <div class="form-group">
                                        <label for="">Current Password</label>
                                        <input type="password" required name="current_password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">New Password</label>
                                        <input type="password" required name="new_password" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">New Password (Again)</label>
                                        <input type="password" required name="new_password_again" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Change">
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="block">
                            <div class="block-header">
                                <h3 class="block-title">Address Information</h3>
                            </div>
                            <div class="block-content">
                                <form action="{{route('users.save_address')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$associated_user->id}}">
                                    <div class="form-group">
                                        <label for="">Street Address</label>
                                        <input type="text"  name="address" class="form-control" value="{{$associated_user->address}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Address 2</label>
                                        <input type="text"  name="address2" class="form-control" value="{{$associated_user->address2}}">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label for="">City</label>
                                            <input type="text"  name="city" class="form-control" value="{{$associated_user->city}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">State</label>
                                            <input type="text"  name="state" class="form-control" value="{{$associated_user->state}}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="">Zip</label>
                                            <input type="text"  name="zip" class="form-control" value="{{$associated_user->zip}}">
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label for="">Country</label>
                                        <select name="country" class="form-control">
                                            @foreach($countries as $country)
                                                <option @if($associated_user->country == $country->name) selected @endif value="{{$country->name}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Save">
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
