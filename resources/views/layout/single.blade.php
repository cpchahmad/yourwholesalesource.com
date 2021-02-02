<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>WeFullfill</title>

    <meta name="description" content="WeFullfill 2020 created by TetraLogicx Pvt. Limited.">
    <meta name="author" content="tetralogicx">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">


    <link rel="shortcut icon" href="{{ asset('assets/img/favicons/wefullfill.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/img/favicons/wefullfill.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/wefullfill.png') }}">

    <meta property="og:title" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="OneUI">
    <meta property="og:description" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
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
    </style>

</head>
<body>
<div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">

    <!-- Side Overlay-->
    <aside id="side-overlay" class="font-size-sm">
        <!-- Side Header -->
        <div class="content-header border-bottom">
            <!-- User Avatar -->
            <a class="img-link mr-1" href="javascript:void(0)">
                <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar10.jpg" alt="">
            </a>
            <!-- END User Avatar -->

            <!-- User Info -->
            <div class="ml-2">
                <a class="link-fx text-dark font-w600" href="javascript:void(0)">Adam McCoy</a>
            </div>
            <!-- END User Info -->

            <!-- Close Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="ml-auto btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
                <i class="fa fa-fw fa-times text-danger"></i>
            </a>
            <!-- END Close Side Overlay -->
        </div>
        <!-- END Side Header -->

        <!-- Side Content -->
        <div class="content-side">
            <!-- Side Overlay Tabs -->
            <div class="block block-transparent pull-x pull-t">
                <ul class="nav nav-tabs nav-tabs-alt nav-justified" data-toggle="tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#so-overview">
                            <i class="fa fa-fw fa-coffee text-gray mr-1"></i> Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#so-sales">
                            <i class="fa fa-fw fa-chart-line text-gray mr-1"></i> Sales
                        </a>
                    </li>
                </ul>
                <div class="block-content tab-content overflow-hidden">
                    <!-- Overview Tab -->
                    <div class="tab-pane pull-x fade fade-left show active" id="so-overview" role="tabpanel">
                        <!-- Activity -->
                        <div class="block">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Recent Activity</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                        <i class="si si-refresh"></i>
                                    </button>
                                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                </div>
                            </div>
                            <div class="block-content">
                                <!-- Activity List -->
                                <ul class="nav-items mb-0">
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="si si-wallet text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">New sale ($15)</div>
                                                <div class="text-success">Admin Template</div>
                                                <small class="text-muted">3 min ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="si si-pencil text-info"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">You edited the file</div>
                                                <div class="text-info">
                                                    <i class="fa fa-file-text"></i> Documentation.doc
                                                </div>
                                                <small class="text-muted">15 min ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="si si-close text-danger"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">Project deleted</div>
                                                <div class="text-danger">Line Icon Set</div>
                                                <small class="text-muted">4 hours ago</small>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <!-- END Activity List -->
                            </div>
                        </div>
                        <!-- END Activity -->

                        <!-- Online Friends -->
                        <div class="block">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Online Friends</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                        <i class="si si-refresh"></i>
                                    </button>
                                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                </div>
                            </div>
                            <div class="block-content">
                                <!-- Users Navigation -->
                                <ul class="nav-items mb-0">
                                    <li>
                                        <a class="media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2 overlay-container overlay-bottom">
                                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar8.jpg" alt="">
                                                <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-success"></span>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">Lisa Jenkins</div>
                                                <div class="font-w400 text-muted">Copywriter</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2 overlay-container overlay-bottom">
                                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar13.jpg" alt="">
                                                <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-success"></span>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">Jose Mills</div>
                                                <div class="font-w400 text-muted">Web Developer</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2 overlay-container overlay-bottom">
                                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar7.jpg" alt="">
                                                <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-success"></span>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">Barbara Scott</div>
                                                <div class="font-w400 text-muted">Web Designer</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2 overlay-container overlay-bottom">
                                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar8.jpg" alt="">
                                                <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-warning"></span>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">Andrea Gardner</div>
                                                <div class="font-w400 text-muted">Photographer</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2 overlay-container overlay-bottom">
                                                <img class="img-avatar img-avatar48" src="assets/media/avatars/avatar9.jpg" alt="">
                                                <span class="overlay-item item item-tiny item-circle border border-2x border-white bg-warning"></span>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">Brian Cruz</div>
                                                <div class="font-w400 text-muted">Graphic Designer</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <!-- END Users Navigation -->
                            </div>
                        </div>
                        <!-- END Online Friends -->

                        <!-- Quick Settings -->
                        <div class="block mb-0">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Quick Settings</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                                </div>
                            </div>
                            <div class="block-content">
                                <!-- Quick Settings Form -->
                                <form action="be_pages_dashboard.html" method="POST" onsubmit="return false;">
                                    <div class="form-group">
                                        <p class="font-w600 mb-2">
                                            Online Status
                                        </p>
                                        <div class="custom-control custom-switch mb-1">
                                            <input type="checkbox" class="custom-control-input" id="so-settings-check1" name="so-settings-check1" checked>
                                            <label class="custom-control-label" for="so-settings-check1">Show your status to all</label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <p class="font-w600 mb-2">
                                            Auto Updates
                                        </p>
                                        <div class="custom-control custom-switch mb-1">
                                            <input type="checkbox" class="custom-control-input" id="so-settings-check2" name="so-settings-check2" checked>
                                            <label class="custom-control-label" for="so-settings-check2">Keep up to date</label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <p class="font-w600 mb-1">
                                            Application Alerts
                                        </p>
                                        <div class="custom-control custom-switch mb-1">
                                            <input type="checkbox" class="custom-control-input" id="so-settings-check3" name="so-settings-check3" checked>
                                            <label class="custom-control-label" for="so-settings-check3">Email Notifications</label>
                                        </div>
                                        <div class="custom-control custom-switch mb-1">
                                            <input type="checkbox" class="custom-control-input" id="so-settings-check4" name="so-settings-check4" checked>
                                            <label class="custom-control-label" for="so-settings-check4">SMS Notifications</label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <p class="font-w600 mb-1">
                                            API
                                        </p>
                                        <div class="custom-control custom-switch mb-1">
                                            <input type="checkbox" class="custom-control-input" id="so-settings-check5" name="so-settings-check5" checked>
                                            <label class="custom-control-label" for="so-settings-check5">Enable access</label>
                                        </div>
                                    </div>
                                </form>
                                <!-- END Quick Settings Form -->
                            </div>
                        </div>
                        <!-- END Quick Settings -->
                    </div>
                    <!-- END Overview Tab -->

                    <!-- Sales Tab -->
                    <div class="tab-pane pull-x fade fade-right" id="so-sales" role="tabpanel">
                        <div class="block mb-0">
                            <!-- Stats -->
                            <div class="block-content">
                                <div class="row items-push pull-t">
                                    <div class="col-6">
                                        <div class="font-w700 text-uppercase">Sales</div>
                                        <a class="link-fx font-size-h3 font-w300" href="javascript:void(0)">22.030</a>
                                    </div>
                                    <div class="col-6">
                                        <div class="font-w700 text-uppercase">Balance</div>
                                        <a class="link-fx font-size-h3 font-w300" href="javascript:void(0)">$4.589,00</a>
                                    </div>
                                </div>
                            </div>
                            <!-- END Stats -->

                            <!-- Today -->
                            <div class="block-content block-content-full block-content-sm bg-body-light">
                                <div class="row">
                                    <div class="col-6">
                                        <span class="font-w600 text-uppercase">Today</span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span class="ext-muted">$996</span>
                                    </div>
                                </div>
                            </div>
                            <div class="block-content">
                                <ul class="nav-items push">
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">New sale! + $249</div>
                                                <small class="text-muted">3 min ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">New sale! + $129</div>
                                                <small class="text-muted">50 min ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">New sale! + $119</div>
                                                <small class="text-muted">2 hours ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">New sale! + $499</div>
                                                <small class="text-muted">3 hours ago</small>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- END Today -->

                            <!-- Yesterday -->
                            <div class="block-content block-content-full block-content-sm bg-body-light">
                                <div class="row">
                                    <div class="col-6">
                                        <span class="font-w600 text-uppercase">Yesterday</span>
                                    </div>
                                    <div class="col-6 text-right">
                                        <span class="text-muted">$765</span>
                                    </div>
                                </div>
                            </div>
                            <div class="block-content">
                                <ul class="nav-items push">
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">New sale! + $249</div>
                                                <small class="text-muted">26 hours ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-danger"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">Product Purchase - $50</div>
                                                <small class="text-muted">28 hours ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">New sale! + $119</div>
                                                <small class="text-muted">29 hours ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-danger"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">Paypal Withdrawal - $300</div>
                                                <small class="text-muted">37 hours ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">New sale! + $129</div>
                                                <small class="text-muted">39 hours ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">New sale! + $119</div>
                                                <small class="text-muted">45 hours ago</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="text-dark media py-2" href="javascript:void(0)">
                                            <div class="mr-3 ml-2">
                                                <i class="fa fa-fw fa-circle text-success"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="font-w600">New sale! + $499</div>
                                                <small class="text-muted">46 hours ago</small>
                                            </div>
                                        </a>
                                    </li>
                                </ul>

                                <!-- More -->
                                <div class="text-center">
                                    <a class="btn btn-sm btn-light" href="javascript:void(0)">
                                        <i class="fa fa-arrow-down mr-1"></i> Load More..
                                    </a>
                                </div>
                                <!-- END More -->
                            </div>
                            <!-- END Yesterday -->
                        </div>
                    </div>
                    <!-- END Sales Tab -->
                </div>
            </div>
            <!-- END Side Overlay Tabs -->
        </div>
        <!-- END Side Content -->
    </aside>
    <!-- END Side Overlay -->

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
                <strong>To Initiate Your WeFullFill Wallet Services. Please <a href="{{route('store.index')}}"> Complete Your Registration</a>.</strong>
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
                    Designed by <i class="fa fa-bolt text-danger"></i> <a class="font-w600" href="https://tetralogicx.com" target="_blank">Fantasy Supply Limited</a>
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
