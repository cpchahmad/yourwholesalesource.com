<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Wefullfill</title>
</head>
<body style="margin: 0">
<style>
    .email-body
    {
        color:black;
    }
    .email-content
    {
        max-width: 450px;
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

<div class="email-body" style="padding: 20px;max-width: 700px;margin: auto; font-family: DIN Next,sans-serif;">
    <div class="email-contaner" style="border: 4px solid #7daa40;padding: 25px;">
        <div class="email-content" style=" max-width: 450px;  margin: auto;  text-align: center; ">
            <div class="email-logo">
                <img src="https://cdn.shopify.com/s/files/1/0370/7361/7029/files/image_3.png?v=1585895317" alt="Wefullfill" style="width: 50%">
            </div>
            @isset($edit)
                <form action="{{ route('admin.emails.update', 1) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="email-content-detail" style="margin: 50px 0;">
                        <input class="email-title " type="text" name="subject" style="margin: 0;margin-bottom: 30px;font-size: 34px; width: 100%" placeholder="Welcome to Wefullfill" value="Welcome to Wefullfill">
                        <br>
                        <textarea type="text" class="email-message-1" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53; width: 100%" name="body" placeholder="Hey {{ $user->name }}, Welcome to Wefullfill Family, here you enjoy handsfree dropshipping and other perks." >Hey {{ $user->name }}, Welcome to Wefullfill Family, here you enjoy handsfree dropshipping and other perks.</textarea>
                        <br>
                        <a href="https://www.wefullfill.com/pages/help-center" target="_blank" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">Help Center</a>
                    </div>
                    <button type="submit" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none; background-color: #7daa40; color: #ffffff; margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">Update</button>
                </form>
            @else
                <div class="email-content-detail" style="margin: 50px 0;">
                    <h1 class="email-title" style="margin: 0;margin-bottom: 30px;font-size: 34px;">Welcome to Wefullfill</h1>
                    <p class="email-message-1" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53;" >Hey {{ $user->name }}, Welcome to Wefullfill Family, here you enjoy handsfree dropshipping and other perks. </p>

                    <a href="https://www.wefullfill.com/pages/help-center" target="_blank" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">Help Center</a>
                </div>
            @endisset
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
