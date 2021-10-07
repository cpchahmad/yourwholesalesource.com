@extends('layout.credential')
@section('content')
    <div class="block">
        <div class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-header bg-modern-dark">
                        <h3 class="block-title text-white">Associate with an account</h3>
                    </div>
                    <ul class="nav nav-tabs nav-tabs-block nav-justified" data-toggle="tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active js-new-user-tab" href="#new">Create New</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-existing-user-tab" href="#existing">Link Existing Account</a>
                        </li>
                    </ul>

                    <div class="block-content tab-content">
                        <div class="tab-pane active" role="tabpanel">
                            <form id="authenticate_user_form" data-store="{{$shop->shopify_domain}}" data-token="{{csrf_token()}}" data-route="{{route('store.user.associate')}}" action="{{route('store.user.authenticate')}}" method="post">
                                @csrf
                                <div class="block-content p-0">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Name</label>
                                                <input required class="form-control" type="text" id="user-name" name="name"
                                                       value=""   placeholder="Enter Name">

                                            </div>
                                        </div>
                                    </div>

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
            </div>
        </div>
    </div>
@endsection
