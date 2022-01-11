@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                Export All Products
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Export All Products</a>
                        </li>
                        <li class="breadcrumb-item">Export All Products</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">Export All Products</h3>
                    </div>
                    <div class="block-content block-content-narrow">
                        <form class="form-horizontal push-10-t"
                              action="{{ route('product.exportallproducts') }}"
                              method="post">
                            @csrf

                            <div class="form-group">
                                <div class="col-md-12 text-centre">
                                    <button class="btn btn-sm btn-primary" type="submit">Export</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>





            <div class="col-md-6">
                <div class="block">
                    <div class="block-header">
                        <h3 class="block-title">Import All Products</h3>
                    </div>
                    <div class="block-content block-content-narrow">
                        <form class="form-horizontal push-10-t"
                              action="{{ route('product.importallproducts') }}"
                              method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="file">
                                    </div>

                                    <div class="col-md-2">
                                        <button class="btn btn-sm btn-primary" type="submit">Import</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>

        </div>


        <div class="block">
            <div class="block-content">

                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            {{--                            <th style="width:5% "></th>--}}
                            <th>#</th>
                            <th>File Name</th>
                            <th>Created_at</th>
                            <th>Action</th>


                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $i=1;
                        @endphp
                       @foreach($csv as $getcsv)
                           <tr>
                             <td class="">
                                    {{$i++}}
                               </td>
                                <td class="font-w600" style="vertical-align: middle">
                                    {{$getcsv->filename}}
                                </td>

                               <td class="font-w600" style="vertical-align: middle">
                                    {{$getcsv->created_at}}
                                </td>

                                <td>
                                    <a href="{{asset('allproductsupload/'.$getcsv->filename)}}" download="{{asset('allproductsupload/'.$getcsv->filename)}}">Download</a>
                                </td>
                           </tr>

                       @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center" style="font-size: 17px">

                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
