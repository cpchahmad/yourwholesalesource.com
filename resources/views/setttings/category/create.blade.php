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
                <h3 class="font-w700">Categories</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="block" style="height: 28rem;">
{{--                    <div class="block-header">--}}
{{--                        <h3 class="block-title">Create Category</h3>--}}
{{--                    </div>--}}
                    <div class="block-content block-content-narrow">
                        <form class="form-horizontal" action="{{ route('category.save') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="form-material">
                                        <input class="form-control" type="text" id="cat" name="cat_title"
                                               placeholder="Enter Category Title here">
                                        <label for="material-error">Category</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9">
                                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if ($categories)
                <div class="col-md-6">
                    <div class="block" style="min-height: 28rem;">
{{--                        <div class="block-header">--}}
{{--                            <h3 class="block-title">Create SubCategory</h3>--}}
{{--                        </div>--}}
                        <div class="block-content block-content-narrow">
                            <form class="form-horizontal push-10-t" action="{{ route('sub.save') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="form-material">
                                            <select class="form-control" id="material-select" name="category_id"
                                                    size="1">
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                                @endforeach
                                            </select>
                                            <label for="material-select">Select Category</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group sub_cat_start">
                                    <div class="col-sm-11">
                                        <div class="form-material">
                                            <input class="form-control" type="text" id="sub_cat" name="sub_title[]"
                                                   placeholder="Enter Sub Category Title here">
                                            <label for="material-error">Sub Category</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-xs btn-default btn-primary sub_cat_btn" type="button"
                                                data-toggle="tooltip" title=""
                                                data-original-title="Add New SubCategory"><i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div style="display: none;">
                                    <div class="form-group append_sub_category">
                                        <div class="col-sm-11">
                                            <div class="form-material">
                                                <input class="form-control" type="text" name="sub_title[]"
                                                       placeholder="Enter Sub Category Title here">
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-9">
                                        <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="block">
{{--            <div class="block-header">--}}
{{--                <div class="block-options">--}}
{{--                </div>--}}
{{--                <h3 class="block-title">Categories</h3>--}}
{{--            </div>--}}
            <div class="block-content">
                @if(count($categories) > 0)
                @if ($categories)
                    <table class="js-table-sections table table-hover">
                        <thead>
                        <tr>
                            <th style="width: 30px;"></th>
                            <th>Title</th>
                            <th style="width: 15%;"></th>
                            <th class="text-center" style="width: 15%;"></th>
                        </tr>
                        </thead>
                        <?php $i = 1;?>
                        @foreach($categories as $category)
                            <tbody class="js-table-sections-header">
                            <tr>
                                <td class="text-center">
                                    <i class="fa fa-angle-right"></i>
                                </td>
                                <td class="font-w600">{{ $category->title }}</td>
                                <td>
                                    <span class="label label-primary"></span>
                                </td>
                                <td class="hidden-xs">

                                        <button class="btn btn-xs btn-warning" type="button" data-toggle="modal"
                                                data-target="#modal-popin{{$category->id}}" title="Edit Category"><i
                                                class="fa fa-pencil"></i></button>
                                        <a href="{{ route('category.delete', $category->id) }}"
                                           class="btn btn-xs btn-danger" type="button" data-toggle="tooltip" title=""
                                           data-original-title="Delete Category"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="modal-popin{{$category->id}}" tabindex="-1" role="dialog"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-popin">
                                    <div class="modal-content">
                                        <div class="block block-themed block-transparent remove-margin-b">
                                            <div class="block-header bg-primary-dark">
                                                <ul class="block-options">
                                                    <li>
                                                        <button data-dismiss="modal" type="button"><i
                                                                class="si si-close"></i></button>
                                                    </li>
                                                </ul>
                                                <h3 class="block-title">Update Category</h3>
                                            </div>
                                            <div class="block-content">
                                                <form class="form-horizontal push-10-t"
                                                      action="{{ route('category.update', $category->id) }}"
                                                      method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="title"
                                                               value="{{ $category->title }}">
                                                    </div>
                                                    <div class="form-group text-right">
                                                        <button class="btn btn-sm btn-success " type="submit">Update
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </tbody>
                            <tbody>
                            @if ($category->hasSub)
                                @foreach($category->hasSub as $sub)
                                    <tr>
                                        <td class="text-center text-success">
                                        </td>
                                        <td class="font-w600 text-success">{{ $sub->title }}</td>
                                        <td>
                                            <small></small>
                                        </td>
                                        <td class="hidden-xs">

                                                <button class="btn btn-xs btn-warning" type="button" data-toggle="modal"
                                                        data-target="#sub{{$sub->id}}" title="Edit SubCategory"><i
                                                        class="fa fa-pencil"></i></button>
                                                <a href="{{ route('sub.delete', $sub->id) }}"
                                                   class="btn btn-xs btn-danger" data-toggle="tooltip" title=""
                                                   data-original-title="Delete SubCategory"><i class="fa fa-times"></i></a>

                                        </td>
                                    </tr>
                                    <div class="modal fade" id="sub{{$sub->id}}" tabindex="-1" role="dialog"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-popin">
                                            <div class="modal-content">
                                                <div class="block block-themed block-transparent remove-margin-b">
                                                    <div class="block-header bg-primary-dark">
                                                        <ul class="block-options">
                                                            <li>
                                                                <button data-dismiss="modal" type="button"><i
                                                                        class="si si-close"></i></button>
                                                            </li>
                                                        </ul>
                                                        <h3 class="block-title">Update SubCategory</h3>
                                                    </div>
                                                    <div class="block-content">
                                                        <form class="form-horizontal push-10-t"
                                                              action="{{ route('sub.update', $sub->id) }}"
                                                              method="post">
                                                            @csrf
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="title"
                                                                       value="{{ $sub->title }}">
                                                            </div>
                                                            <div class="form-group text-right">
                                                                <button class="btn btn-sm btn-success " type="submit">
                                                                    Update
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            </tbody>
                        @endforeach
                    </table>
                @else
                    <h3>no found</h3>
                @endif
                    @else
                <p>No Categories Created</p>
                    @endif
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.sub_cat_btn').on('click', function () {
                var lastRepeatingGroup = $('.append_sub_category').last();
                lastRepeatingGroup.clone().insertAfter('.sub_cat_start');
                return false;
            });
            $('.sub_cat_append_remove').on('click', function () {
                alert('123');
            });
            // $('.sub_cat_append_remove').on('click', function(){
            //     alert('aaa');
            //     $(this).parent('div').remove();
            //     return false;
            // });
        });
    </script>
@endsection
