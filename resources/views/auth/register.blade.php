@extends('layout.credential')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-4">
            <div class="block block-themed block-fx-shadow mb-0">
                <div class="block-header bg-success">
                    <h3 class="block-title">Sign In</h3>
                    <div class="block-options">
                        <a class="btn-block-option font-size-sm" href="{{ route('password.request') }}">Forgot Password?</a>
                        <a class="btn-block-option" href="{{route('login')}}" data-toggle="tooltip" data-placement="left" title="Login">
                            <i class="fa fa-user-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="">
                        <h3 class="mb-2">WeFullFill</h3>
                        <p>Welcome, please Create an account to continue.</p>

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
                                    <form class="" action="{{ route('register') }}" method="POST">
                                       @csrf
                                        <div class="py-3">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input name="name" type="text" required value="{{ old('name') }}" class="form-control form-control-alt form-control-lg">
                                                @error('name')
                                                <span class="invalid-feedback" role="alert" style="color: red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Email</label>
                                                <input name="email" type="email" required value="{{ old('email') }}" class="form-control form-control-alt form-control-lg">
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
                                                <label>Confirm Password</label>
                                                <input type="password" class="form-control form-control-alt form-control-lg" name="password_confirmation">
                                                @error('password')
                                                <span class="invalid-feedback" role="alert" style="color: red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-7 col-xl-7">
                                                <button type="submit" class="btn btn-block btn-success">
                                                    <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Create Account
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
                                                       type="text" placeholder="example.myshopify.com">
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-7 col-xl-7">
                                                    <button type="submit" class="btn btn-block btn-success">
                                                        <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Create Account
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
