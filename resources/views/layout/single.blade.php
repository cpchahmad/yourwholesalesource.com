
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Dropship</title>

    <meta name="description" content="OneUI - Admin Dashboard Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicons/favicon.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicons/favicon-96x96.png') }}" sizes="96x96">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicons/favicon-160x160.png') }}" sizes="160x160">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicons/favicon-192x192.png') }}" sizes="192x192">

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/img/favicons/apple-touch-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/img/favicons/apple-touch-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/img/favicons/apple-touch-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/favicons/apple-touch-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/img/favicons/apple-touch-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/favicons/apple-touch-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/img/favicons/apple-touch-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/img/favicons/apple-touch-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon-180x180.png') }}">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Web fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/slick/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/slick/slick-theme.min.css') }}">

    <!-- Bootstrap and OneUI CSS framework -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/summernote/summernote.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/dropzonejs/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

{{--    <link rel="stylesheet" href="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.css') }}">--}}


<!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
    <!-- END Stylesheets -->
</head>
<body>
<!-- Page Container -->
<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed">
    <!-- Side Overlay-->

    <!-- Sidebar -->
@include('layout.single_sidebar')
<!-- END Sidebar -->

    <!-- Header -->
    <header id="header-navbar" class="content-mini content-mini-full">
        <!-- Header Navigation Right -->
        <ul class="nav-header pull-right">
            <li>
                <div class="btn-group">
                    <button class="btn btn-default btn-image dropdown-toggle" data-toggle="dropdown" type="button">
                        <img src="{{asset('assets/img/avatars/avatar10.jpg')}}" alt="Avatar">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header">Profile</li>
                        <li>
                            <a tabindex="-1" href="{{route('logout')}}">
                                <i class="si si-logout pull-right"></i>Log out
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
            </li>
        </ul>
        <!-- END Header Navigation Right -->

        <!-- Header Navigation Left -->
        <ul class="nav-header pull-left">
            <li class="hidden-md hidden-lg">
                <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
                    <i class="fa fa-navicon"></i>
                </button>
            </li>
            <li class="hidden-xs hidden-sm">
                <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
            </li>

            <li class="visible-xs">
                <!-- Toggle class helper (for .js-header-search below), functionality initialized in App() -> uiToggleClass() -->
                <button class="btn btn-default" data-toggle="class-toggle" data-target=".js-header-search" data-class="header-search-xs-visible" type="button">
                    <i class="fa fa-search"></i>
                </button>
            </li>
            <li class="js-header-search header-search">
                <form class="form-horizontal" action="base_pages_search.html" method="post">
                    <div class="form-material form-material-primary input-group remove-margin-t remove-margin-b">
                        <input class="form-control" type="text" id="base-material-text" name="base-material-text" placeholder="Search..">
                        <span class="input-group-addon"><i class="si si-magnifier"></i></span>
                    </div>
                </form>
            </li>
        </ul>
        <!-- END Header Navigation Left -->
    </header>
    <main id="main-container">
        @include('flash_message.message')
        @yield('content')
    </main>
</div>
<!-- END Page Container -->
<!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/core/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/js/core/jquery.scrollLock.min.js') }}"></script>
<script src="{{ asset('assets/js/core/jquery.appear.min.js') }}"></script>
<script src="{{ asset('assets/js/core/jquery.countTo.min.js') }}"></script>
<script src="{{ asset('assets/js/core/jquery.placeholder.min.js') }}"></script>
<script src="{{ asset('assets/js/core/js.cookie.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Page Plugins -->
<script src="{{ asset('assets/js/plugins/slick/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/summernote/summernote.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzonejs/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<!-- Page JS Code -->
<script src="{{ asset('assets/js/pages/base_pages_dashboard.js') }}"></script>
<script src="{{ asset('js/single-store.js') }}"></script>

<script>
    jQuery(function () {
        App.initHelpers('slick');
    });
    jQuery(function () {
        // Init page helpers (Table Tools helper)
        App.initHelpers('table-tools');
    });
    jQuery(function () {
        // Init page helpers (Summernote + CKEditor + SimpleMDE plugins)
        App.initHelpers(['maxlength', 'select2', 'tags-inputs', 'summernote', 'appear', 'appear-countTo']);
    });
</script>
<div class="pre-loader">
    <div class="loader">
    </div>
</div>
</body>
</html>
