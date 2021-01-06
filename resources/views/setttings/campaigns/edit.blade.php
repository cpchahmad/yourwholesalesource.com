@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    {{ $campaign->title }}
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Campaigns</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content">
                        <div class="form-group">
                            <label for="">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ $template->subject }}">
                        </div>

                        <div class="form-group">
                            <label for="">Campaign Name</label>
                            <input type="text" name="campaign_name" class="form-control" value="{{ $campaign->title }}">
                        </div>

                        <div class="form-group">
                            <label for="">Campaign Time</label>
                            <input type="text" required name="time" placeholder="{{ $campaign->time }}" class="js-flatpickr form-control bg-white" id="example-flatpickr-datetime-24" name="example-flatpickr-datetime-24" data-enable-time="true" data-time_24hr="true">
                        </div>

                        <div class="form-group">
                            <label for="">Body</label>
                            <textarea name="body" id="" cols="30" rows="10" class="form-control">{{ $template->body }}</textarea>
                        </div>

                        <div class="text-center">
                            <img style="width: 50%; height: auto;" src="{{asset('ticket-attachments')}}/{{$template->banner}}" alt="">
                        </div>

                        <div class="form-group">
                            <label for="">Banner</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input name="banner" type="file" class="custom-file-input" id="inputGroupFile04">
                                    <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
