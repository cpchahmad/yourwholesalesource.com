@php
    $warehouses = \App\WareHouse::all();
    $admin_product = \App\Product::find($product);
    $retailer_product = \App\RetailerProduct::find($retailer_product_id);
@endphp

<div class="warehouses mt-2">
    @if($retailer_product && $retailer_product->has_inventory())
        <label for="material-error">Warehouses</label>
        @foreach($warehouses as $warehouse)
            @if($warehouse->has_inventory($admin_product))
                <div class="custom-control custom-switch custom-control-success mb-1">
                    <label for="material-error">{{ $warehouse->title }}</label>
                </div>
                <span class="mt-2">Shipping Countries In that zone: </span>
                @if($warehouse->zone)
                    <div class="form-group row" style="margin-top: 10px">
                        <div class="col-md-12">
                            <div class="form-material">
                                <select  class="form-control shipping_country_select" name="country" data-retailer-product="{{ $retailer_product->id }}" data-product="{{$product}}" data-route="{{route('calculate_shipping')}}">
                                    @foreach($warehouse->zone->has_countries as $country)
                                        <option @if($selected == $country->name) selected @endif  value="{{$country->name}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
                <hr>
            @endif
        @endforeach
    @endif
</div>

<div class="form-group row" style="margin-top: 10px">
    <div class="col-md-12">
        <div class="form-material">
            <label for="material-error">Shipping Country</label>
            <select  class="form-control shipping_country_select" name="country" data-product="{{$product}}" data-retailer-product="{{ $retailer_product->id }}" data-route="{{route('calculate_shipping')}}">
                @foreach($countries as $country)
                    <option @if($selected == $country->name) selected @endif  value="{{$country->name}}">{{$country->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

@if(count($rates) > 0)
    <table class="table table-vcenter table-hover table-striped">
        <thead>
        <tr>
            <td></td>
            <td>Estimated Delivery Time</td>
            <td>Shipping Price</td>
        </tr>
        </thead>
        <tbody>
        @foreach($rates as $rate)
            <tr>
                <td><input type="radio" class="shipping_price_radio" data-country="{{$selected}}" name="shipping_price" data-price="${{number_format($rate->shipping_price,2)}}"></td>
                <td>{{$rate->shipping_time}}</td>
                <td>${{number_format($rate->shipping_price,2)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-center">No shipping price available for this country!</p>
@endif
