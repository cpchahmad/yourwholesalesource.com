@extends('layout.single')
@section('content')
    <style>
        .img-avatar {
            border-radius: 0;
        }
        .mb2{
            margin-bottom: 10px !important;

        }
    </style>
    <div class="content">
        <div class="content-grid">
            <div class="row mb2">
                <h3 class="font-w700" style="display: contents">Import List</h3>
            </div>

            <div class="row mb2">
                <div class="col-md-8">
                    <input type="search" name="search" placeholder="Search By Keyword" class="form-control">
                </div>
                <div class="col-md-3"><select name="source" class="form-control">
                        <option value="all">All Sources</option>
                        <option value="Fantasy">WeFullFill</option>
                        <option value="AliExpress">AliExpress</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary"><i class="fa fa-search"></i>Search</button>
                </div>
            </div>
            <div class="row block mb2">
                <div class="block-content push-10">
                    <div>
                        <label class="css-input css-checkbox css-checkbox-primary">
                            <input type="checkbox"><span></span>
                        </label>
                    </div>


                    <div class="">
                        <div class="btn-group btn-group-justified">
                            <div class="btn-group">
                                <button class="btn btn-default" type="button"><i class="fa fa-arrow-left"></i></button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-default" type="button"><i class="fa fa-check"></i></button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-default" type="button"><i class="fa fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            @foreach($products as $product)
                <div class="row block mb2">
                    <div class="block-content push-10">
                        <label class="css-input css-checkbox css-checkbox-primary">
                            <input type="checkbox"><span></span>
                        </label>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

@endsection
