@extends('layout.woocommerce')
@section('content')
<div class="bg-body-light">
    <div class="content content-full pt-2 pb-2">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h4 my-2">
                Invoice Zone
            </h1>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item" aria-current="page">
                        <a class="link-fx" href="">Invoice Zone</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if(!isset($wallet) && !isset($user))
    <div class="block ">
        <div class="block-content ">
            <p class="text-center"> No Account Associated With This Store Found ! <a href="{{route('store.index')}}"> Click Here For Account Association :) </a></p>
        </div>
    </div>
@else
    @if($wallet != null)
        <div class="content">
            <div class="content-grid">
                <div class="row mb2">
                    <div class="col-md-3">
                        <div class="block ">
                            <div class="block-header">
                                <h3 class="block-title ">Available</h3>
                            </div>
                            <div class="block-content ">
                                <p class="font-size-h2"> {{number_format($wallet->available,2)}} USD</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="block ">
                            <div class="block-header">
                                <h3 class="block-title">Pending</h3>
                            </div>
                            <div class="block-content ">
                                <p class=" font-size-h2"> {{number_format($wallet->pending,2)}} USD</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="block ">
                            <div class="block-header">
                                <h3 class="block-title">Transferred</h3>
                            </div>
                            <div class="block-content ">
                                <p class="font-size-h2"> {{number_format($wallet->transferred,2)}} USD</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="block ">
                            <div class="block-header">
                                <h3 class="block-title">Used</h3>
                            </div>
                            <div class="block-content ">
                                <p class=" font-size-h2"> {{number_format($wallet->used,2)}} USD</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="block">
                            <div class="block-header">
                                <div class="block-title">
                                    Bank Transfer Top-up Requests
                                </div>
                            </div>
                            <div class="block-content">
                                @if (count($wallet->requests()->where('type','bank transfer')->get()) > 0)
                                    <table class="table table-hover table-borderless table-striped table-vcenter">
                                        <thead>
                                        <tr>
                                            <th>Bank</th>
                                            <th>Cheque</th>
                                            <th>Company/Sender Title</th>
                                            <th>Amount</th>
                                            <th>Bank Proof Copy</th>
                                            <th>Notes</th>
                                            <th>Status</th>
                                            <th>PDF</th>
                                        </tr>
                                        </thead>

                                        @foreach($wallet->requests()->where('type','bank transfer')->get() as $index => $req)
                                            <tbody class="">
                                            <tr>
                                                <td class="font-w600">{{ $req->bank_name }}</td>
                                                <td>
                                                    @if($req->cheque != null)
                                                        {{$req->cheque}}
                                                    @else
                                                        <span class="text-primary-dark">No Cheque Provided</span>
                                                    @endif

                                                </td>
                                                <td>
                                                    {{$req->cheque_title}}
                                                </td>
                                                <td>
                                                    {{number_format($req->amount,2)}} USD
                                                </td>
                                                <td class="js-gallery">
                                                    @if($req->attachment != null)
                                                        <a class="img-link img-link-zoom-in img-lightbox" href="{{asset('wallet-attachment')}}/{{$req->attachment}}">
                                                            View Proof
                                                        </a>
                                                    @else
                                                        No Proof Provided
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($req->notes != null)
                                                        {{$req->notes}}
                                                    @else
                                                        No Notes
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($req->status == 0)
                                                        <span class="badge badge-warning">Pending</span>
                                                    @else
                                                        <span class="badge badge-success">Approved</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button target="_blank" class="btn btn-danger" onclick="window.location.href='{{route('store.invoice.download',$req->id)}}'">Download</button>
                                                </td>

                                            </tr>
                                            </tbody>

                                        @endforeach
                                    </table>
                                @else
                                    <p>No  Bank Transfer Requests Found</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="block">
                            <div class="block-header">
                                <div class="block-title">
                                    AliBaba Top-up Requests
                                </div>
                            </div>
                            <div class="block-content">
                                @if (count($wallet->requests()->where('type','alibaba')->get()) > 0)
                                    <table class="table table-hover table-borderless table-striped table-vcenter">
                                        <thead>
                                        <tr>
                                            <th>Company/Sender Title</th>
                                            <th>Alibaba Order Number </th>
                                            <th>Amount</th>
                                            <th>Bank Proof Copy</th>
                                            <th>Notes</th>
                                            <th>Status</th>
                                            <th>PDF</th>
                                        </tr>
                                        </thead>

                                        @foreach($wallet->requests()->where('type','alibaba')->get() as $index => $req)
                                            <tbody class="">
                                            <tr>

                                                <td>
                                                    {{$req->cheque_title}}
                                                </td>
                                                <td>
                                                    {{$req->cheque}}
                                                </td>

                                                <td>
                                                    {{number_format($req->amount,2)}} USD
                                                </td>
                                                <td class="js-gallery">
                                                    @if($req->attachment != null)
                                                        <a class="img-link img-link-zoom-in img-lightbox" href="{{asset('wallet-attachment')}}/{{$req->attachment}}">
                                                            View Proof
                                                        </a>
                                                    @else
                                                        No Proof Provided
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($req->notes != null)
                                                        {{$req->notes}}
                                                    @else
                                                        No Notes
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($req->status == 0)
                                                        <span class="badge badge-warning">Pending</span>
                                                    @else
                                                        <span class="badge badge-success">Approved</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button target="_blank" class="btn btn-danger" onclick="window.location.href='{{route('store.invoice.download',$req->id)}}'">Download</button>
                                                </td>
                                            </tr>
                                            </tbody>

                                        @endforeach
                                    </table>
                                @else
                                    <p>No AliBaba Top-up Requests Found</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
@endsection
