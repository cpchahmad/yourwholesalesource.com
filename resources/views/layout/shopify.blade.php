<!DOCTYPE html>
<html>
@include('inc.header')
<body>
<div id="page-container" class="sidebar-o sidebar-dark side-overlay-hover  enable-page-overlay side-scroll page-header-fixed">


{{--    <!-- Side Overlay-->--}}
{{--    <aside id="side-overlay" class="font-size-sm">--}}
{{--        <!-- Side Content -->--}}
{{--        <div class="content-side">--}}
{{--            <!-- Side Overlay Tabs -->--}}
{{--            <div class="block block-transparent pull-x pull-t">--}}
{{--                <ul class="nav nav-tabs nav-tabs-alt nav-justified" data-toggle="tabs" role="tablist">--}}
{{--                    <li class="nav-item">--}}
{{--                        <a class="nav-link active">--}}
{{--                            <i class="fa fa-fw fa-user text-gray mr-1"></i> Your Sale Manager Info--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--                <div class="block-content tab-content overflow-hidden">--}}
{{--                    <!-- Overview Tab -->--}}
{{--                    <div class="tab-pane pull-x fade fade-left show active" id="so-overview" role="tabpanel">--}}
{{--                        <!-- Activity -->--}}
{{--                        <div class="block">--}}
{{--                            <div class="block-content">--}}
{{--                                <!-- Activity List -->--}}
{{--                                <ul class="nav-items mb-0 text-center">--}}
{{--                                    @if($manager)--}}
{{--                                        <li>--}}
{{--                                            <div class="text-dark media py-2">--}}
{{--                                                <div class="media-body">--}}
{{--                                                    <img class="img-avatar-rounded" @if($manager->profile == null) src="{{ asset('assets/media/avatars/avatar10.jpg') }}" @else  src="{{asset('managers-profiles')}}/{{$manager->profile}}" @endif alt="Header Avatar" style="width: 18px;">--}}
{{--                                                    <div class="font-w600">{{$manager->name}} {{$manager->last_name}}</div>--}}
{{--                                                    <div class="font-w600">{{$manager->email}}</div>--}}
{{--                                                    <div class="text-info">--}}
{{--                                                        <i class="fab fa-whatsapp text-success fa-lg"></i>--}}
{{--                                                        <a target="_blank" href="https://api.whatsapp.com/send?phone={{$manager->whatsapp}}">Whatsapp {{$manager->whatsapp}}</a>--}}
{{--                                                    </div>--}}
{{--                                                    <div class="text-info">--}}
{{--                                                        <i class="fab fa-skype text-info fa-lg"></i>--}}
{{--                                                        <a href="skype:{{$manager->skype}}?chat">{{ $manager->skype }}</a>--}}
{{--                                                    </div>--}}

{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </li>--}}
{{--                                    @endif--}}
{{--                                    <li>--}}
{{--                                        <div class="text-dark media py-4">--}}
{{--                                            <div class="media-body">--}}
{{--                                                <div class="font-w600 text-left">Wallet Balance</div>--}}
{{--                                                <div class="mt-2 p-2 bg-primary text-white">{{number_format($balance,2)}} USD</div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <div class="text-dark media py-2" href="javascript:void(0)">--}}
{{--                                            <div class="media-body">--}}
{{--                                                <div class="font-w600 text-left">Help us improve our App</div>--}}
{{--                                                <form method="POST" action="{{ route('suggestion.create') }}" class="mt-2">--}}
{{--                                                    @csrf--}}
{{--                                                    <textarea class="form-control" name="suggestion"></textarea>--}}
{{--                                                    <input type="hidden" name="user_email" value="{{ auth()->user()->email }}">--}}
{{--                                                    <button class="btn btn-sm btn-success mt-2">Submit</button>--}}
{{--                                                </form>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                                <!-- END Activity List -->--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- END Activity -->--}}
{{--                    </div>--}}
{{--                    <!-- END Overview Tab -->--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <!-- END Side Overlay Tabs -->--}}
{{--        </div>--}}
{{--        <!-- END Side Content -->--}}
{{--    </aside>--}}
{{--    <!-- END Side Overlay -->--}}

    @include('layout.shopify_sidebar')
    <main id="main-container">
        @include('flash_message.message')
        @if(auth()->check())
            @if(count(auth()->user()->has_shops) == 0)
                <div class="alert alert-info alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>You dont have connected any store yet. For store connection <a data-target="#add_store_modal" class="text-primary" style="cursor: pointer;" data-toggle="modal">click here</a>.</strong>
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
                </div>
            @endif
        @endif
        @yield('content')
    </main>
@php
$countries = \App\Country::all();

@endphp

    <div class="modal fade" data-route="{{route('app.questionaire.check')}}" data-user="{{auth()->id()}}" id="questionnaire_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Some Basic Information We needed</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option">
                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                            </button>
                        </div>
                    </div>
                    <form action="{{route('app.questionaire.post')}}" method="post">
                        @csrf
                        <input type="hidden" name="user_id" @if(auth()->check()) value="{{auth()->id()}}" @endif>
                        <div class="block-content font-size-sm">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    @if(Session::has('failure'))
                                        <div class="alert alert-info alert-block">
                                            {{ Session::get('failure') }}
                                        </div>
                                    @endisset
                                    <div class="form-material">
                                        <label for="material-error"> Gender</label>
                                        <select class="form-control " style="width: 100%;"   name="gender" required  >
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error"> Date of Birth</label>
                                        <input required class="form-control" type="date"  name="dob" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">Are you new to business or you have your online Online store already?</label>
                                        <input required class="form-control" type="text"  name="new_to_business" value="" >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error"> What is your target product ranges? </label>
                                        <select class="form-control js-select2" style="width: 100%;" data-placeholder="Choose multiple " name="product_ranges[]" required  multiple="">
                                            <option value="Electronics">Electronics</option>
                                            <option value="Home and Garden">Home and Garden </option>
                                            <option value="Kids and Toy">Kids and Toy</option>
                                            <option value="Health and Beauty">Health and Beauty</option>
                                            <option value="Sports and Outdoor">Sports and Outdoor</option>
                                            <option value="Fashions">Fashions</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">Which of the countries you would like to sell to?</label>
                                        <select class="form-control js-select2" style="width: 100%;" data-placeholder="Choose multiple" name="countries[]" required  multiple="">
                                            <option></option>
                                            @foreach($countries as $country)
                                                <option value="{{$country->name}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">What is your delivery time request for your orders to be delivered ?</label>
                                        <input required class="form-control" type="text"  name="delivery_time" value="" >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <label for="material-error">What is your most concern in our drop shipping service?</label>
                                        <select class="form-control js-select2" style="width: 100%;" data-placeholder="Choose multiple" name="concerns[]" required  multiple="">
                                            <option></option>
                                            <option value="Communication">Communication</option>
                                            <option value="Price">Price</option>
                                            <option value="Product Trends">Product Trends</option>
                                            <option value="Delivery Time">Delivery Time</option>
                                            <option value="Product Quality">Product Quality</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="block-content block-content-full text-right border-top">
                            <button type="submit" class="btn btn-sm btn-primary" >Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    @include('inc.footer')
</div>

</body>
</html>
