<select name="" id="" class="form-control">
    @foreach($rates as $rate)
        <option value="{{ $rate->Postage->Rate }}">{{ $rate->Postage->MailService . ': '. $rate->Postage->Rate .' USD' }}</option>
    @endforeach
</select>
