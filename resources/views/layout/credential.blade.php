
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
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>

</head>
<body>

<div id="page-container">

    <!-- Main Container -->
    <main id="main-container">
        @include('flash_message.message')
        <div class="bg-image" style="background-image: url('{{ asset('assets/media/photos/photo6@2x.jpg') }}');">
            <div class="hero-static bg-white-95">
                <div class="content">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
</div>




<script src="{{ asset('assets/js/oneui.core.min.js') }}"></script>
<script src="{{ asset('assets/js/oneui.app.min.js') }}"></script>


</body>
</html>
