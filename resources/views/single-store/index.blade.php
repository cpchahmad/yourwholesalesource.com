@extends('layout.single')
@section('content')
    <style>
        .mb2{
            margin-bottom: 10px !important;
        }
        .vertical-line{
            font-size: large;
            text-align: center;
            border-left: 2px solid whitesmoke;
            height: 30px;
            border-right: 2px solid whitesmoke;
            font-weight: bold;
        }
        .swal2-popup {
            font-size: 1.5rem !important;
        }
    </style>
    <div class="content">
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
                            <div class="row push-15">
                                <div class="col-md-5"><a href="" class="btn btn-primary">I dont have an account!</a></div>
                                <div class="col-md-2" ><div class="vertical-line">Or</div></div>
                                <div class="col-md-5 text-right"><a data-toggle="modal" data-target="#associate_modal" class="btn btn-primary">Associate with an account!</a></div>
                                <div class="modal fade" id="associate_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-popout" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Associate with an account</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option">
                                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form id="authenticate_user_form" data-store="{{$shop->shopify_domain}}" data-token="{{csrf_token()}}" data-route="{{route('store.user.associate')}}" action="{{route('store.user.authenticate')}}" method="post">
                                                    @csrf
                                                    <div class="block-content font-size-sm">
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <input required class="form-control" type="email" id="user-email" name="email"
                                                                           value=""   placeholder="Enter Registered Email Address">
                                                                    <label for="material-error">Email Address</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-material">
                                                                    <input required class="form-control" type="password" id="user-password" name="password"
                                                                           value=""  placeholder="Enter Password">
                                                                    <label for="material-error">Password</label>
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
                                </div>

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection
