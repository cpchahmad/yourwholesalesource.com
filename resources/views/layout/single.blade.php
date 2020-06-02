
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
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/js/plugins/dropzone/dist/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/magnific-popup/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/select2/css/select2.css')}}">

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>

</head>
<body>
<div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">

    @include('layout.single_sidebar')
    <main id="main-container">
        @include('flash_message.message')
        @yield('content')
    </main>

    <footer id="page-footer" class="bg-body-light">
        <div class="content py-3">
            <div class="row font-size-sm">
                <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
                    Developed for <i class="fa fa-heart text-danger"></i> by <a class="font-w600" href="" target="_blank">Fantasy Supplier</a>
                </div>
            </div>
        </div>
    </footer>

</div>



<script src="{{ asset('assets/js/oneui.core.min.js') }}"></script>
<script src="{{ asset('assets/js/oneui.app.min.js') }}"></script>
<script src="{{ asset('js/single-store.js') }}"></script>
<script src="{{ asset('assets/js/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/dropzone/dist/dropzone.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
<script src="{{asset('assets/js/plugins/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/select2/js/select2.min.js')}}"></script>

<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>



<script>jQuery(function(){ One.helpers(['summernote','magnific-popup','table-tools-sections','masked-inputs','select2']); });</script>

<div class="pre-loader">
    <div class="loader">
    </div>
</div>
</body>
</html>
