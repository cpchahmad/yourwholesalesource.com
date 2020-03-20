@extends('layout.credential')

@section('content')
    <style>
        #header-navbar{
            display: none !important;
        }
        #sidebar{
            display: none !important;
        }
    </style>
    <div class="content overflow-hidden">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <!-- Login Block -->


                <div class="block block-themed animated fadeIn">
                    <div class="block-header bg-primary">
                        <ul class="block-options">
                            <li>
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            </li>
{{--                            <li>--}}
{{--                                <a href="{{route('register')}}" data-toggle="tooltip" data-placement="top" title="New Account">Create an account ?</a>--}}
{{--                            </li>--}}
                        </ul>
                        <h3 class="block-title">Login</h3>
                    </div>
                    <div class="block-content block-content-full block-content-narrow">
                        <!-- Login Title -->
                        <h1 class="h2 font-w600 push-30-t push-5">Fantasy Supplier</h1>
                        <p>Welcome, please login.</p>
                        <!-- END Login Title -->
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
                                    <form method="POST" action="{{ route('login') }}" class="form-horizontal push-30-t push-2">
                                        @csrf
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="form-material form-material-primary floating">
                                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                                    <label for="email">Email Address</label>
                                                </div>

                                                @error('email')
                                                <span class="invalid-feedback" role="alert" style="color: red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <div class="form-material form-material-primary floating">
                                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                                    <label for="password">Password</label>
                                                </div>
                                                @error('password')
                                                <span class="invalid-feedback" role="alert" style="color: red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <label class="css-input switch switch-sm switch-primary">
                                                    <input type="checkbox" id="login-remember-me" name="remember"><span></span> Remember Me?
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <button class="btn btn-block btn-primary" type="submit">Log in </button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane " id="btabs-alt-static-profile">
                                    <form method="POST" action="{{ route('authenticate') }}">
                                        {{ csrf_field() }}
                                        <div class="form-material">
                                            <input id="shop" name="shop" class="form-control" type="text" autofocus="autofocus" placeholder="example.myshopify.com">
                                            <label for="shop">Store Domain</label>
                                        </div>

                                        <button class="btn btn-primary" type="submit">Log in </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Login Form -->
                        <!-- jQuery Validation (.js-validation-login class is initialized in js/pages/base_pages_login.js) -->
                        <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->

                        <!-- END Login Form -->
                        <a href="{{route('register')}}" data-toggle="tooltip" data-placement="top" title="New Account">Create an account?</a>
                    </div>
                </div>
                <!-- END Login Block -->
            </div>
        </div>
    </div>
{{--    <div class="container">--}}
{{--        <div class="row justify-content-center">--}}
{{--            <div class="col-md-8">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header">{{ __('Login') }}</div>--}}

{{--                    <div class="card-body">--}}
{{--                        <form method="POST" action="{{ route('login') }}">--}}
{{--                            @csrf--}}

{{--                            <div class="form-group row">--}}
{{--                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>--}}

{{--                                    @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                    <strong>{{ $message }}</strong>--}}
{{--                                </span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group row">--}}
{{--                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">--}}

{{--                                    @error('password')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                    <strong>{{ $message }}</strong>--}}
{{--                                </span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group row">--}}
{{--                                <div class="col-md-6 offset-md-4">--}}
{{--                                    <div class="form-check">--}}
{{--                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

{{--                                        <label class="form-check-label" for="remember">--}}
{{--                                            {{ __('Remember Me') }}--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group row mb-0">--}}
{{--                                <div class="col-md-8 offset-md-4">--}}
{{--                                    <button type="submit" class="btn btn-primary">--}}
{{--                                        {{ __('Login') }}--}}
{{--                                    </button>--}}

{{--                                    @if (Route::has('password.request'))--}}
{{--                                        <a class="btn btn-link" href="{{ route('password.request') }}">--}}
{{--                                            {{ __('Forgot Your Password?') }}--}}
{{--                                        </a>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
