@extends('layout.single')
@section('content')
    <div class="bg-body-light">
        <div class="content content-full pt-2 pb-2">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h4 my-2">
                    Wallet
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Wallet</a>
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
                                    <h3 class="block-title ">Wallet ID
                                        <span style="float: right" ><i class="fa fa-info-circle" title="This ID used for wallet-to-wallet transferred"></i> {{$wallet->wallet_token}}</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 ">
                            <div class="block pay-options">
                                <div class="block-content">
                                    <p class="text-center"> Transfer Wallet-to-Wallet </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="block pay-options">
                               <div class="block-content">
                                   <p class="text-center"> Top-up with Paypal </p>
                               </div>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="block pay-options"  data-toggle="modal" data-target="#bank_transfer_modal">
                                <div class="block-content">
                                    <p class="text-center" > Top-up with Bank Transfer </p>
                                </div>
                            </div>
                            <div class="modal fade" id="bank_transfer_modal" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-popout" role="document">
                                    <div class="modal-content">
                                        <div class="block block-themed block-transparent mb-0">
                                            <div class="block-header bg-primary-dark">
                                                <h3 class="block-title">TOPUP through Bank Transfer</h3>
                                                <div class="block-options">
                                                    <button type="button" class="btn-block-option">
                                                        <i class="fa fa-fw fa-times"  data-dismiss="modal" aria-label="Close"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <form action="{{route('store.user.wallet.request.topup')}}" method="post">
                                                @csrf
                                                <input type="hidden" value="{{$user->id}}" name="user_id">
                                                <input type="hidden" value="{{$wallet->id}}" name="wallet_id">
                                                <div class="block-content font-size-sm">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="form-material">
                                                                <label for="material-error">Cheque Number</label>
                                                                <input  class="form-control" type="text"  name="cheque"
                                                                       value="" required  placeholder="Enter Cheque Number here">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="form-material">
                                                                <label for="material-error">Cheque Title</label>
                                                                <input  class="form-control" type="text"  name="cheque_title"
                                                                       value="" required  placeholder="Enter Cheque Title here">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="form-material">
                                                                <label for="material-error">Bank Name</label>
                                                                <input required class="form-control" type="text"  name="bank_name"
                                                                       value=""  placeholder="Enter Bank Title here">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="form-material">
                                                                <label for="material-error">Amount</label>
                                                                <input required class="form-control" type="number"  name="amount"
                                                                       value=""  placeholder="Enter Cheque Amount here">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="form-material">
                                                                <label for="material-error">Notes (Optional)</label>
                                                                <input  class="form-control" type="text"  name="notes"
                                                                             value=""   placeholder="Enter Notes here">
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="block">
                                <div class="block-header">
                                    <div class="block-title">
                                        Bank Transfer Top-up Requests
                                    </div>
                                </div>
                                <div class="block-content">
                                    @if (count($wallet->requests) > 0)
                                        <table class="table table-hover table-borderless table-striped table-vcenter">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Bank</th>
                                                <th>Cheque</th>
                                                <th>Cheque Title</th>
                                                <th>Amount</th>
                                                <th>Notes</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>

                                            @foreach($wallet->requests as $index => $req)
                                                <tbody class="">
                                                <tr>
                                                    <td>{{$index+1}}</td>
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
                                                    <td>
                                                        {{$req->notes}}
                                                    </td>

                                                    <td>
                                                        @if($req->status == 0)
                                                            <span class="badge badge-warning">Pending</span>
                                                        @else
                                                            <span class="badge badge-success">Approved</span>
                                                        @endif
                                                    </td>
                                                    <td>


                                                    </td>
                                                </tr>
                                                </tbody>

                                            @endforeach
                                        </table>
                                    @else
                                        <p>No Requests Found</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('inc.wallet_log')
                </div>
            </div>
        @endif
    @endif
@endsection
