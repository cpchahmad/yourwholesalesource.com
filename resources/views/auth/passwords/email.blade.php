@extends('layout.credential')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-4">
            <div class="block block-themed block-fx-shadow mb-0">
                <div class="block-header bg-success">
                    <h3 class="block-title">Reset Password</h3>
                    <div class="block-options">
                        <a class="btn-block-option" href="{{route('login')}}" data-toggle="tooltip" data-placement="left" title="Login">
                            <i class="fa fa-user-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="block-content">
                    <div class="">
                        <h3 class="mb-2">WeFullFill</h3>
                        <p>Welcome, please enter email to reset password.</p>
                        <div class="block">

                            @if (session('status'))

                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form class="js-validation-signin" action="{{ route('password.email') }}" method="POST">
                                {{ csrf_field() }}
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
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6 col-xl-5">
                                        <button type="submit" class="btn btn-block btn-success">
                                            <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Reset
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
@endsection
