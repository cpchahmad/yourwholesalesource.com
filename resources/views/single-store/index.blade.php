@extends('layout.single')
@section('content')
    <div class="content content-narrow">
        <div class="row mb2">
            <div class="col-md-4">
                <h3 class="font-w700">Store Association </h3>
            </div>
            <div class="col-md-8">
                <div class="block">
                    <div class="block-content">
                        @if($associated_user != null)
                        <form class="form-horizontal push-5-t">
                            <div class="form-group">
                                <label class="col-xs-12" for="register1-username">Username</label>
                                <div class="col-xs-12">
                                    <input class="form-control" readonly value="{{$associated_user->name}}" type="text" id="register1-username" name="register1-username" placeholder="Enter your username..">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-xs-12" for="register1-email">Email</label>
                                <div class="col-xs-12">
                                    <input class="form-control" readonly value="{{$associated_user->email}}" type="email" id="register1-email" name="register1-email" placeholder="Enter your email..">
                                </div>
                            </div>
                        </form>
                            @else
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="block">
                                        <div class="block-header">
                                            <h3 class="block-title">Associate with an account</h3>
                                        </div>
                                        <form id="authenticate_user_form" data-store="{{$shop->shopify_domain}}" data-token="{{csrf_token()}}" data-route="{{route('store.user.associate')}}" action="{{route('store.user.authenticate')}}" method="post">
                                            @csrf
                                            <div class="block-content ">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="form-material">
                                                            <label for="material-error">Email Address</label>
                                                            <input required class="form-control" type="email" id="user-email" name="email"
                                                                   value=""   placeholder="Enter Registered Email Address">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="form-material">
                                                            <label for="material-error">Password</label>
                                                            <input required class="form-control" type="password" id="user-password" name="password"
                                                                   value=""  placeholder="Enter Password">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="submit" hidden>
                                        </form>
                                        <div class="block-content block-content-full text-right border-top">
                                            <button type="submit" class="btn btn-sm authenticate_user btn-primary" >Authenticate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
