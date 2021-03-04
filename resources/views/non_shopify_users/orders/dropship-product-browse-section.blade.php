@foreach($products as $product)
    <div class="col-md-12">
        <div class="custom-control custom-checkbox d-inline-block" style="padding: 10px">
            <input type="checkbox" class="custom-control-input product-checkbox" data-related="#product_variant{{$product->id}}" id="product{{$product->id}}">
            <label class="custom-control-label" for="product{{$product->id}}">
                @if(count($product->has_images) > 0)
                    <img class="img-avatar16"  src="{{asset('shipping-marks')}}/{{$product->has_images()->first()->image}}">
                @else
                    <img class="img-avatar16" src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg">
                @endif

                {{$product->title}}</label>
        </div>
        <div class="variants row" id="product_variant{{$product->id}}" style="margin-left: 10px;margin-bottom: 10px">
            @if(count($product->hasVariants) > 0)
                @foreach($product->hasVariants as $variant)
                    <div class="col-md-12">
                        <div class="custom-control custom-checkbox d-inline-block" style="padding: 10px;width: 100%">
                            <input type="checkbox" name="variants[]" class="custom-control-input variant-checkbox" data-related="#product{{$product->id}}" id="varaint_product_{{$variant->id}}" value="{{$variant->id}}">
                            <label class="custom-control-label" for="varaint_product_{{$variant->id}}">
                                <img class="img-avatar16 " style="border: 1px solid whitesmoke"
                                     @if($variant->image == null)  src="https://wfpl.org/wp-content/plugins/lightbox/images/No-image-found.jpg"
                                     @else src="{{asset('shipping-marks')}}/{{$variant->image}}" @endif alt="">
                               @if($variant->option != null)   {{$variant->option}}   @endif

                            </label>
                            <span class="d-inline-block" style="float: right;font-weight: 400">{{number_format($variant->price,2)}} USD</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

@endforeach
