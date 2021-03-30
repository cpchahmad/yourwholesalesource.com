@extends('layout.shopify')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Wefulfill University
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row justify-content-center">
            <h1>Help Center</h1>
        </div>
        @foreach($videos as $category => $video)
            <div class="block text-center pt-3">
                <h1 class="text-primary">{{ $category }}</h1>
                <div class="row p-3">
                    @foreach($video as $item)
                        <div class="col-lg-4 invisible" data-toggle="appear" data-offset="50" data-class="animated fadeIn">
                            <a class="block block-link-pop text-center p-2" href="{{ $item->link }}" target="_blank">
                                <div class="overlay justify-content-center d-flex justify-content-center align-items-center" alt="">
                                    <i class="si si-control-play text-white" style="font-size: 60px; font-weight: bolder;"></i>
                                </div>
                                <div class="block-content text-left pl-0 d-flex justify-content-between">
                                    <h4 class="mb-1">{{ $item->title }}</h4>
                                    <div class="text-right">
                                        <i class="si si-control-play text-white bg-dark p-2" style="font-size: 20px; font-weight: bolder; border-radius: 50%"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="content">
        <div class="block mt-5">
        <div class="block-content">
            <div class="row justify-content-center">
                <h1>Ribbons</h1>
            </div>
            @if(count($ribbons))
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th>Color</th>
                            <th>Cause</th>
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
