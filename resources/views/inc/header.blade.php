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
    <link rel="stylesheet" href="{{asset('assets/js/plugins/flatpickr/flatpickr.css')}}">
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

        @media(max-width: 590px) {
            .section-status-mobile {
                display: block !important;
            }

            .status-section {
                display: none;
            }

        }

        .shipping-mark-body {
            background-color: #7daa40;
            padding: 70px;
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

        .loading-snipper{
            background-image: url('{{ asset('assets/spinner.gif') }}');
            width: 100%;
            height: auto;
        }
    </style>

</head>

