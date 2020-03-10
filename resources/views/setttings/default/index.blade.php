@extends('layout.index')
@section('content')
    <div class="content">
        <div class="row" style="margin-bottom: 10px">
            <div class="col-sm-6">
                <h3 class="font-w700">Default Setting</h3>
            </div>
            <div class="col-sm-6 text-right">
            </div>
        </div>
{{--        <div class="block">--}}
{{--            <div class="block-header">--}}
{{--                <h3 class="block-title">Shipping Information</h3>--}}
{{--            </div>--}}
{{--            <div class="block-content block-content-narrow">--}}
{{--                <form class="form-horizontal push-10-t"--}}
{{--                      @if($info)--}}
{{--                      action="{{ route('default_info.update', $info->id) }}"--}}
{{--                      @else--}}
{{--                      action="{{ route('default_info.save') }}"--}}
{{--                      @endif--}}
{{--                      method="post">--}}
{{--                    @csrf--}}
{{--                    <div class="form-group">--}}
{{--                        <div class="col-sm-12">--}}
{{--                            <div class="form-material">--}}
{{--                                <input class="form-control" type="text" name="info"--}}
{{--                                       placeholder="Enter Shipping Information here"--}}
{{--                                       @if($info->ship_info) value="{{ $info->ship_info }}"@endif--}}
{{--                                >--}}
{{--                                <label>Shipping Info</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <div class="col-sm-12">--}}
{{--                            <div class="form-material">--}}
{{--                                <input class="form-control" type="text" name="time"--}}
{{--                                       placeholder="eg : 6 days"--}}
{{--                                       @if($info->processing_time) value="{{ $info->processing_time }}"@endif>--}}
{{--                                <label>Processing Time</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <div class="col-sm-12">--}}
{{--                            <div class="form-material">--}}
{{--                                <input class="form-control" type="text" name="price"--}}
{{--                                       placeholder="$0.00"--}}
{{--                                       @if($info->ship_price) value="{{ $info->ship_price }}"@endif>--}}
{{--                                <label>Shipping Price</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group">--}}
{{--                        <div class="col-sm-12">--}}
{{--                            <div class="form-material">--}}
{{--                                <textarea class="form-control" type="text" name="warnedplatform" rows="3">--}}
{{--                                    @if($info->warned_platform){{ $info->warned_platform }}@endif</textarea>--}}
{{--                                <label>Warned Platforms</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="form-group">--}}
{{--                        <div class="col-sm-9">--}}
{{--                            @if ($info)--}}
{{--                                <button class="btn btn-sm btn-success" type="submit">Update</button>--}}
{{--                            @else--}}
{{--                                <button class="btn btn-sm btn-primary" type="submit">Submit</button>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
       <div class="row">
           <div class="col-md-4">
               <div class="block">
                   <div class="block-header">
                       <h3 class="block-title">Create Warned Platform</h3>
                   </div>
                   <div class="block-content block-content-narrow">
                       <form class="form-horizontal push-10-t"
                             action="{{ route('create_platform') }}"
                             method="post">
                           @csrf
                           <div class="form-group">
                               <div class="col-sm-12">
                                   <div class="form-material">
                                       <input class="form-control" type="text" name="name" required
                                              placeholder="Enter Warned Platform Title here">
                                       <label>Title</label>
                                   </div>
                               </div>
                           </div>
                           <div class="form-group">
                               <div class="col-sm-9">
                                       <button class="btn btn-sm btn-success" type="submit">Save</button>
                               </div>
                           </div>
                       </form>
                   </div>
               </div>
           </div>
           <div class="col-md-8">
               <div class="block">
                   <div class="block-header">
                       <h3 class="block-title">Warned Platform</h3>
                   </div>
                   <div class="block-content block-content-narrow">
                       @if(count($platforms) > 0)
                           <table class="table table-hover">
                               <thead>
                               <tr>
                                   <th>Title</th>
                                   <th class="text-right">Action</th>
                               </tr>
                               </thead>
                               <tbody>
                               @foreach($platforms as $index => $p)
                                   <tr>
                                       <td>{{$p->name}}</td>
                                       <td class="text-right">
                                           <a class="btn btn-xs btn-warning"
                                              type="button" data-toggle="modal" data-target="#edit_platform_modal{{$index}}"><i class="fa fa-pencil"></i></a>
                                           <a href="{{ route('delete_platform', $p->id) }}" class="btn btn-xs btn-danger"
                                              type="button" data-toggle="tooltip" title=""
                                              data-original-title="Delete Plateform"><i class="fa fa-times"></i></a>
                                       </td>
                                   </tr>
                                   <div class="modal fade" id="edit_platform_modal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                       <div class="modal-dialog modal-dialog-popout" role="document">
                                           <div class="modal-content">
                                               <div class="block block-themed block-transparent mb-0">
                                                   <div class="block-header bg-primary-dark">
                                                       <h3 class="block-title">Edit "{{$p->name}}"</h3>
                                                       <div class="block-options">
                                                           <button type="button" class="btn-block-option">
                                                               <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                           </button>
                                                       </div>
                                                   </div>
                                                   <form action="{{route('update_platform',$p->id)}}" method="post">
                                                       @csrf
                                                       <div class="block-content font-size-sm">
                                                           <div class="form-group">
                                                               <div class="col-sm-12">
                                                                   <div class="form-material">
                                                                       <input required class="form-control" type="text" id="name" name="name"
                                                                              value="{{$p->name}}">
                                                                       <label for="material-error">Title</label>
                                                                   </div>
                                                               </div>
                                                           </div>
                                                       </div>

                                                       <div class="block-content block-content-full text-right border-top">
                                                           <button type="submit" class="btn btn-sm btn-primary" >Save</button>
                                                       </div>
                                                   </form>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               @endforeach
                               </tbody>
                           </table>
                           @else
                           <p>No Platforms Available</p>
                       @endif
                   </div>
               </div>
           </div>
       </div>
    </div>
@endsection
