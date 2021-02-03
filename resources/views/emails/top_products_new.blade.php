{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <title>Our Products</title>--}}
{{--    <style>--}}
{{--        .email-body--}}
{{--        {--}}
{{--            color:black;--}}
{{--        }--}}
{{--        .email-content--}}
{{--        {--}}
{{--            /*max-width: 450px;*/--}}
{{--            width : 90%;--}}
{{--        }--}}
{{--        .email-content-detail--}}
{{--        {--}}
{{--            margin: 50px 0;--}}
{{--        }--}}

{{--        .wrap {--}}
{{--            padding-left: 20px;--}}
{{--            background-color: #7daa40 !important;--}}
{{--            color: #ffffff !important;--}}
{{--            padding: 1px 20px;--}}
{{--        }--}}

{{--        .wrap .right{--}}
{{--            text-align: right !important;--}}
{{--        }--}}

{{--        .wrap .left{--}}
{{--            text-align: left !important;--}}
{{--        }--}}

{{--        a.title {--}}
{{--            text-decoration: none;--}}
{{--            color: #7daa40 !important;--}}
{{--            font-size: 18px;--}}
{{--            margin-left: 5px;--}}
{{--        }--}}

{{--        a.title:hover{--}}
{{--            text-decoration: none;--}}
{{--            color: #7daa40 !important;--}}
{{--            font-size: 18px;--}}
{{--            margin-left: 5px;--}}

{{--        }--}}

{{--        a.title:active{--}}
{{--            text-decoration: none;--}}
{{--            color: #7daa40 !important;--}}
{{--            font-size: 18px;--}}
{{--            margin-left: 5px;--}}

{{--        }--}}

{{--        .wrapper{--}}
{{--            display: flex;--}}
{{--            flex-wrap: wrap;--}}
{{--            width: 100%;--}}
{{--        }--}}

{{--        .product_div{--}}
{{--            margin: 10px 0;--}}
{{--            box-sizing: border-box;--}}
{{--            padding: 10px;--}}
{{--            width: 33.3%;--}}
{{--        }--}}

{{--        .product_div p{--}}
{{--            text-align: left;--}}
{{--        }--}}

{{--        .product_price{--}}
{{--            color: #ff0000db;--}}
{{--            font-weight: bold;--}}

{{--        }--}}

{{--        .product_div .product_img{--}}
{{--            width: 100%;--}}
{{--            height: auto;--}}
{{--        }--}}

{{--        .inner{--}}
{{--            padding: 15px;--}}
{{--            border: 1px solid #ccc;--}}
{{--            border-radius: 5px;--}}
{{--            -webkit-box-shadow: 4px 4px 5px 1px rgba(0,0,0,0.75);--}}
{{--            -moz-box-shadow: 4px 4px 5px 1px rgba(0,0,0,0.75);--}}
{{--            box-shadow: 4px 4px 5px 1px rgba(0,0,0,0.75);--}}
{{--        }--}}

{{--        .product-btn{--}}
{{--            width: 100%;--}}
{{--            background-color: #1f6fb2;--}}
{{--            color: white;--}}
{{--            padding: 15px 0;--}}
{{--            border-radius: 5px;--}}
{{--            border: 1px solid #1f6fb2;--}}
{{--            font-size: 16px;--}}
{{--            display: block;--}}
{{--            text-decoration: none;--}}
{{--        }--}}

{{--        .clear{--}}
{{--            content: "";--}}
{{--            display: table;--}}
{{--            clear: both;--}}

{{--        }--}}



{{--        @media (max-width: 742px) {--}}
{{--            .product_div {--}}
{{--                width: 50%;--}}
{{--            }--}}
{{--        }--}}
{{--        @media (max-width: 570px) {--}}
{{--            .email_btn--}}
{{--            {--}}
{{--                padding:15px 30px !important;--}}
{{--                font-size:18px !important;--}}
{{--            }--}}

{{--            .product_div {--}}
{{--                width: 100%;--}}
{{--            }--}}
{{--        }--}}
{{--        @media (max-width: 430px) {--}}
{{--            .email_btn {--}}
{{--                padding: 15px 20px !important;--}}
{{--                font-size: 12px !important;--}}
{{--            }--}}
{{--        }--}}
{{--        @media (max-width: 400px) {--}}
{{--            .email_btn {--}}
{{--                padding: 15px 10px !important;--}}
{{--                font-size: 12px !important;--}}
{{--            }--}}
{{--            span--}}
{{--            {--}}
{{--                font-size:18px !important ;--}}
{{--            }--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body style="margin: 0">--}}

{{--<div class="email-body" style="padding: 20px;max-width: 80%;margin: auto; font-family: cursive;">--}}
{{--    <div class="email-contaner" style="border: 2px solid #7daa40;padding: 25px;">--}}
{{--        <div class="email-content" style="margin: auto;  text-align: center; ">--}}
{{--            <div class="email-logo">--}}
{{--                <img src="https://cdn.shopify.com/s/files/1/0370/7361/7029/files/image_3.png?v=1585895317" alt="Wefullfill" style="width: 35%">--}}
{{--            </div>--}}

{{--            <div class="email-content-detail" style="margin: 50px 0;">--}}
{{--                <h1 class="email-title" style="margin: 0;margin-bottom: 30px;font-size: 34px;">{{ $template->subject }}</h1>--}}
{{--                <p class="email-message-1" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53;" >{{ $template->body }} </p>--}}
{{--                <hr>--}}

{{--                <div class="" style="width: 100%">--}}
{{--                    <div style=" padding-left: 20px; background-color: #7daa40 !important;color: #ffffff !important;padding: 1px 20px;">--}}
{{--                        <div style=" text-align: left !important;">--}}
{{--                            <h3 style="color: #ffffff; margin-right: 5px;">Our Top Products</h3>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div style="width: 100%;">--}}
{{--                    @foreach($top_products_stores as $product)--}}
{{--                        @php--}}
{{--                            $prods = json_decode($template->products);--}}
{{--                        @endphp--}}
{{--                        @if(in_array($product->id, $prods))--}}
{{--                        <div style=" margin: 10px 0;--}}
{{--                            box-sizing: border-box;--}}
{{--                            padding: 10px;--}}
{{--                            float: right;--}}
{{--                            display: inline;--}}
{{--                            width: 33%;--}}
{{--                            ">--}}
{{--                            <div style="    padding: 15px;--}}
{{--                                                border: 1px solid #ccc;--}}

{{--                                                border-radius: 5px;--}}
{{--                                                -webkit-box-shadow: 4px 4px 5px 1px rgba(0,0,0,0.75);--}}
{{--                                                -moz-box-shadow: 4px 4px 5px 1px rgba(0,0,0,0.75);--}}
{{--                                                box-shadow: 4px 4px 5px 1px rgba(0,0,0,0.75);--}}
{{--                                                text-align: center">--}}
{{--                                @foreach($product->has_images()->orderBy('position')->get() as $index => $image)--}}
{{--                                    @if($index == 0)--}}
{{--                                        @if($image->isV == 0)--}}

{{--                                            <img style=" width: 70%;height: auto;"  src="{{asset('images')}}/{{$image->image}}">--}}
{{--                                        @else--}}
{{--                                            <img style=" width: 70%;height: auto;"  src="{{asset('images/variants')}}/{{$image->image}}">--}}
{{--                                        @endif--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                                <p><a href="{{route('store.product.wefulfill.show',$product->id)}}" class="title">{{$product->title}}</a></p>--}}
{{--                                <p class=" color: #ff0000db;--}}
{{--                                                font-weight: bold;">From ${{ $product->price }}</p>--}}
{{--                                <a href="{{route('store.product.wefulfill.show',$product->id)}}" style="  width: 100%;--}}
{{--                                            background-color: #1f6fb2;--}}
{{--                                            color: white;--}}
{{--                                            padding: 15px 0;--}}
{{--                                            border-radius: 5px;--}}
{{--                                            border: 1px solid #1f6fb2;--}}
{{--                                            font-size: 16px;--}}
{{--                                            display: block;--}}
{{--                                            text-decoration: none;">View Product</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        @endif--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--                <br><br><br><br>--}}
{{--                <hr>--}}
{{--                <div class="clear"></div>--}}
{{--                <div>--}}
{{--                    <a href="{{ route('store.product.wefulfill') }}" target="_blank" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">View Products</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}



{{--<div class="email-footer" style=" padding: 25px 10px; color: white; ">--}}

{{--    <div class="email-footer-caption">--}}
{{--        <ul style=" color: white; list-style: none; padding: 0 ;  margin-top: 25px;text-align: center; ">--}}
{{--            <li class="site-name" style="width: max-content; display: inline-block; margin-right: 15px;padding-right: 15px;border-right: 1px solid white;"><a href="" style="color: white;text-decoration: none;">WeFullFill</a></li>--}}
{{--            <li class="dalls" style="width: max-content; display: inline-block; margin-right: 15px; padding-right: 15px; border-right: 1px solid white;">ROOM 2103 TUNG CHIU COMMERCIAL CENTRE 193,LOCKHART ROAD WAN</li>--}}
{{--            <li class="country" style="width: max-content; display: inline-block;">China</li>--}}
{{--        </ul>--}}
{{--    </div>--}}

{{--</div>--}}

{{--</body>--}}
{{--</html>--}}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Reward Points</title>

    <meta  name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0," />

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'/>

    <style type="text/css">


        html {
            width: 100%;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        img {
            display: block !important;
            border: 0;
            -ms-interpolation-mode: bicubic;
        }
        .ReadMsgBody {
            width: 100%;
        }
        .ExternalClass {
            width: 100%;
        }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        .images {
            display: block !important;
            width: 100% !important;
        }
        .MsoNormal {
            font-family:Open Sans,Arial, Helvetica Neue, Helvetica, sans-serif !important;
        }
        p {
            margin: 0 !important;
            padding: 0 !important;
        }
        .display-button td,
        .display-button a {
            font-family:Open Sans, Arial, Helvetica Neue, Helvetica, sans-serif !important;
        }
        .display-button a:hover {
            text-decoration: none !important;
        }

        /* MEDIA QUIRES */


        @media only screen and (max-width:640px) {
            body {
                width:auto !important;
            }
            table[class=display-width] {
                width:100% !important;
            }
            table[class=nulltable] {
                display:none !important;
            }
            .null {
                display:none !important;
            }
            .hide-height {
                height:0 !important;
            }
            .common-height {
                height:40px !important;
            }
            .hide {
                display:none !important;
            }
            .remove {
                border:none !important;
            }
            .choose {
                padding-right:0 !important;
            }
            .remove-bar {
                display:none !important;
            }
            .phone {
                width:55% !important;
            }
            .follow {
                width:15px !important;
            }
            .padding-right{
                padding-right: 9px;
            }
        }

        @media only screen and (max-width:480px) {
            table[class=display-width] table {
                width:100% !important;
            }
            table[class=display-width] .button-width .display-button {
                width:auto !important;
            }
            .menu {
                font-size:12px !important;
            }
            table[class=display-width] .phone {
                width:55% !important;
            }
            .padding-right{
                padding-right: 6px;
            }
        }

        @media only screen and (max-width:350px) {
            table[class=display-width] .phone {
                width:auto !important;
            }
        }

    </style>

</head>

<body>

<!-- MENU STARTS -->

<table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center">
            <table align="center" bgcolor="#7daa40" border="0" cellpadding="0" cellspacing="0" class="display-width" width="680">
                <tr>
                    <td align="center" style="padding:0 30px;">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="600">
                            <tr>
                                <td align="center">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                                        <tr>
                                            <td height="15"></td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="25%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                    <tr>
                                                        <td align="center" valign="middle">
                                                            <a href="#" style="color:#ffffff;text-decoration:none;">
                                                                <img src="https://wefullfill.com/wp-content/uploads/2021/01/wefullfill-final-logo-change-white_540x.png" alt="150x50" width="150"/>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="line-height:20px;" height="20" width="1"></td>
                                        </tr>

                                        <tr>
                                            <td align="center">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="330" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                    <tr>
                                                        <td align="right">
                                                            <table align="right" border="0" cellspacing="0" cellpadding="0" class="display-width" width="100%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                <tr>
                                                                    <td align="center">
                                                                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:auto !important; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                            <tr>
                                                                                <td>
                                                                                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="160" class="display-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                                        <tr>
                                                                                            <td align="center">
                                                                                                <table align="center" border="0" cellspacing="0" cellpadding="0" class="display-width" width="100%" style="width:auto !important; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                                                    <tr>
                                                                                                        <td align="left" valign="middle" class="MsoNormal menu" style="color:#FFFFFF;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;font-weight:600;letter-spacing:1px;">
                                                                                                            <a href="https://wefullfill.com" style="color:#FFFFFF;text-decoration:none;">
                                                                                                                www.wefullfill.com
                                                                                                            </a>
                                                                                                        </td>
                                                                                                        <td align="center" width="70" valign="middle" class="MsoNormal remove-bar" style="color:#FFFFFF;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;font-weight:500;letter-spacing:1px;text-transform:capitalize;">
                                                                                                            |
                                                                                                        </td>
                                                                                                        <td align="right" valign="middle" class="MsoNormal padding-right" style="color:#FFFFFF;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;font-weight:600;letter-spacing:1px;text-transform:capitalize;">
                                                                                                            support@wefullfill.com
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="15"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>

<!-- MENU ENDS -->

<!-- THREE COLUMN : REWARD POINTS STARTS -->

<table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center">
            <table align="center" bgcolor="#7daa40" border="0" cellpadding="0" cellspacing="0" class="display-width" width="680">
                <tr>
                    <td align="center" style="padding:0 30px;">
                        <table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="display-width" style="border-top-left-radius:5px;border-top-right-radius:5px;" width="600">
                            <tr>
                                <td height="40"></td>
                            </tr>
                            <tr>
                                <td align="center" style="padding:0 20px;">
                                    <table align="center"  border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                                        <tr>
                                            <td align="left" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:18px;font-weight:600;letter-spacing:1px;line-height:18px;text-transform:capitalize;">
                                                {{ $template->subject }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="20"></td>
                                        </tr>
                                        <tr>
                                            <td align="left">
                                                <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                                                    <tr>
                                                        <td align="left" class="MsoNormal" style="color:#666666;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;line-height:24px;">
                                                            {{ $template->body }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="30" style="border-bottom:1px dashed #dddddd;"></td>
                                        </tr>
                                        <tr>
                                            <td height="30"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:18px;font-weight:700;letter-spacing:1px;line-height:26px;text-transform:uppercase;">
                                                By Choosing Below Product
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="10"></td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <img src="http://pennyblacktemplates.com/demo/notifications/images/layout-2/app-release/images/90x5.png" alt="90x5" width="90" style="color:#444444;" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="30"></td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <table align="center"  border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                                                    <tr>
                                                        <td width="180">
                                                            @foreach($top_products_stores as $product)
                                                                @php
                                                                    $prods = json_decode($template->products);
                                                                @endphp
                                                                @if(in_array($product->id, $prods))
                                                                    <!-- TABLE  -->
                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width" width="180" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                        <tr>
                                                                            <td height="20"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="center">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%" style="width:auto !important;">
                                                                                    <tr>
                                                                                        <td align="center" width="150">
                                                                                            @foreach($product->has_images()->orderBy('position')->get() as $index => $image)
                                                                                                @if($index == 0)
                                                                                                    @if($image->isV == 0)

                                                                                                        <img  src="{{asset('images')}}/{{$image->image}}" alt="180x180x1" width="180" style="border-radius:5px;color:#444444;width:100%;">
                                                                                                    @else
                                                                                                        <img  src="{{asset('images/variants')}}/{{$image->image}}" alt="180x180x1" width="180" style="border-radius:5px;color:#444444;width:100%;">
                                                                                                    @endif
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td height="20"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="center" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:16px;font-weight:600;letter-spacing:1px;line-height:20px;text-transform:capitalize;">
                                                                                <a style="text-decoration: none;color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:16px;font-weight:600;letter-spacing:1px;line-height:20px;text-transform:capitalize;" href="{{route('store.product.wefulfill.show',$product->id)}}">{{$product->title}}</a>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td height="5"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="center" class="MsoNormal" style="color:#666666;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;line-height:14px;">
                                                                                From ${{ $product->price }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td height="20"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="center" class="button-width">
                                                                                <table align="center" bgcolor="#7daa40" border="0" cellspacing="0" cellpadding="0" class="display-button" style="border-radius:5px;"> <!-- USING TABLE AS BUTTON -->
                                                                                    <tr>
                                                                                        <td align="center" valign="middle" class="MsoNormal" style="color:#ffffff;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;font-weight:bold;letter-spacing:1px;padding:7px 15px;text-transform:uppercase;">
                                                                                            <a href="{{route('store.product.wefulfill.show',$product->id)}}" style="color:#ffffff;text-decoration:none;">View Product</a>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </table>

                                                                    <!--[if gte mso 9]>
                                                                    </td><td width="8">
                                                                    <![endif]-->

                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="10" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                        <tr>
                                                                            <td style="line-height:40px;" height="40"></td>
                                                                        </tr>
                                                                    </table>

                                                                    <!--[if gte mso 9]>
                                                                    </td><td width="180">
                                                                    <![endif]-->
                                                                @endif
                                                            @endforeach



                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="40" style="border-bottom:1px solid #eeeeee;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- THREE COLUMN : REWARD POINTS ENDS -->


<!-- FOOTER STARTS -->

<table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" >
            <table align="center"  border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                <tr>
                    <td align="center">
                        <table align="center" bgcolor="#7daa40" border="0" cellpadding="0" cellspacing="0" class="display-width" width="680">
                            <tr>
                                <td align="center" style="padding:0 30px;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="600">
                                        <tr>
                                            <td height="50"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" style="padding:0 10px;">
                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                                                    <tr>
                                                        <td>

                                                            <!-- TABLE LEFT -->

                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="43%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="center">
                                                                        <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width" style="width:auto;">
                                                                            <tr>
                                                                                <td>
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%" style="width:auto !important;">
                                                                                        <tr>
                                                                                            <td align="center" class="MsoNormal" style="color:#FFFFFF;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;letter-spacing:1px;line-height:24px;">
                                                                                                Copyright &copy; 2021, Wefullfill
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="50"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- FOOTER ENDS -->

</body>
</html>



