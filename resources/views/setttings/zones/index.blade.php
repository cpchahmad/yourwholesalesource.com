@extends('layout.index')
@section('content')
    <style>
        .mb2{
            margin-bottom: 10px !important;
        }
    </style>
    <div class="content">
        <div class="row mb2">
            <div class="col-sm-6">
                <h3 class="font-w700">Shipping Zones</h3>
            </div>
            <div class="col-sm-6 text-right">
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#create_zone_modal">Create Shipping Zone</button>
            </div>
        </div>
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-content">
                        @if (count($zones) > 0)
                            <table class="js-table-sections table table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 30px;"></th>
                                    <th >Title</th>
                                    <th style="width: 25%;">Countries</th>
                                    <th></th>
                                    <th class="text-center" style="width: 15%;"></th>
                                    <th></th>
                                </tr>
                                </thead>

                                @foreach($zones as $index => $zone)
                                    <tbody class="js-table-sections-header">
                                    <tr>
                                        <td class="text-center">
                                            <i class="fa fa-angle-right"></i>
                                        </td>
                                        <td class="font-w600">{{ $zone->name }}</td>
                                        <td>
                                            @foreach($zone->has_countries as $country)
                                            <span class="label label-primary">{{$country->name}}</span>
                                                @endforeach
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#create_rate_modal{{$index}}"> Add Rate</button>
                                        </td>
                                        <td></td>
                                        <td class="text-center">
                                            <button class="btn btn-xs btn-warning" type="button" data-toggle="modal"
                                                    data-target="#edit_zone_modal{{$index}}"><i
                                                    class="fa fa-pencil"></i>
                                            </button>
                                            <a href="{{ route('zone.delete', $zone->id) }}"
                                               class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title=""
                                               data-original-title="Delete Zone"><i class="fa fa-times"></i></a>
                                        </td>

                                    </tr>
                                    <div class="modal fade" id="edit_zone_modal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-popout" role="document">
                                            <div class="modal-content">
                                                <div class="block block-themed block-transparent mb-0">
                                                    <div class="block-header bg-primary-dark">
                                                        <h3 class="block-title">Edit "{{$zone->name}}"</h3>
                                                        <div class="block-options">
                                                            <button type="button" class="btn-block-option">
                                                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <form action="{{route('zone.update',$zone->id)}}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{$zone->id}}" name="zone_id">
                                                        <div class="block-content font-size-sm">
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <div class="form-material">
                                                                        <input required class="form-control" type="text" id="zone_title" name="name"
                                                                            value="{{$zone->name}}"   placeholder="Enter Zone Title here">
                                                                        <label for="material-error">Title</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <label for=""> Select Countries</label>
                                                                </div>
                                                            </div>
                                                            <div class="countries-section">
                                                                @foreach($countries as $country)
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <label class="css-input css-checkbox css-checkbox-info">
                                                                                @if(in_array($country->id,$zone->has_countries->pluck('id')->toArray()))
                                                                                <input type="checkbox" value="{{$country->id}}" checked name="countries[]"><span></span> {{$country->name}}
                                                                                    @else
                                                                                    <input type="checkbox" value="{{$country->id}}" name="countries[]"><span></span> {{$country->name}}
                                                                                @endif
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
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
                                    <div class="modal fade" id="create_rate_modal{{$index}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-popout" role="document">
                                            <div class="modal-content">
                                                <div class="block block-themed block-transparent mb-0">
                                                    <div class="block-header bg-primary-dark">
                                                        <h3 class="block-title">Add {{$zone->name}}'s Rate</h3>
                                                        <div class="block-options">
                                                            <button type="button" class="btn-block-option">
                                                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <form action="{{route('zone.rate.create',$zone->id)}}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{$zone->id}}" name="zone_id">
                                                        <div class="block-content font-size-sm">
                                                            <div class="form-group row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-material">
                                                                        <input required class="form-control" type="text"  name="name"
                                                                               placeholder="Enter Zone Title here">
                                                                        <label for="material-error">Title</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row" style="margin-top: 10px">
                                                                <div class="col-sm-6">
                                                                    <div class="form-material">
                                                                        <select required class="form-control rate_type_select" name="type">
                                                                            <option value="flat">Flat Rate</option>
                                                                            <option value="order_price">Per Order Price</option>
                                                                            <option value="weight">Per Weight</option>

                                                                        </select>
                                                                        <label for="material-error">Rate Type</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-material">
                                                                        <input required class="form-control" type="number" name="shipping_price"
                                                                            >
                                                                        <label for="material-error">Price</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row condition-div" style="display: none">
                                                                <div class="col-sm-6">
                                                                    <div class="form-material">
                                                                        <input class="form-control max-condtion" type="number" name="max">
                                                                        <label for="material-error ">Max Condition</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-material">
                                                                        <input class="form-control min-condtion " type="number" name="min">
                                                                        <label for="material-error ">Min Condition</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row ">
                                                                <div class="col-sm-6">
                                                                    <div class="form-material">
                                                                        <input required class="form-control" type="text" name="shipping_time">
                                                                        <label for="material-error ">Shipping Time (Days)</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-material">
                                                                        <input required class="form-control " type="text" name="processing_time">
                                                                        <label for="material-error ">Processing Time (Days)</label>
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
                                    </tbody>
                                {{--Rates Tables--}}
                                    <tbody>
                                    @if (count($zone->has_rate) > 0)
                                        @foreach($zone->has_rate as $new_index => $rate)
                                            <tr>
                                                <td class="text-center text-success">
                                                    {{ $rate->name }}
                                                </td>
                                                <td class="font-w600"> Type: {{ str_replace('_',' ',$rate->type)  }}</td>
                                                <td>
                                                    Condition: @if($rate->type == 'flat') None @elseif($rate->type == 'order_price') {{$rate->min}} - {{$rate->max}} $ @else  {{$rate->min}} - {{$rate->max}} Kgs @endif
                                                </td>
                                              <td style="width: 25%;">
                                                 <p>Shipping Time : {{$rate->shipping_time}}<br>
                                                     Processing Time : {{$rate->processing_time}}
                                                 </p>
                                              </td>
                                                <td
                                                    class="text-success text-center">${{number_format($rate->shipping_price,2)}}
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-xs btn-warning" type="button" data-toggle="modal"
                                                            data-target="#edit_rate_modal{{$new_index}}"><i
                                                            class="fa fa-pencil"></i>
                                                    </button>
                                                    <a href="{{ route('zone.rate.delete', $rate->id) }}"
                                                       class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title=""
                                                       data-original-title="Delete Rate"><i class="fa fa-times"></i></a>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="edit_rate_modal{{$new_index}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-popout" role="document">
                                                    <div class="modal-content">
                                                        <div class="block block-themed block-transparent mb-0">
                                                            <div class="block-header bg-primary-dark">
                                                                <h3 class="block-title">Add {{$zone->name}}'s Rate</h3>
                                                                <div class="block-options">
                                                                    <button type="button" class="btn-block-option">
                                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <form action="{{route('zone.rate.update',$rate->id)}}" method="post">
                                                                @csrf
                                                                <input type="hidden" value="{{$zone->id}}" name="zone_id">
                                                                <div class="block-content font-size-sm">
                                                                    <div class="form-group row">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-material">
                                                                                <input required class="form-control" type="text"  name="name"
                                                                                  value="{{$rate->name}}"     placeholder="Enter Zone Title here">
                                                                                <label for="material-error">Title</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row" style="margin-top: 10px">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-material">
                                                                                <select required class="form-control rate_type_select" name="type">
                                                                                    <option @if($rate->type == 'flat') selected @endif  value="flat">Flat Rate</option>
                                                                                    <option @if($rate->type == 'order_price') selected @endif value="order_price">Per Order Price</option>
                                                                                    <option @if($rate->type == 'weight') selected @endif value="weight">Per Weight</option>

                                                                                </select>
                                                                                <label for="material-error">Rate Type</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-material">
                                                                                <input required class="form-control" type="number" name="shipping_price" value="{{$rate->shipping_price}}">
                                                                                <label for="material-error">Price</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row condition-div" @if($rate->type == 'flat') style="display: none" @endif>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-material">
                                                                                <input class="form-control min-condtion " @if($rate->type != 'flat') required @endif value="{{$rate->min}}" type="number" name="min">
                                                                                <label for="material-error ">Min Condition</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-material">
                                                                                <input class="form-control max-condtion" @if($rate->type != 'flat') required @endif value="{{$rate->max}}" type="number" name="max">
                                                                                <label for="material-error ">Max Condition</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row ">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-material">
                                                                                <input required class="form-control" type="text" name="shipping_time" value="{{$rate->shipping_time}}">
                                                                                <label for="material-error ">Shipping Time (Days)</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-material">
                                                                                <input required class="form-control " type="text" name="processing_time" value="{{$rate->processing_time}}">
                                                                                <label for="material-error ">Processing Time (Days)</label>
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

                                    @else
                                        <tr>
                                            <td >
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#create_rate_modal{{$index}}"> Add Rate</button>
                                            </td>
                                            <td class="font-w600 text-success"></td>
                                            <td>
                                                <small></small>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                    @endif

                                    </tbody>

                                @endforeach
                            </table>
                        @else
                            <p>No Shipping Zone Created</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="create_zone_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Shipping Zone</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option">
                                <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                            </button>
                        </div>
                    </div>
                    <form action="{{route('zone.create')}}" method="post">
                        @csrf
                    <div class="block-content font-size-sm">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <input required class="form-control" type="text" id="zone_title" name="name"
                                           placeholder="Enter Zone Title here">
                                    <label for="material-error">Title</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                            <label for=""> Select Countries</label>
                            </div>
                        </div>
                        <div class="countries-section">
                            @foreach($countries as $country)
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label class="css-input css-checkbox css-checkbox-info">
                                            <input type="checkbox" value="{{$country->id}}" name="countries[]"><span></span> {{$country->name}}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
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

@endsection
