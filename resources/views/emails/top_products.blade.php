<!DOCTYPE html>
<html>
<head>
    <title>Top Products</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{now()}}"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
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

<div class="email-body" style="padding: 20px;max-width: 80%;margin: auto; font-family: DIN Next,sans-serif;">
    <div class="email-contaner" style="border: 2px solid #7daa40;padding: 25px;">
        <div class="email-content" style="margin: auto;  text-align: center; ">
            <div class="email-logo">
                <img src="https://cdn.shopify.com/s/files/1/0370/7361/7029/files/image_3.png?v=1585895317" alt="Wefullfill" style="width: 35%">
            </div>

            <div class="email-content-detail" style="margin: 50px 0;">
                <h1 class="email-title" style="margin: 0;margin-bottom: 30px;font-size: 34px;">{{ $template->subject }}</h1>
                <p class="email-message-1" style=" margin: 0;margin-bottom: 30px;font-size: 20px;line-height: 1.53;" >{{ $template->body }} </p>
                <hr>
                <div class="products-details text-left">
                    <div class="block-content ">
                        @if(count($top_products_stores) > 0)
                            <table class="table table-striped table-hover table-borderless table-vcenter">
                                <thead>
                                <tr class="text-uppercase">
                                    <th class="font-w700">Product</th>
                                    <th class="d-none d-sm-table-cell font-w700 text-center" style="width: 80px;">Quantity</th>
                                    <th class="font-w700 text-center" style="width: 60px;">Sales</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($top_products_stores as $product)
                                    <tr>
                                        <td class="font-w600">
                                            @foreach($product->has_images()->orderBy('position')->get() as $index => $image)
                                                @if($index == 0)
                                                    @if($image->isV == 0)
                                                        <img class="img-avatar img-avatar32" style="margin-right: 5px" src="{{asset('images')}}/{{$image->image}}" alt="">
                                                    @else
                                                        <img class="img-avatar img-avatar32" style="margin-right: 5px" src="{{asset('images/variants')}}/{{$image->image}}" alt="">
                                                    @endif
                                                @endif
                                            @endforeach
                                            <a href="{{route('product.view',$product->id)}}">{{$product->title}}</a>
                                        </td>
                                        <td class="d-none d-sm-table-cell text-center">
                                            {{$product->sold}}
                                        </td>
                                        <td class="">
                                            ${{number_format($product->selling_cost,2)}}
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                @else
                                    <p  class="text-center"> No Products Found </p>
                                @endif
                            </table>
                    </div>
                </div>
                <a href="{{ route('store.product.wefulfill') }}" target="_blank" class="email_btn" style="padding: 17px 55px; border: 2px solid #7daa40;font-size: 20px;letter-spacing: 1px;text-decoration: none;color: #7daa40;margin-top: 0;FONT-WEIGHT: 600;margin-bottom: 25px;margin-top: 25px">View Products</a>
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
