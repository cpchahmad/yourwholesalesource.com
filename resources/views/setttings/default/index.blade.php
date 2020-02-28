@extends('layout.index')
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6 text-right">
            </div>
        </div>
        <div class="block">
            <div class="block-header">
                <h3 class="block-title">Default Settings</h3>
            </div>
            <div class="block-content block-content-narrow">
                <form class="form-horizontal push-10-t"
                      @if($info)
                      action="{{ route('default_info.update', $info->id) }}"
                      @else
                      action="{{ route('default_info.save') }}"
                      @endif
                      method="post">
                    @csrf
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="form-material">
                                <input class="form-control" type="text" name="info"
                                       placeholder="Enter Shipping Information here"
                                       @if($info->ship_info) value="{{ $info->ship_info }}"@endif
                                >
                                <label>Shipping Info</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="form-material">
                                <input class="form-control" type="text" name="time"
                                       placeholder="eg : 6 days"
                                       @if($info->processing_time) value="{{ $info->processing_time }}"@endif>
                                <label>Processing Time</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="form-material">
                                <input class="form-control" type="text" name="price"
                                       placeholder="$0.00"
                                       @if($info->ship_price) value="{{ $info->ship_price }}"@endif>
                                <label>Shipping Price</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="form-material">
                                <textarea class="form-control" type="text" name="warnedplatform" rows="3">
                                    @if($info->warned_platform){{ $info->warned_platform }}@endif</textarea>
                                <label>Warned Platforms</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-9">
                            @if ($info)
                                <button class="btn btn-sm btn-success" type="submit">Update</button>
                            @else
                                <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
