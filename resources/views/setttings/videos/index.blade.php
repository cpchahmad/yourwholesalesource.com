@extends('layout.index')
@section('content')

<div class="bg-body-light">
    <div class="content content-full pt-2 pb-2">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h4 my-2">
                WholeSaleSource University
            </h1>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">WholeSaleSource University</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    <div class="row mb2">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6 text-right">
            <button class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#create_video">Create Video</button>
            <button class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#create_ribbon">Add Ribbon</button>
            <div class="modal fade" id="create_video" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                <div class="modal-dialog modal-dialog-popout" role="document">
                    <div class="modal-content text-left">
                        <div class="block block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">Create Video</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option">
                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                    </button>
                                </div>
                            </div>
                            <form action="{{route('admin.videos.create')}}" method="post">
                                @csrf
                                <div class="block-content font-size-sm">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="material-error">Title</label>
                                                <input  class="form-control  @error('title') is-invalid @enderror" type="text" id="zone_title"  name="title" placeholder="Enter video title..">
                                                @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="material-error">Youtube Link</label>
                                                <input  class="form-control  @error('link') is-invalid @enderror" type="text" id="zone_title"  name="link" placeholder="Enter video youtube link..">
                                                @error('link')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="material-error">Category</label>
                                                <select name="category" id="" class="form-control">
                                                    <option value="Shopify Users">Shopify Users</option>
                                                    <option value="Non shopify Users">Non-Shopify Users</option>
                                                    <option value="Useful Resources">Useful Resources</option>
                                                </select>
                                                @error('category')
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
            <div class="modal fade" id="create_ribbon" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                <div class="modal-dialog modal-dialog-popout" role="document">
                    <div class="modal-content text-left">
                        <div class="block block-themed block-transparent mb-0">
                            <div class="block-header bg-primary-dark">
                                <h3 class="block-title">Add Ribbon</h3>
                                <div class="block-options">
                                    <button type="button" class="btn-block-option">
                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                    </button>
                                </div>
                            </div>
                            <form action="{{route('admin.ribbon.create')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="block-content font-size-sm">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="material-error">Ribbon Color</label>
                                                <input  class="form-control  @error('color') is-invalid @enderror" type="text" id="zone_title"  name="color" placeholder="Enter ribbon color..">
                                                @error('color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="material-error">Cause</label>
                                                <input  class="form-control  @error('cause') is-invalid @enderror" type="text" id="zone_title"  name="cause" placeholder="Enter ribbon color cause..">
                                                @error('cause')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="material-error">Ribbon Image</label>
                                                <input  class="form-control  @error('image') is-invalid @enderror" type="file" id="zone_title"  name="image" placeholder="Select ribbon image..">
                                                @error('image')
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
    {{-- Videos --}}
    <div class="block">
        @foreach($videos as $category => $video)
            <div class="block-content">
            <div class="block-header font-size-h5 font-w600 pl-0">
                {{ $category }}
            </div>
            <div class="table-responsive">
                <table class="table table-borderless table-striped table-vcenter">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Link</th>
                        <th>Created At</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($video as $index => $item)
                    <tr>
                        <td class="font-w600" style="vertical-align: middle">
                            {{ $item->title }}
                        </td>
                        <td style="vertical-align: middle">
                            <a href="{{ $item->link }}">{{ $item->link }}</a>
                        </td>
                        <td style="vertical-align: middle">
                            {{ date_format($item->created_at ,"Y/M/d H:i ") }}
                        </td>
                        <td class="text-right btn-group" style="float: right">
                            <button class="btn btn-sm btn-warning" type="button" data-toggle="modal"
                                    data-target="#edit_video_modal{{$item->id}}"><i
                                    class="fa fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.videos.delete', $item->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title=""
                                        data-original-title="Delete Video"><i class="fa fa-times"></i></button>
                            </form>
                        </td>
                        <div class="modal fade" id="edit_video_modal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-popout" role="document">
                                <div class="modal-content">
                                    <div class="block block-themed block-transparent mb-0">
                                        <div class="block-header bg-primary-dark">
                                            <h3 class="block-title">Edit "{{ $item->title}}"</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option">
                                                    <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <form action="{{route('admin.videos.edit',$item->id)}}" method="post">
                                            @csrf
                                            <div class="block-content font-size-sm">
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label for="material-error">Title</label>
                                                            <input  class="form-control  @error('title') is-invalid @enderror" type="text" id="zone_title" value="{{$item->title}}" name="title" placeholder="Enter News title..">
                                                            @error('title')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="material-error">Youtube Link</label>
                                                            <input  class="form-control  @error('link') is-invalid @enderror" type="text" id="zone_title" value="{{$item->link}}"  name="link" placeholder="Enter video youtube link..">
                                                            @error('link')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="material-error">Category</label>
                                                            <select name="category" id="" class="form-control">
                                                                <option @if($item->category == "Shopify Users") selected @endif value="Shopify Users">Shopify Users</option>
                                                                <option @if($item->category == "Non shopify Users") selected @endif value="Non shopify Users">Non-Shopify Users</option>
                                                                <option @if($item->category == "Useful Resources") selected @endif value="Useful Resources">Useful Resources</option>
                                                            </select>
                                                            @error('category')
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

                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Ribbons --}}
    <div class="block mt-5">
        <div class="block-content">
            <div class="block-header font-size-h5 font-w600 pl-0">
                Ribbons
            </div>
            @if(count($ribbons))
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th>Color</th>
                            <th>Cause</th>
                            <th>Created At</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                             @foreach($ribbons as $index => $ribbon)
                            <tr>
                                <td class="font-w600" style="vertical-align: middle">
                                    @if($ribbon->image)<img src="{{ asset('/ribbons') }}/{{ $ribbon->image }}" alt="" style="width: 30px; height: auto;" ">@endif
                                    {{ $ribbon->color }}
                                </td>
                                <td style="vertical-align: middle">
                                    {{ $ribbon->cause }}
                                </td>
                                <td style="vertical-align: middle">
                                    {{ date_format($ribbon->created_at ,"Y/M/d H:i ") }}
                                </td>
                                <td class="text-right btn-group" style="float: right">
                                    <button class="btn btn-sm btn-warning" type="button" data-toggle="modal"
                                            data-target="#edit_video_modal{{$ribbon->id}}"><i
                                            class="fa fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.videos.delete', $ribbon->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" type="button" data-toggle="tooltip" title=""
                                                data-original-title="Delete Video"><i class="fa fa-times"></i></button>
                                    </form>
                                </td>
                                <div class="modal fade" id="edit_video_modal{{$ribbon->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-popout" role="document">
                                        <div class="modal-content">
                                            <div class="block block-themed block-transparent mb-0">
                                                <div class="block-header bg-primary-dark">
                                                    <h3 class="block-title">Edit "{{ $ribbon->title}}"</h3>
                                                    <div class="block-options">
                                                        <button type="button" class="btn-block-option">
                                                            <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <form action="{{route('admin.videos.edit',$ribbon->id)}}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="block-content font-size-sm">
                                                        <div class="form-group">
                                                            <div class="col-sm-12">
                                                                <div class="form-group">
                                                                    <label for="material-error">Ribbon Color</label>
                                                                    <input  class="form-control  @error('color') is-invalid @enderror" type="text" id="zone_title"  value="{{ $ribbon->color }}" name="color" placeholder="Enter ribbon color..">
                                                                    @error('color')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="material-error">Cause</label>
                                                                    <input  class="form-control  @error('cause') is-invalid @enderror" type="text" id="zone_title" {{ $ribbon->cause }}  name="cause" placeholder="Enter ribbon color cause..">
                                                                    @error('cause')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="material-error">Ribbon Image</label>
                                                                    <input  class="form-control  @error('image') is-invalid @enderror" type="file" id="zone_title"  name="image" placeholder="Select ribbon image..">
                                                                    @error('image')
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

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center p-3">No Ribbons Added Yet!</div>
            @endif
        </div>
    </div>

</div>
@endsection
