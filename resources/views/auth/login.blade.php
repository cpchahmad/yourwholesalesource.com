@extends('layout.credential')
@section('content')
    <div class="row ">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="block block-themed  mb-0">
                <div class="block-header bg-success">
                    <h3 class="block-title">Sign In</h3>
                    <div class="block-options">
                        <a class="btn-block-option font-size-sm" href="{{ route('password.request') }}">Forgot Password?</a>
                        <a class="btn-block-option" href="{{route('register')}}" data-toggle="tooltip" data-placement="left" title="New Account">
                            <i class="fa fa-user-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="">
                        <p>Welcome, please login.</p>
                        <div class="block">
                            <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#by_email">By Email</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#by_store">By Store</a>
                                </li>
                            </ul>
                            <div class="block-content tab-content">
                                <div class="tab-pane active" id="by_email" role="tabpanel">
                                    <form class="js-validation-signin" action="{{ route('login') }}" method="POST">
                                     @csrf
                                        <div class="py-3">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input name="email" required value="{{ old('email') }}" class="form-control form-control-alt form-control-lg">
                                                @error('email')
                                                <span class="invalid-feedback" role="alert" style="color: red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" class="form-control form-control-alt form-control-lg" name="password">

                                                @error('password')
                                                <span class="invalid-feedback" role="alert" style="color: red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror

                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="login-remember" name="remember">
                                                    <label class="custom-control-label font-w400" for="login-remember">Remember Me</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6 col-xl-5">
                                                <button type="submit" class="btn btn-block btn-success">
                                                    <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Sign In
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="by_store" role="tabpanel">
                                    <div class="py-3">
                                        <form method="POST" action="{{ route('authenticate') }}">
                                            @csrf
                                            <div class="form-group">
                                                <label for="shop">Domain</label>
                                                <input id="shop" name="shop" class="form-control form-control-alt form-control-lg"
                                                       type="text" autofocus="autofocus" placeholder="example.myshopify.com">

                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-6 col-xl-5">
                                                    <button type="submit" class="btn btn-block btn-success">
                                                        <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Sign In
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
