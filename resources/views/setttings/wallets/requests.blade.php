@extends('layout.index')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Wallets
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Wallets</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div  class="form-horizontal push-30">
        <div class="content">
            @foreach($wallets as $wallet)
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
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        @foreach($wallet->requests()->where('type','bank transfer')->get() as $index => $req)
                                            <tbody class="">
                                            <tr>
                                                <td class="font-w600">{{ $req->bank_name }}</td>
                                                <td>
                                                    {{$req->cheque}}
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
                                                    @if($req->status == 0)
                                                        <span class="badge badge-warning">Pending</span>
                                                    @else
                                                        <span class="badge badge-success">Approved</span>
                                                    @endif
                                                </td>
                                                <td>
                                                <td class="text-right">

                                                    @if($req->status == 0)
                                                        <button type="button" data-toggle="modal" data-target="#edit_bank_approve_modal{{$req->id}}" class="btn btn-sm btn-primary">Edit</button>
                                                        <button class="btn btn-sm btn-success <!--approve-bank-transfer-button-->" data-toggle="modal" data-target="#bank_approve_modal{{$req->id}}" {{--data-route="{{route('sales_managers.wallets.approve.request',$req->id)}}" data-wallet="{{$wallet->wallet_token}}" data-amount="{{number_format($req->amount,2)}} USD" --}} > Approve</button>
                                                    @endif

                                                </td>

                                            </tr>
                                            </tbody>
                                            <div class="modal fade" id="edit_bank_approve_modal{{$req->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-popout" role="document">
                                                    <div class="modal-content">
                                                        <div class="block block-themed block-transparent mb-0">
                                                            <div class="block-header bg-primary-dark">
                                                                <h3 class="block-title">Edit Top-up</h3>
                                                                <div class="block-options">
                                                                    <button type="button" class="btn-block-option">
                                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <form action="{{route('admin.wallets.edit',$req->id)}}" method="get">
                                                                <div class="block-content font-size-sm">
                                                                    <div class="form-group">
                                                                        <div class="form-material">
                                                                            <label for="material-error">Cheque</label>
                                                                            <input required class="form-control" type="text"  name="cheque"
                                                                                   value="{{ $req->cheque    }}" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="form-material">
                                                                            <label for="material-error">Company/Sender Name</label>
                                                                            <input required class="form-control" type="text"  name="cheque_title"
                                                                                   value="{{ $req->cheque_title }}" >
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="form-material">
                                                                            <label for="material-error">Amount</label>
                                                                            <input required class="form-control" type="text"  name="amount"
                                                                                   value="{{ $req->amount }}" >
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div class="block-content block-content-full text-right border-top">
                                                                    <button type="submit" class="btn btn-sm btn-primary" >Edit Wallet Request</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="bank_approve_modal{{$req->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-popout" role="document">
                                                    <div class="modal-content">
                                                        <div class="block block-themed block-transparent mb-0">
                                                            <div class="block-header bg-primary-dark">
                                                                <h3 class="block-title">Approve Top-up</h3>
                                                                <div class="block-options">
                                                                    <button type="button" class="btn-block-option">
                                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <form action="{{route('admin.wallets.approve.request',$req->id)}}" method="get">
                                                                <div class="block-content font-size-sm">
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-material">
                                                                                <label for="material-error">Approved Date</label>
                                                                                <input required class="form-control" type="date"  name="date"
                                                                                       value="" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="block-content block-content-full text-right border-top">
                                                                    <button type="submit" class="btn btn-sm btn-primary" >Approved</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


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
                                            <th></th>
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
                                                <td class="text-right">
                                                    @if($req->status == 0)
                                                        <button type="button" data-toggle="modal" data-target="#edit_bank_approve_modal{{$req->id}}" class="btn btn-sm btn-primary">Edit</button>
                                                        <button class="btn btn-sm btn-success <!--approve-bank-transfer-button-->" data-toggle="modal" data-target="#bank_approve_modal{{$req->id}}" {{--data-route="{{route('sales_managers.wallets.approve.request',$req->id)}}" data-wallet="{{$wallet->wallet_token}}" data-amount="{{number_format($req->amount,2)}} USD" --}} > Approve</button>
                                                    @endif
                                                </td>
                                            </tr>
                                            </tbody>
                                            <div class="modal fade" id="edit_bank_approve_modal{{$req->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-popout" role="document">
                                                    <div class="modal-content">
                                                        <div class="block block-themed block-transparent mb-0">
                                                            <div class="block-header bg-primary-dark">
                                                                <h3 class="block-title">Edit Top-up</h3>
                                                                <div class="block-options">
                                                                    <button type="button" class="btn-block-option">
                                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <form action="{{route('admin.wallets.edit',$req->id)}}" method="get">
                                                                <div class="block-content font-size-sm">
                                                                    <div class="form-group">
                                                                        <div class="form-material">
                                                                            <label for="material-error">Alibaba Order Number</label>
                                                                            <input required class="form-control" type="text"  name="cheque"
                                                                                   value="{{ $req->cheque }}" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="form-material">
                                                                            <label for="material-error">Company/Sender Name</label>
                                                                            <input required class="form-control" type="text"  name="cheque_title"
                                                                                   value="{{ $req->cheque_title }}" >
                                                                        </div>
                                                                    </div>


                                                                    <div class="form-group">
                                                                        <div class="form-material">
                                                                            <label for="material-error">Amount</label>
                                                                            <input required class="form-control" type="text"  name="amount"
                                                                                   value="{{ $req->amount }}" >
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div class="block-content block-content-full text-right border-top">
                                                                    <button type="submit" class="btn btn-sm btn-primary" >Edit Wallet Request</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="bank_approve_modal{{$req->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-popout" role="document">
                                                    <div class="modal-content">
                                                        <div class="block block-themed block-transparent mb-0">
                                                            <div class="block-header bg-primary-dark">
                                                                <h3 class="block-title">Approve Top-up</h3>
                                                                <div class="block-options">
                                                                    <button type="button" class="btn-block-option">
                                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <form action="{{route('admin.wallets.approve.request',$req->id)}}" method="get">
                                                                <div class="block-content font-size-sm">
                                                                    <div class="form-group">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-material">
                                                                                <label for="material-error">Approved Date</label>
                                                                                <input required class="form-control" type="date"  name="date"
                                                                                       value="" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="block-content block-content-full text-right border-top">
                                                                    <button type="submit" class="btn btn-sm btn-primary" >Approved</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </table>
                                @else
                                    <p>No AliBaba Top-up Requests Found</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
