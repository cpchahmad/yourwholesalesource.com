<!DOCTYPE html>
<html>
<head>
    <title>News Email</title>
</head>
<body style="margin: 0">
<style>
    .email-body
    {
        color:black;
    }
    .email-content
    {
        /*max-width: 450px;*/
        width : 90%;
    }
    .email-content-detail
    {
        margin: 50px 0;
    }
    @media (max-width: 570px) {
        .email_btn
        {
            padding:15px 30px !important;
            font-size:18px !important;
        }
    }
    @media (max-width: 430px) {
        .email_btn {
            padding: 15px 20px !important;
            font-size: 12px !important;
        }
    }
    @media (max-width: 400px) {
        .email_btn {
            padding: 15px 10px !important;
            font-size: 12px !important;
        }
        span
        {
            font-size:18px !important ;
        }
    }
</style>


<div class="email-body" style="padding: 20px;max-width: 80%;margin: auto; font-family: cursive;">
    <div class="email-contaner" style="border: 2px solid #7daa40;padding: 25px;">
        <div class="email-content" style="margin: auto;  text-align: center; ">
            <div class="email-logo">
                <img src="https://cdn.shopify.com/s/files/1/0370/7361/7029/files/image_3.png?v=1585895317" alt="Wefullfill" style="width: 35%">
            </div>

            <div class="email-content-detail" style="margin: 50px 0;">
                <h1 class="email-title" style="margin: 0;margin-bottom: 30px;font-size: 34px;">{{ $template->subject }}</h1>
                <p class="email-message-1" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53;" >{{ $template->body }} </p>

                <img style="width: 100%; height: auto;" src="{{asset('ticket-attachments')}}/{{$template->banner}}" alt="">
            </div>
        </div>
    </div>
</div>

<div class="email-footer" style=" padding: 25px 10px; color: white; ">

    <div class="email-footer-caption">
        <ul style=" color: white; list-style: none; padding: 0 ;  margin-top: 25px;text-align: center; ">
            <li class="site-name" style="width: max-content; display: inline-block; margin-right: 15px;padding-right: 15px;border-right: 1px solid white;"><a href="" style="color: white;text-decoration: none;">WeFullFill</a></li>
            <li class="dalls" style="width: max-content; display: inline-block; margin-right: 15px; padding-right: 15px; border-right: 1px solid white;">ROOM 2103 TUNG CHIU COMMERCIAL CENTRE 193,LOCKHART ROAD WAN</li>
            <li class="country" style="width: max-content; display: inline-block;">China</li>
        </ul>
    </div>

</div>

</body>
</html>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>News Email</title>

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
        .phone-1 {
            width:140px !important;
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
                padding-right: 8px !important;
            }
            .phone-1 {
                width:100% !important;
            }

            table[class=display-width] .phone-1 {
                width:100% !important;
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
            .phone {
                width:55% !important;
            }
            table[class=display-width] .phone {
                width:55% !important;
            }
            .padding-right{
                padding-right: 1px;
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
        <td height="80"></td>
    </tr>
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
                                                                                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="180" class="display-width" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                                        <tr>
                                                                                            <td align="center">
                                                                                                <table align="center" border="0" cellspacing="0" cellpadding="0" class="display-width" width="100%" style="width:auto !important; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                                                    <tr>

                                                                                                        <td align="left" valign="middle" class="MsoNormal" style="color:#FFFFFF;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;font-weight:600;letter-spacing:1px;">
                                                                                                            <a href="http://www.wefullfill.com" style="color:#FFFFFF;text-decoration:none;">
                                                                                                                www.wefullfill.com
                                                                                                            </a>
                                                                                                        </td>
                                                                                                        <td align="center" width="40" valign="middle" class="MsoNormal remove-bar" style="color:#FFFFFF;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;font-weight:500;letter-spacing:1px;text-transform:capitalize;">
                                                                                                            |
                                                                                                        </td>
                                                                                                        <td align="left" valign="middle" class="MsoNormal padding-right" style="color:#FFFFFF;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;font-weight:600;letter-spacing:1px;text-transform:capitalize;">
                                                                                                            support@wefullfill.com
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>

                                                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="1" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; mso-hide:all;">
                                                                                        <tr>
                                                                                            <td style="line-height:10px;" height="10"></td>
                                                                                        </tr>
                                                                                    </table>

                                                                                    <table align="left" border="0" cellspacing="0" cellpadding="0" width="145"  class="phone-1" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; ">
                                                                                        <tr>
                                                                                            <td align="center">
                                                                                                <table align="center" border="0" cellspacing="0" cellpadding="0" class="display-width" width="100%" style="width:auto !important;">
                                                                                                    <tr>
                                                                                                        <td align="left">
                                                                                                            <table align="left" border="0" cellspacing="0" cellpadding="0" class="display-width" width="100%">
                                                                                                                <tr>

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

<!-- NEW PRODUCT STARTS -->

<table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center">
            <table align="center" bgcolor="#7daa40" border="0" cellpadding="0" cellspacing="0" class="display-width" width="680">
                <tr>
                    <td align="center" style="padding:0 30px;">
                        <table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="display-width" style="border-top-left-radius:5px; border-top-right-radius:5px;" width="500">
                            <tr>
                                <td height="40"></td>
                            </tr>
                            <tr>
                                <td align="center" style="padding:0 30px;">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                                        <tr>
                                            <td align="left" width="600">
                                                <img src="{{asset('ticket-attachments')}}/{{$template->banner}}" alt="540x250" width="540" style="border-radius:5px;color:#444444;width:100%;"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="20"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:18px;font-weight:700;letter-spacing:1px;line-height:26px;text-transform:uppercase;">
                                                {{ $template->subject }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="15"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="MsoNormal" style="color:#666666;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;line-height:24px;">
                                                {{ $template->body }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td height="20"></td>
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

<!-- NEW PRODUCT ENDS -->

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
                                                                                            <td align="center" class="MsoNormal" style="color:#FFFFFF; font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;letter-spacing:1px;line-height:24px;">
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
    <tr>
        <td height="80"></td>
    </tr>
</table>

<!-- FOOTER ENDS -->

</body>
</html>










