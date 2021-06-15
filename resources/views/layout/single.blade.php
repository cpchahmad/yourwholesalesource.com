<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Awareness Drop Shipping</title>

    <meta name="description" content="WeFullfill 2020 created by TetraLogicx Pvt. Limited.">
    <meta name="author" content="tetralogicx">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">


    <link rel="shortcut icon" href="{{ asset('assets/wholesale.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/wholesale.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/wholesale.png') }}">

    <meta property="og:title" content="YourWholesaleSource">
    <meta property="og:site_name" content="YourWholesaleSource">
    <meta property="og:description" content="YourWholesaleSource">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    @include('inc.font')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/js/plugins/dropzone/dist/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/magnific-popup/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/select2/css/select2.css')}}">
    <link rel="stylesheet" href="{{asset('assets/custom.css')}}">

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{now()}}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" integrity="sha256-aa0xaJgmK/X74WM224KMQeNQC2xYKwlAt08oZqjeF0E=" crossorigin="anonymous" />


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        .overlay{
            width: 100%;
            height: 250px;
            background-image: linear-gradient(rgba(0,0,0,0.3),rgba(0,0,0,0.2)), url("https://cdn.shopify.com/s/files/1/0370/7361/7029/files/Wefullfill.jpg?v=1598885447");
            background-position: center;
            background-size: cover;
        }

        .section-status-mobile {
            display: none !important;
        }
        .status-section {
            display: block;
        }

        @media(max-width: 612px) {
            .section-status-mobile {
                display: block !important;
            }

            .status-section {
                display: none;
            }

        }

        .tooltip-ex { /* Container for our tooltip */
            position: relative;
            display: inline-block;
        }

        .tooltip-ex .tooltip-ex-text { /* This is for the tooltip text */
            visibility: hidden;
            width: 100px;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 10px; /* This defines tooltip text position */
            /*position: absolute;*/
            z-index: 1;
        }

        .tooltip-ex:hover .tooltip-ex-text { /* Makes tooltip text visible when text is hovered on */
            visibility: visible;
        }


    </style>

</head>
<body>
<div id="page-container" class="sidebar-o sidebar-dark  side-overlay-hover enable-page-overlay side-scroll page-header-fixed">

{{--    @php--}}
{{--        $shop =  \OhMyBrew\ShopifyApp\Facades\ShopifyApp::shop();--}}
{{--        /*Local Shop Model!*/--}}
{{--        $shop= \App\Shop::find($shop->id);--}}
{{--           if($shop->has_manager != null){--}}
{{--           $manager = $shop->has_manager;--}}
{{--           }--}}
{{--           else{--}}
{{--               $manager = null;--}}
{{--           }--}}

{{--         if(count($shop->has_user) > 0){--}}
{{--            $associated_user =   $shop->has_user[0];--}}
{{--        }--}}
{{--        else{--}}
{{--            $associated_user = null;--}}
{{--        }--}}
{{--    @endphp--}}

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
{{--                                                        <a href="https://api.whatsapp.com/send?phone={{$manager->whatsapp}}">Whatsapp {{$manager->whatsapp}}</a>--}}
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
{{--                                                    <input type="hidden" name="user_email" value="{{ $associated_user->email }}">--}}
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

    @include('layout.single_sidebar')
    <main id="main-container">
        @include('flash_message.message')

        @php

            use App\Shop;
            $current_shop = \OhMyBrew\ShopifyApp\Facades\ShopifyApp::shop();
            $shop = Shop::where('shopify_domain',$current_shop->shopify_domain)->first();
            $countries = \App\Country::all();

        @endphp


        @if(count($shop->has_user) == 0)
            <div class="alert alert-info alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>To Initiate Your Awareness Drop Shipping Wallet Services. Please <a href="{{route('store.index')}}"> Complete Your Registration</a>.</strong>
            </div>
        @endif

        @yield('content')
    </main>


    <div class="modal fade" data-route="{{route('app.questionaire.check')}}" data-shop="{{$shop->id}}" id="questionnaire_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
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
                        <input type="hidden" name="shop_id" value="{{$shop->id}}">
                        <div class="block-content font-size-sm">
                            <div class="form-group">
                                <div class="col-sm-12">
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


    <footer id="page-footer" class="bg-body-light">
        <div class="content py-3">
            <div class="row font-size-sm">
                <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
{{--                    Designed by <i class="fa fa-bolt text-danger"></i> <a class="font-w600" href="https://tetralogicx.com" target="_blank">Fantasy Supply Limited</a>--}}
                </div>
            </div>
        </div>
    </footer>

</div>



<script src="{{ asset('assets/js/oneui.core.min.js') }}"></script>
<script src="{{ asset('assets/js/oneui.app.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>]

<script src="{{ asset('js/single-store.js') }}?v={{now()}}"></script>
<script src="{{ asset('assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzone/dist/dropzone.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{asset('assets/js/plugins/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/select2/js/select2.min.js')}}"></script>

<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script src="{{asset('js/jquery.lazy.min.js')}}"></script>
<script>
    $(function() {
        $('img').lazy();
    });
</script>



<script>jQuery(function(){ One.helpers(['summernote','magnific-popup','table-tools-sections','masked-inputs','select2','table-tools-checkable']); });</script>

<div class="pre-loader">
    <div class="loader">
    </div>
</div>
</body>
</html>
