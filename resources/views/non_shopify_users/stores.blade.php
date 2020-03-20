@extends('layout.shopify')
@section('content')
    <style>
        .mb2{
            margin-bottom: 10px !important;
        }
    </style>
    <div class="content" >
        <div class="row mb2">
            <div class="col-sm-6">
                <h3 class="font-w700">Stores</h3>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('system.store.connect')}}" class="btn btn-success btn-square ">Add Store</a>
            </div>
        </div>
        <div class="block">
            <div class="block-content">
                <table class="js-table-sections table table-hover">
                    <thead>
                    <tr>
                        <th style="width: 30px;">#</th>
                        <th>Title</th>
                        <th>Domain</th>
                        <th></th>
                    </tr>
                    </thead>


                    <tbody>
                    @foreach($shops as $index => $shop)
                        <tr>
                            <td class="text-center" style="vertical-align: middle">
                                {{ $index+1 }}
                            </td>
                            <td class="font-w600" style="vertical-align: middle">
                                {{explode('.',$shop->shopify_domain)[0]}}
                            </td>
                            <td style="vertical-align: middle">{{ $shop->shopify_domain }}</td>
                            <td class="text-right" style="vertical-align: middle">
                                <a href="" class="btn btn-xs btn-danger"
                                   type="button" data-toggle="tooltip" title=""
                                   data-original-title="Remove Store"><i class="fa fa-times"></i></a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
