@extends('layout.index')
@section('content')

    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    News
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">News</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
{{--        <form class="js-form-icon-search push" action="" method="get">--}}
{{--            <div class="form-group">--}}
{{--                <div class="input-group">--}}
{{--                    <input type="user_search" class="form-control" placeholder="Search By User Name" value="{{$user_search}}" name="user_search" >--}}
{{--                    <select name="type_search" id="" class="form-control">--}}
{{--                        <option value="" disabled selected>{{ $type_search }}</option>--}}
{{--                        <option value="Product">Product</option>--}}
{{--                        <option value="RetailerProduct">Retailer Product</option>--}}
{{--                        <option value="Order">Order</option>--}}
{{--                        <option value="Ticket">Ticket</option>--}}
{{--                        <option value="Wishlist">Wishlist</option>--}}
{{--                        <option value="Wallet">Wallet</option>--}}
{{--                    </select>--}}
{{--                    <div class="input-group-append">--}}
{{--                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>--}}
{{--                        <a class="btn btn-danger" href="{{ route('admin.activity.log.index') }}"> <i class="fa fa-times"></i> Clear </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </form>--}}
        <div class="row mb2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6 text-right">
                <button class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#create_news">Create News</button>
                <div class="modal fade" id="create_news" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-popout" role="document">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent mb-0">
                                <div class="block-header bg-primary-dark">
                                    <h3 class="block-title">Create News</h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option">
                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                        </button>
                                    </div>
                                </div>
                                <form action="{{route('admin.news.store')}}" method="post">
                                    @csrf
                                    <div class="block-content font-size-sm">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="material-error">Title</label>
                                                    <input required class="form-control  @error('title') is-invalid @enderror" type="text" id="zone_title"  name="title" placeholder="Enter news title..">
                                                    @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="material-error">Description</label>
                                                    <textarea required class="form-control  @error('description') is-invalid @enderror" type="text" id="zone_title"   name="description" placeholder="Enter news address.."></textarea>
                                                    @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
            </div>
        </div>
        <div class="block">
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Published At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($news as $item)
                            <tr>
                                <td class="font-w600" style="vertical-align: middle">
                                        {{ $item->title }}
                                </td>
                                <td style="vertical-align: middle">
                                    <p> {{ $item->description }}</p>
                                </td>
                                <td style="vertical-align: middle">
                                    {{ date_format($item->created_at ,"Y/M/d H:i ") }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{ $news->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
