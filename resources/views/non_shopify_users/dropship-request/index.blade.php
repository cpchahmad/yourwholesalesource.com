@extends('layout.shopify')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Dropship Request
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dropship Request</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        @if($user->has_manager != null)
            <form class="js-form-icon-search push" action="" method="get">
                <div class="form-group">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="Search by name" value="" name="search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                            <a class="btn btn-danger" href=""> <i class="fa fa-times"></i> Clear </a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="block">
                <div class="block-content">
                    <div class="row">
                        <div class="col-md-12 mb2">
                            <button style="float: right;margin-bottom: 10px" class="btn btn-sm btn-primary" data-target="#create_new_request" data-toggle="modal">Create New Dropship Request</button>
                        </div>

                        <div class="col-md-12 mb2">
                            @if(count($requests) > 0)
                                <table class="table table-hover table-borderless table-striped table-vcenter">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Cost</th>
                                        <th>Weekly Sales</th>
                                        <th style="width: 5%">Markets</th>
                                        <th>Status</th>
                                        <th>Approved Cost</th>
                                        <th style="text-align: right">
                                        </th>
                                    </tr>
                                    </thead>

                                    @foreach($requests as $index => $item)
                                        <tbody class="">
                                        <tr>

                                            <td class="font-w600"><a href="{{route('users.dropship.request.view',$item->id)}}">{{ $item->product_name }}</a></td>
                                            <td>
                                                {{number_format($item->cost,2)}} USD
                                            </td>
                                            <td>
                                                {{$item->weekly_sales}}
                                            </td>
                                            <td>
                                                @if(count($item->has_market) > 0)
                                                @foreach($item->has_market as $country)
                                                    <span class="badge badge-primary">{{$country->name}}</span>
                                                    @endforeach
                                                @else
                                                    none
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->has_status != null)
                                                    <span class="badge " style="background: {{$item->has_status->color}};color: white;"> {{$item->has_status->name}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->approved_price != null)
                                                    {{number_format($item->approved_price,2)}} USD
                                                    @else
                                                    Not Approved Yet
                                                @endif
                                            </td>

                                            <td class="text-right">
                                                <div class="btn-group">
                                                    <a href="{{route('users.dropship.request.view',$item->id)}}"
                                                       class="btn btn-sm btn-success" type="button" data-toggle="tooltip" title=""
                                                       data-original-title="View Wishlist"><i class="fa fa-eye"></i></a>
                                                    <a href="{{ route('dropship.requests.delete', $item->id) }}"
                                                       class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title=""
                                                       data-original-title="Delete Wishlist"><i class="fa fa-times"></i></a>
                                                </div>
                                            </td>

                                        </tr>
                                        </tbody>

                                    @endforeach
                                </table>

                                <div class="row">
                                    <div class="col-md-12 text-center" style="font-size: 17px">
                                        {!! $requests->links() !!}
                                    </div>
                                </div>

                            @else
                                <p class="text-center">No Dropship Request Found.</p>
                            @endif
                        </div>


                    </div>
                </div>
            </div>
            <div class="modal fade" id="create_new_request" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
                    <div class="modal-content">
                        <div class="block block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">New Dropship Request</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option">
                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                    </button>
                                </div>
                            </div>
                            <form action="{{route('dropship.request.create')}}" method="post"  enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="manager_id" value="{{$user->sale_manager_id}}">
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                <input type="hidden" name="type" value="user-wishlist">

                                <div class="text-center text-danger p-3 font-w600">
                                    Reminder: Dear user, creating a Dropship Request means that you will send us the stock to fulfill
                                </div>

                                <div class="block-content font-size-sm">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Product Name <i class="fa fa-question-circle"  title="This is the name of product you want to request in your dropship request"> </i></label>

                                                <input required class="form-control" type="text"  name="product_name"
                                                       placeholder="Enter Title here">
                                            </div>
                                        </div>
                                    </div>
{{--                                    @if($user->has_stores()->count() > 0)--}}
{{--                                        @if($user->has_stores()->count() == 1)--}}
{{--                                            @php--}}
{{--                                                $store = $user->has_stores()->first()--}}
{{--                                            @endphp--}}
{{--                                            <input type="hidden" name="shop_id" value="{{ $store->id }}">--}}
{{--                                        @else--}}
{{--                                            <div class="form-group">--}}
{{--                                                <div class="col-sm-12">--}}
{{--                                                    <div class="form-material">--}}
{{--                                                        <label for="material-error">Shopify Store <i class="fa fa-question-circle"  title="This is the name of the store you want to you want to request for your dropship request"> </i></label>--}}
{{--                                                        <select name="shop_id" id="" class="form-control">--}}
{{--                                                            @foreach($user->has_stores()->get() as $store)--}}
{{--                                                                <option value="{{ $store->id }}"> {{ $store->shopify_domain }}</option>--}}
{{--                                                            @endforeach--}}
{{--                                                        </select>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
{{--                                    @endif--}}

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Attachments <i class="fa fa-question-circle"  title="Files/Images related to this product"> </i></label>
                                                <input type="file" name="attachments[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Product Link <i class="fa fa-question-circle"  title="Reference link to product you want to request in your dropship request"> </i></label>
                                                <input  class="form-control" type="url"  name="product_url"
                                                        placeholder="Enter Product Link here">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Expected Weekly Sold out amount <i class="fa fa-question-circle"  title="This is the expected weekly sales of product you want to request in your dropship request"> </i></label>
                                                <input required class="form-control" type="number"   name="weekly_sales"
                                                       placeholder="Enter Weekly Sold out quantity here">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Contains Battery or not?</label>
                                                <br>
                                                <div class=" mb-1">
                                                    <input type="radio"  class="" id="battery-yes" name="battery" value="1" >
                                                    <label class="" for="battery-yes">Yes</label>
                                                    <input type="radio"  class="ml-2" id="battery-no" name="battery" value="0" >
                                                    <label class="" for="battery-no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Packing Size <i class="fa fa-question-circle"  title="This is the size of product you want to request in your dropship request"> </i></label>

                                                <input required class="form-control" type="text"  name="packing_size"
                                                       placeholder="Enter size details here">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Weight <span class="text-muted">(kg)</span> <i class="fa fa-question-circle"  title="This is the expected weight of product you want to request in your dropship request"> </i></label>
                                                <input required class="form-control" type="number" step="any"  name="weight"
                                                       placeholder="Enter Product Weight here">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Do you need Relabell or not?</label>
                                                <br>
                                                <div class=" mb-1">
                                                    <input type="radio"  class="" id="relabell-yes" name="relabell" value="1" >
                                                    <label class="" for="relabell-yes">Yes</label>
                                                    <input type="radio"  class="ml-2" id="relabell-no" name="relabell" value="0" >
                                                    <label class="" for="relabell-no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Do you need us to repack with extra protections?</label>
                                                <br>
                                                <div class=" mb-1">
                                                    <input type="radio"  class="" id="repack-yes" name="re_pack" value="1" >
                                                    <label class="" for="repack-yes">Yes</label>
                                                    <input type="radio"  class="ml-2" id="repack-no" name="re_pack" value="0" >
                                                    <label class="" for="repack-no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">How many stock you are going to send to us? <i class="fa fa-question-circle"  title="This is the expected stock of product you are going to send to us"> </i></label>
                                                <input required class="form-control" type="number" name="stock"
                                                       placeholder="Enter Product Stock here">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">How many options of the stock? <i class="fa fa-question-circle"  title="This is the expected number on stock options you are going to send to us"> </i></label>
                                                <input required class="form-control" type="number" name="option_count"
                                                       placeholder="Enter number of options of the stock here">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Selling Markets <i class="fa fa-question-circle"  title="Countries where product you want to sale. "> </i></label>
                                                <select class="form-control js-select2" style="width: 100%;" data-placeholder="Choose multiple markets.." name="countries[]" required  multiple="">
                                                    <option></option>
                                                    @foreach($countries as $country)
                                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Target Shipping Cost <i class="fa fa-question-circle"  title="This is the cost of product you want to request in your dropship request"> </i></label>
                                                <input required class="form-control" type="number" step="any"  name="cost"
                                                       placeholder="Enter Shipping Cost here">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-material">
                                                <label for="material-error">Description <i class="fa fa-question-circle"  title="Description of product you want to request in dropship request"> </i></label>
                                                <textarea required class="js-summernote" name="description"
                                                          placeholder="Please Enter Description here !"></textarea>
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

        @else
            <div class="block">
                <div class="block-content">
                    <p class="text-center">You can't create dropship request because you are not assigned to any sales manager.</p>
                </div>
            </div>
    @endif


@endsection
