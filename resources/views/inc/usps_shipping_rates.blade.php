<select name="" id="" class="">
    @foreach($rates as $rate)
        <option value="{{ $rate->Postage->Rate }}">{{ $rate->Postage->Rate .' USD' }}</option>
    @endforeach
</select>
