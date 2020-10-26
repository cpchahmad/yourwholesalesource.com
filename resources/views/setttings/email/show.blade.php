@extends('layout.index')
@section('content')
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

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    View Email Templates
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">View Email Templates</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block">
            <div class="block-content">
                <div class="email-body" style="padding: 20px;max-width: 700px;margin: auto; font-family: DIN Next,sans-serif;">
                    <div class="email-contaner" style="border: 4px solid #7daa40;padding: 25px;">
                        <div class="email-content" style="margin: auto;  text-align: center; ">
                            <div class="email-logo">
                                <img src="https://cdn.shopify.com/s/files/1/0370/7361/7029/files/image_3.png?v=1585895317" alt="Wefullfill" style="width: 50%">
                            </div>
                                <form action="{{ route('admin.emails.update', 1) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="email-content-detail" style="margin: 50px 0;">
                                        <input class="email-title " type="text" name="subject" style="margin: 0;margin-bottom: 30px;font-size: 34px; width: 100%" placeholder="subject" value="{{ $template->subject }}">
                                        <br>
                                        <textarea type="text" class="email-message-1" rows="5" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53; width: 100%" name="body" placeholder="body" >{{ $template->body }}</textarea>
                                        <br>
                                        <a href="https://www.wefullfill.com/pages/help-center" target="_blank" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">Help Center</a>
                                    </div>
                                    <button type="submit" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none; background-color: #7daa40; color: #ffffff; margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">Update</button>
                                </form>
                            @else
                                <div class="email-content-detail" style="margin: 50px 0;">
                                    <h1 class="email-title" style="margin: 0;margin-bottom: 30px;font-size: 34px;">Welcome to Wefullfill</h1>
                                    <p class="email-message-1" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53;" >Hey, Welcome to Wefullfill Family, here you enjoy handsfree dropshipping and other perks. </p>

                                    <a href="https://www.wefullfill.com/pages/help-center" target="_blank" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">Help Center</a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
