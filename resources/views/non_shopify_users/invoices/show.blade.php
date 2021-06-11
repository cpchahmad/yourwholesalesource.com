<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Invoice</title>

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
        .report-pdf-btn:hover {
            cursor: pointer;
        }

        /* MEDIA QUIRES */


        @media only screen and (max-width:640px) {
            body {
                width:auto !important;
            }
            table[class=display-width] {
                width:100% !important;
            }
            table[class=display-width-4] {
                width:560px !important;
            }
            table[class=display-width-5] {
                width:48% !important;
            }

            table[class=display-width-menu] {
                width:16% !important;
            }
            table[class=display-width-1] {
                width:37% !important;
            }
            table[class=display-width-2] {
                width:39% !important;
            }
            table[class=nulltable] {
                display:none !important;
            }
            .null {
                display:none !important;
            }
            .hide-height-1 {
                height:0 !important;
            }
            .common-height {
                height:40px !important;
            }
            .hide {
                display:none !important;
            }
            .title-width-1 {
                width:120px !important;
            }
            td[class=title-width] {
                width:100px !important;
            }
            .follow {
                width:15px !important;
            }
            .resp-padding{padding:0 10px !important;}
        }

        @media only screen and (max-width:639px) {
            body {
                width:auto !important;
            }
            table[class=display-width] {
                width:100% !important;
            }
            table[class=display-width-menu] {
                width:100% !important;
            }
            table[class=display-width-1] {
                width:100% !important;
            }
            table[class=display-width-2] {
                width:100% !important;
            }
            table[class=display-width-5] {
                width:100% !important;
            }
            table[class=display-width-4] {
                width:100% !important;
            }
            table[class=nulltable] {
                display:none !important;
            }
            .bill {
                width:40% !important;border-bottom:3px solid #333333;"
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
            .follow {
                width:15px !important;
            }

            .icon64 {
                height:40px !important;
            }
        }


        @media only screen and (max-width:480px) {
            table[class=display-width] table {
                width:100% !important;
            }
            table[class=display-width] .button-width .display-button {
                width:auto !important;
            }
            table[class=display-width] .bill {
                width:50% !important;border-bottom:3px solid #333333;"
            }
        }

        @media only screen and (max-width:400px) {
            .product {
                font-size:12px !important;
            }
            .bill {
                width:50% !important;border-bottom:3px solid #333333;"
            }
        }

        @media only screen and (max-width:380px) {
            .product {
                font-size:10px !important;
            }
            .product-width {
                width:11px !important;
            }
            .menu {
                font-size:10px !important;
            }
            .item {
                font-size:12px !important;
            }
            .resp-padding{padding:0 20px !important;}
        }

        @media only screen and (max-width:350px) {

            .item {
                font-size:11px !important;
            }
        }

        @media only screen and (min-width:330px) and (max-width:350px) {
            td[class=title-width] {
                width:auto !important;
            }
            .total {
                width:200px !important;
            }
            .item {
                font-size:12px !important;
            }
            .resp-padding{padding:0 10px !important;}
        }

        @media only screen and (min-width:300px) and (max-width:330px) {
            td[class=title-width] {
                width:auto !important;
            }
            .total {
                width:200px !important;
            }
            .item {
                font-size:11px !important;
            }
            .date {
                font-size:10px !important;
            }
            .resp-padding{padding:0 5px !important;}
        }

    </style>

</head>

<body>

<table align="center" bgcolor="#d26a5c" border="0" cellspacing="0" cellpadding="0" class="display-button" style="border-radius:5px;margin-top:40px;margin-bottom:40px;"> <!-- USING TABLE AS BUTTON -->
    <tbody>
        <tr>
            <td align="center" bgcolor="#d26a5c" valign="middle" class="MsoNormal" style="color:#ffffff;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;font-weight:bold;letter-spacing:1px;padding:7px 15px;text-transform:uppercase;">
                <a class="report-pdf-btn" style="color:#ffffff;text-decoration:none;">Download PDF</a>
            </td>
        </tr>
    </tbody>
</table>

<div id="pdfDownload">
    <!-- MENU STARTS -->

    <table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
        <tr>
            <td align="center">
                <table align="center" bgcolor="#7daa40" border="0" cellpadding="0" cellspacing="0" class="display-width" width="680">
                    <tr>
                        <td height="80"></td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:0 30px;">
                            <table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="display-width" style="border-top-left-radius:5px; border-top-right-radius:5px;" width="600">
                                <tr>
                                    <td height="30"></td>
                                </tr>
                                <tr>
                                    <td align="center" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:25px;font-weight:600;letter-spacing:1px;line-height:18px;text-transform:uppercase;">
                                        Top-up Invoice
                                    </td>
                                </tr>
                                <tr>
                                    <td height="30"></td>
                                </tr>
                                <tr>
                                    <td align="left">
                                        <table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="display-width-4" width="100%">
                                            <tr>
                                                <td class="resp-padding" style="padding:0 30px;">
                                                    <!--TABLE LEFT-->
                                                    <table align="left" border="0" cellspacing="0" cellpadding="0" class="display-width-menu" width="16%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                        <tbody>

                                                        </tbody>
                                                    </table>

                                                    <!--TABLE MIDDLE-->
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" width="12" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                        <tbody>
                                                        <tr>
                                                            <td height="20"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <!--TABLE CENTER-->
                                                    <table align="left" border="0" cellspacing="0" cellpadding="0" class="display-width-1" width="30%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                        <tbody>
                                                        <tr>
                                                            <td align="left" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;font-weight:600;letter-spacing:1px;line-height:15px;">
                                                                Awareness Drop Shipping
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="10"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left" class="MsoNormal" style="color:#666666;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:12px;letter-spacing:1px;line-height:24px;text-transform:uppercase;">
                                                                www.Awareness Drop Shipping.com <br>
                                                                support@Awareness Drop Shipping.com
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <!--TABLE MIDDLE-->
                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="1" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                        <tbody>
                                                        <tr>
                                                            <td width="1" height="20"></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <!--TABLE RIGHT-->
                                                    <table align="right" border="0" cellspacing="0" cellpadding="0" class="display-width-2" width="37%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;width:auto;">
                                                        <tbody>
                                                        <tr>
                                                            <td align="left">
                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:auto !important;">
                                                                    <tr>
                                                                        <td align="left" valign="top">
                                                                            <table align="left"  border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">

                                                                            </table>


                                                                        </td>
                                                                        <td width="15"></td>
                                                                        <td align="left" valign="top">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                                                                                <tr>
                                                                                    <td align="left" valign="middle">
                                                                                        <a href="#" style="color:#444444;text-decoration:none;">
                                                                                            <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/7Rf6UGhvdG9zaG9wIDMuMAA4QklNBCUAAAAAABAAAAAAAAAAAAAAAAAAAAAAOEJJTQQ6AAAAAADXAAAAEAAAAAEAAAAAAAtwcmludE91dHB1dAAAAAUAAAAAUHN0U2Jvb2wBAAAAAEludGVlbnVtAAAAAEludGUAAAAAQ2xybQAAAA9wcmludFNpeHRlZW5CaXRib29sAAAAAAtwcmludGVyTmFtZVRFWFQAAAABAAAAAAAPcHJpbnRQcm9vZlNldHVwT2JqYwAAAAVoIWg3i75/bgAAAAAACnByb29mU2V0dXAAAAABAAAAAEJsdG5lbnVtAAAADGJ1aWx0aW5Qcm9vZgAAAAlwcm9vZkNNWUsAOEJJTQQ7AAAAAAItAAAAEAAAAAEAAAAAABJwcmludE91dHB1dE9wdGlvbnMAAAAXAAAAAENwdG5ib29sAAAAAABDbGJyYm9vbAAAAAAAUmdzTWJvb2wAAAAAAENybkNib29sAAAAAABDbnRDYm9vbAAAAAAATGJsc2Jvb2wAAAAAAE5ndHZib29sAAAAAABFbWxEYm9vbAAAAAAASW50cmJvb2wAAAAAAEJja2dPYmpjAAAAAQAAAAAAAFJHQkMAAAADAAAAAFJkICBkb3ViQG/gAAAAAAAAAAAAR3JuIGRvdWJAb+AAAAAAAAAAAABCbCAgZG91YkBv4AAAAAAAAAAAAEJyZFRVbnRGI1JsdAAAAAAAAAAAAAAAAEJsZCBVbnRGI1JsdAAAAAAAAAAAAAAAAFJzbHRVbnRGI1B4bEBSAAAAAAAAAAAACnZlY3RvckRhdGFib29sAQAAAABQZ1BzZW51bQAAAABQZ1BzAAAAAFBnUEMAAAAATGVmdFVudEYjUmx0AAAAAAAAAAAAAAAAVG9wIFVudEYjUmx0AAAAAAAAAAAAAAAAU2NsIFVudEYjUHJjQFkAAAAAAAAAAAAQY3JvcFdoZW5QcmludGluZ2Jvb2wAAAAADmNyb3BSZWN0Qm90dG9tbG9uZwAAAAAAAAAMY3JvcFJlY3RMZWZ0bG9uZwAAAAAAAAANY3JvcFJlY3RSaWdodGxvbmcAAAAAAAAAC2Nyb3BSZWN0VG9wbG9uZwAAAAAAOEJJTQPtAAAAAAAQAEgAAAABAAEASAAAAAEAAThCSU0EJgAAAAAADgAAAAAAAAAAAAA/gAAAOEJJTQQNAAAAAAAEAAAAWjhCSU0EGQAAAAAABAAAAB44QklNA/MAAAAAAAkAAAAAAAAAAAEAOEJJTScQAAAAAAAKAAEAAAAAAAAAAThCSU0D9QAAAAAASAAvZmYAAQBsZmYABgAAAAAAAQAvZmYAAQChmZoABgAAAAAAAQAyAAAAAQBaAAAABgAAAAAAAQA1AAAAAQAtAAAABgAAAAAAAThCSU0D+AAAAAAAcAAA/////////////////////////////wPoAAAAAP////////////////////////////8D6AAAAAD/////////////////////////////A+gAAAAA/////////////////////////////wPoAAA4QklNBAAAAAAAAAIAAThCSU0EAgAAAAAABAAAAAA4QklNBDAAAAAAAAIBAThCSU0ELQAAAAAABgABAAAAAjhCSU0ECAAAAAAAEAAAAAEAAAJAAAACQAAAAAA4QklNBB4AAAAAAAQAAAAAOEJJTQQaAAAAAAM/AAAABgAAAAAAAAAAAAAB9AAAAfQAAAAFZypoB5iYAC0AMQAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAB9AAAAfQAAAAAAAAAAAAAAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAQAAAAAQAAAAAAAG51bGwAAAACAAAABmJvdW5kc09iamMAAAABAAAAAAAAUmN0MQAAAAQAAAAAVG9wIGxvbmcAAAAAAAAAAExlZnRsb25nAAAAAAAAAABCdG9tbG9uZwAAAfQAAAAAUmdodGxvbmcAAAH0AAAABnNsaWNlc1ZsTHMAAAABT2JqYwAAAAEAAAAAAAVzbGljZQAAABIAAAAHc2xpY2VJRGxvbmcAAAAAAAAAB2dyb3VwSURsb25nAAAAAAAAAAZvcmlnaW5lbnVtAAAADEVTbGljZU9yaWdpbgAAAA1hdXRvR2VuZXJhdGVkAAAAAFR5cGVlbnVtAAAACkVTbGljZVR5cGUAAAAASW1nIAAAAAZib3VuZHNPYmpjAAAAAQAAAAAAAFJjdDEAAAAEAAAAAFRvcCBsb25nAAAAAAAAAABMZWZ0bG9uZwAAAAAAAAAAQnRvbWxvbmcAAAH0AAAAAFJnaHRsb25nAAAB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" alt="100x50" width="100"/>
                                                                                        </a>
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
                                    <td height="30" style="border-bottom:1px dashed #eeeeee;"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

    <!-- MENU ENDS -->

    <!-- INVOICE STARTS -->

    <table align="center" bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center">
                <table align="center" bgcolor="#7daa40" border="0" cellpadding="0" cellspacing="0" class="display-width" width="680">
                    <tr>
                        <td align="center" style="padding:0 30px;">
                            <table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="display-width" style="border-bottom-left-radius:0px; border-bottom-right-radius:0px;" width="600">
                                <tr>
                                    <td height="40"></td>
                                </tr>
                                <tr>
                                    <td align="center" class="resp-padding" style="padding:0 30px;" >
                                        <table align="center"  border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                                            <tr>
                                                <td align="left">
                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width-4" width="100%">
                                                        <tr>
                                                            <td>
                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width-5" width="48%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                    <tr>
                                                                        <td align="left">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%" style="width:auto !important;">
                                                                                <tr>
                                                                                    <td align="left" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;font-weight:600;letter-spacing:1px;line-height:15px;">
                                                                                        Manager Details:
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="10"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="width:500px !important;">
                                                                                        <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width bill" width="60%">
                                                                                            <tr>
                                                                                                <td width="100%" style="width:100% !important;border-bottom:3px solid #333333;"></td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="10"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;font-weight:bold;letter-spacing:1px;line-height:24px;text-transform:uppercase;">
                                                                                        Name <br/> Email <br/> Date
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>

                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="1" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td style="line-height:40px;" height="40" width="1"></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>

                                                                <table align="right" border="0" cellpadding="0" cellspacing="0" class="display-width-5" width="48%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: right">
                                                                    <tr>
                                                                        <td align="right">
                                                                            <table align="right" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%" style="width:auto !important; text-align: right">
                                                                                <tr>
                                                                                    <td align="left" class="MsoNormal" style="color:#ffffff;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;font-weight:600;letter-spacing:1px;line-height:15px;">
                                                                                        Ship To:
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="10"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"  style="width:500px !important;">
                                                                                        <table align="right" border="0" cellpadding="0" cellspacing="0" class="display-width bill" width="60%" >
                                                                                            <tr>
                                                                                                <td width="100%" style="width:100% !important;border-bottom:3px solid #ffffff;"></td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="10"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="light" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;letter-spacing:1px;line-height:24px;text-transform:uppercase;">
                                                                                        {{ $manager->name }} <br/> {{ $manager->email }} <br/> <strong>{{ $wallet->created_at->toDateString() }}</strong>
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
                        <td align="center" style="padding:0 30px;">
                            <table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" class="display-width" style="border-bottom-left-radius:5px; border-bottom-right-radius:5px;" width="600">
                                <tr>
                                    <td height="40"></td>
                                </tr>
                                <tr>
                                    <td align="center" class="resp-padding" style="padding:0 30px;" >
                                        <table align="center"  border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%">
                                            <tr>
                                                <td align="left">
                                                    <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width-4" width="100%">
                                                        <tr>
                                                            <td>
                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width-5" width="48%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                    <tr>
                                                                        <td align="left">
                                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%" style="width:auto !important;">
                                                                                <tr>
                                                                                    <td align="left" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;font-weight:600;letter-spacing:1px;line-height:15px;">
                                                                                        Your Details:
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="10"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="width:500px !important;">
                                                                                        <table align="left" border="0" cellpadding="0" cellspacing="0" class="display-width bill" width="60%">
                                                                                            <tr>
                                                                                                <td width="100%" style="width:100% !important;border-bottom:3px solid #333333;"></td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="10"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;font-weight:bold;letter-spacing:1px;line-height:24px;text-transform:uppercase;">
                                                                                        Account Name <br/> Email <br/> Top up Amount
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>

                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="1" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td style="line-height:40px;" height="40" width="1"></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>

                                                                <table align="right" border="0" cellpadding="0" cellspacing="0" class="display-width-5" width="48%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; text-align: right">
                                                                    <tr>
                                                                        <td align="right">
                                                                            <table align="right" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%" style="width:auto !important; text-align: right">
                                                                                <tr>
                                                                                    <td align="left" class="MsoNormal" style="color:#ffffff;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;font-weight:600;letter-spacing:1px;line-height:15px;">
                                                                                        Ship To:
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="10"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"  style="width:500px !important;">
                                                                                        <table align="right" border="0" cellpadding="0" cellspacing="0" class="display-width bill" width="60%" >
                                                                                            <tr>
                                                                                                <td width="100%" style="width:100% !important;border-bottom:3px solid #ffffff;"></td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="10"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="light" class="MsoNormal" style="color:#333333;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:13px;letter-spacing:1px;line-height:24px;text-transform:uppercase;">
                                                                                        {{ $user->name }} <br/> {{ $user->email }} <br/> <strong>$ {{ number_format($wallet->amount,2) }}</strong>
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
                                                <td height="30" style="border-bottom:1px dashed #dddddd;"></td>
                                            </tr>
                                            <tr>
                                                <td height="30"></td>
                                            </tr>

                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="40"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>



    <!-- INVOICE ENDS -->

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

                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="48%" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td align="center">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" style="width:auto;">
                                                                                <tr>
                                                                                    <td>
                                                                                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="display-width" width="100%" style="width:auto !important;">
                                                                                            <tr>
                                                                                                <td align="center" class="MsoNormal" style="color:#FFFFFF;font-family:'Segoe UI',sans-serif,Arial,Helvetica,Lato;font-size:14px;letter-spacing:1px;line-height:24px;">
                                                                                                    Copyright &copy; 2021, Awareness Drop Shipping
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
</div>


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.7/dist/html2canvas.min.js"></script>
<script>
    $('.report-pdf-btn').click(function () {
        var data = document.getElementById('pdfDownload');
        html2canvas(data).then(canvas => {
            //  Few necessary setting options
            var imgWidth = 350;
            var imgHeight = canvas.height * imgWidth / canvas.width;
            const contentDataURL = canvas.toDataURL('image/png')
            let pdf = new jsPDF('p', 'mm', 'a3'); // A4 size page of PDF
            var position = 20;
            pdf.addImage(contentDataURL, 'JPEG', -20, position, imgWidth, imgHeight);
            //  pdf.save('new-file.pdf');
            window.open(pdf.output('bloburl', { filename: 'report.pdf' }), '_blank');


        });

    });

</script>

</body>
</html>
