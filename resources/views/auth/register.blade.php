@extends('layout.credential')

@section('content')
    <style>
        .block > .nav-tabs.nav-tabs-alt > li > a:hover {
            -webkit-box-shadow: 0 2px #7eaa41 !important;
            box-shadow: 0 2px #7eaa41 !important;
        }
        .block > .nav-tabs.nav-tabs-alt > li.active > a,
        .block > .nav-tabs.nav-tabs-alt > li.active > a:hover,
        .block > .nav-tabs.nav-tabs-alt > li.active > a:focus {
            -webkit-box-shadow: 0 2px #7eaa41 !important;
            box-shadow: 0 2px #7eaa41 !important;
        }
        .block > .nav-tabs > li > a:hover {
            color: #7eaa41;
            background-color: transparent;
            border-color: transparent;
        }
        .form-material.form-material-success > .form-control:focus {
            -webkit-box-shadow: 0 2px 0 #7eaa41;
            box-shadow: 0 2px 0 #7eaa41;
        }
        .form-material.form-material-success > .form-control:focus + label {
            color: #7eaa41;
        }
    </style>
    <div class="content overflow-hidden">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <!-- Register Block -->
                <div class="block block-themed animated fadeIn">
                    <div class="block-header" style="background-color: #7eaa41;">
                        <ul class="block-options">
                            <li>
                                <a href="#" data-toggle="modal" data-target="#modal-terms">View Terms</a>
                            </li>
                        </ul>
                        <h3 class="block-title">Register</h3>
                    </div>
                    <div class="block-content block-content-full block-content-narrow">
                        <!-- Register Title -->
                        <div class="text-center">
                            <img src="{{asset('assets/img/logos/1.png')}}" style="width:50%" alt="">
                            <p class="font-w600 push-15-t push-15" style="font-size: 14px">Please fill the following details to create a new account.</p>
                        </div>
{{--                        <h1 class="h2 font-w600 push-30-t push-5">WeFullFill</h1>--}}
{{--                        <p>Please fill the following details to create a new account.</p>--}}
                        <!-- END Register Title -->

                        <div class="block">
                            <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs">
                                <li class="active">
                                    <a href="#btabs-alt-static-home">By Account</a>
                                </li>
                                <li class="">
                                    <a href="#btabs-alt-static-profile">By Store</a>
                                </li>
                            </ul>
                            <div class="block-content tab-content">
                                <div class="tab-pane active" id="btabs-alt-static-home">
                                    <form method="POST" action="{{ route('register') }}" class="form-horizontal push-5-t push-5">
                                        @csrf

                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="form-material form-material-success">
                                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name"
                                                           placeholder="Enter User Name" autofocus>

                                                    @error('name')
                                                    <span class="invalid-feedback" role="alert" style="color: red">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                    @enderror
                                                    <label for="name">Username</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="form-material form-material-success">
                                                    <input id="email" type="email"
                                                           placeholder="Enter Email Address"
                                                           class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert" style="color: red">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                    @enderror
                                                    <label for="email">Email</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="form-material form-material-success">
                                                    <input id="password" type="password"
                                                           placeholder="Enter a 8 Character Password"
                                                           class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert" style="color: red">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                    @enderror
                                                    <label for="password">Password</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="form-material form-material-success">
                                                    <input id="password-confirm"
                                                           placeholder="Enter Your 8 Character Password Againn...."
                                                           type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                                    <label for="password-confirm">Confirm Password</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-6 col-md-5">
                                                <button class="btn btn-block" style="background-color:#7eaa41;color: white" type="submit">Sign Up</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane " id="btabs-alt-static-profile">
                                    <form method="POST" action="{{ route('authenticate') }}">
                                        {{ csrf_field() }}
                                        <div class="form-material form-material-success">
                                            <input id="shop" name="shop" class="form-control" type="text" autofocus="autofocus" placeholder="example.myshopify.com">
                                            <label for="shop">Store Domain</label>
                                        </div>

                                        <button class="btn" style="background-color:#7eaa41;color: white" type="submit">Sign Up</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <a href="{{route('login')}}" data-toggle="tooltip" data-placement="top" title="Log In">Already a user?</a>
                    </div>
                </div>
                <!-- END Register Block -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout">
            <div class="modal-content">
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <ul class="block-options">
                            <li>
                                <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                            </li>
                        </ul>
                        <h3 class="block-title">Terms &amp; Conditions</h3>
                    </div>
                    <div class="block-content">
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal"><i class="fa fa-check"></i> I agree</button>
                </div>
            </div>
        </div>
    </div>

{{--<div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Register') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    <form method="POST" action="{{ route('register') }}">--}}
{{--                        @csrf--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>--}}

{{--                                @error('name')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">--}}

{{--                                @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">--}}

{{--                                @error('password')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row mb-0">--}}
{{--                            <div class="col-md-6 offset-md-4">--}}
{{--                                <button type="submit" class="btn btn-primary">--}}
{{--                                    {{ __('Register') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
@endsection
