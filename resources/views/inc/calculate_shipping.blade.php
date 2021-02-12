@php
    $warehouses = \App\WareHouse::all();
@endphp

<div class="warehouses mt-2">
    @foreach($warehouses as $warehouse)
        @if($warehouse->has_inventory($product->linked_product))
            <hr>
            <div class="custom-control custom-switch custom-control-success mb-1">
                <input type="checkbox" class="custom-control-input warehouse_checkbox" id="inventory_status_{{ $warehouse->id }}" name="inventory_status">
                <label class="custom-control-label" for="inventory_status_{{ $warehouse->id }}">{{ $warehouse->title }}</label>
            </div>
            <span class="mt-2">Countries: </span>
            @if($warehouse->zone)
                @foreach($warehouse->zone->has_countries as $country)
                    <span class="badge badge-success mt-2">{{ $country->name }}</span>
                    {{--                                                                                <strong>{{ $country->getShippingCost($product->linked_product) }}</strong>--}}
                @endforeach
            @endif
        @endif
    @endforeach
</div>

<div class="form-group row" style="margin-top: 10px">
    <div class="col-md-12">
        <div class="form-material">
            <label for="material-error">Shipping Country</label>
            <select  class="form-control shipping_country_select" name="country" data-product="{{$product}}" data-route="{{route('calculate_shipping')}}">
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
