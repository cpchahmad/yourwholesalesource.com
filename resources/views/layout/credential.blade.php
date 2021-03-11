
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>WholeSaleSource</title>

    <meta name="description" content="WholeSaleSource 2020 created by TetraLogicx Pvt. Limited.">
    <meta name="author" content="tetralogicx">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">


    <link rel="shortcut icon" href="{{ asset('assets/wholesale.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/wholesale.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/wholesale.png') }}">

    <meta property="og:title" content="WholeSaleSource">
    <meta property="og:site_name" content="WholeSaleSource">
    <meta property="og:description" content="WholeSaleSource">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    @include('inc.font')
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>

</head>
<body>

<div id="page-container">

    <!-- Main Container -->
    <main id="main-container">
{{--        @include('flash_message.message')--}}
        <div class="bg-image" style="background-image: url('https://yourwholesalesource.com/wp-content/uploads/2018/07/Home@2x.jpg');">
            <div class="hero-static">
                <div class="content">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="logo mb2 d-inline-block text-center justify-content-center">
                                <img style="width: 100%;max-width: 77px;vertical-align: sub;margin-right: 10px" class="d-inline-block" src="{{ asset('assets/wholesale.png') }}" alt="">
                                <h1 class="d-inline-block text-white">WholeSaleSource</h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 leftSection">
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


</body>
</html>
