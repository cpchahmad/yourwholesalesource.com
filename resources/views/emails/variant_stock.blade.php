<!DOCTYPE html>
<html>
<head>
    <head>
        <title>Variant Out Of Stock</title>
    </head>
</head>
<body style="margin: 0; ">
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
    .wrap {
        padding-left: 20px;
        background-color: #7daa40 !important;
        color: #ffffff !important;
        padding: 1px 20px
    }

    .custom-badge {
        background: #f3b760;
        color: white;
        padding: 2px 5px;
        border-radius: 5px;
    }

    .wrap .right{
        text-align: right !important;
    }

    tr:nth-child(even) {
        background: #f1f1f1;
    }

    .wrap .left{
        text-align: left !important;
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
                <h1 class="email-title" style="margin: 0;margin-bottom: 30px;font-size: 34px;">Product Variant Is Out Of Stock</h1>
                <p class="email-message-1" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53;" >Dear user, {{ $product->title }} variant(s) is now out of stock on Wefullfill, kindly update the stock on your stores as well.</p>

                <a href="{{ route('store.my_product.wefulfill.show', $product->id) }}" target="_blank" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">View Details</a>
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
