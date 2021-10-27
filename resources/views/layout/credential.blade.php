
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Awareness Drop Shipping</title>

    <meta name="description" content="YourWholesaleSource 2020 created by TetraLogicx Pvt. Limited.">
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
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/js/plugins/dropzone/dist/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/magnific-popup/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/select2/css/select2.css')}}">

    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" integrity="sha256-aa0xaJgmK/X74WM224KMQeNQC2xYKwlAt08oZqjeF0E=" crossorigin="anonymous" />


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


</head>
<body>

<div id="page-container">

    <!-- Main Container -->
    <main id="main-container">
{{--        @include('flash_message.message')--}}
        <div class="bg-image" style=" background:linear-gradient(0deg, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://yourWholesaleSource.com/wp-content/uploads/2018/07/Home@2x.jpg');">
            <div class="hero-static">
                <div class="content">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="logo mb2 d-inline-block text-center justify-content-center">
                                <img style="width: 100%;max-width: 130px;vertical-align: sub;margin-right: 10px" class="d-inline-block" src="{{ asset('assets/wholesale.png') }}" alt="">
                                <h1 class="d-inline-block text-white">Awareness Drop Shipping</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 offset-md-4 leftSection mt-5">
                            <div class="left">
                                @yield('content')
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </main>
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


</body>
</html>
