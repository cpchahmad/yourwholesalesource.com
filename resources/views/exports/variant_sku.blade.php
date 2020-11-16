<table class="table js-table-sections table-hover table-borderless table-striped table-vcenter">
    <thead>
    <tr>
        <th>SKU</th>
        <th>Option</th>
        <th>Image Url</th>
    </tr>
    </thead>


    <tbody class="">
    @foreach($variants as $v)
        <tr>
            <td class="font-w600">{{ $v->sku }}</td>
            <td class="font-w600">@if($v->option1 != null) {{$v->option1}} @endif    @if($v->option2 != null) / {{$v->option2}} @endif    @if($v->option3 != null) / {{$v->option3}} @endif</td>
{{--            <td class="font-w600">--}}
{{--                @if($v->has_image == null)--}}
{{--                    No image Found--}}
{{--                @else--}}
{{--                    @if($v->has_image->isV == 0)--}}
{{--                        {{asset('images')}}/{{$v->has_image->image}}--}}
{{--                    @else--}}
{{--                        {{asset('images/variants')}}/{{$v->has_image->image}}--}}
{{--                    @endif--}}
{{--                @endif--}}
{{--            </td>--}}
            <td>Still to implement</td>
        </tr>
    @endforeach
    </tbody>
</table>
