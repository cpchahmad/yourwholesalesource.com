<?php

namespace App\Http\Controllers;


use App\User;
use App\Wallet;
use App\WalletLog;
use App\WalletRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{

    private $helper;

    /**
     * WalletController constructor.
     */
    public function __construct()
    {
        $this->helper = new HelperController();
    }

    public function user_wallet_view()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->has_wallet == null) {
                $wallet = $this->wallet_create(Auth::id());
            } else {
                $wallet = $user->has_wallet;
            }
            return view('single-store.wallet.index')->with([
                'user' => $user,
                'wallet' => $wallet
            ]);
        } else {
            $shop = $this->helper->getLocalShop();
            if (count($shop->has_user) > 0) {
                if ($shop->has_user[0]->has_wallet == null) {
                    $wallet = $this->wallet_create($shop->has_user[0]->id);
                } else {
                    $wallet = $shop->has_user[0]->has_wallet;
                }
                return view('single-store.wallet.index')->with([
                    'user' => $shop->has_user[0],
                    'wallet' => $wallet
                ]);
            } else {
                return view('single-store.wallet.index');
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function wallet_create($id)
    {
        $wallet = Wallet::create([
            'user_id' => $id,
            'wallet_token' => 'WFF00100' . rand(10000000000, 99999999999),
            'pending' => 0,
            'available' => 0,
            'transferred' => 0,
            'used' => 0,
        ]);
        $wallet_log = new WalletLog();
        $wallet_log->wallet_id = $wallet->id;
        $wallet_log->status = "CREATED";
        $wallet_log->amount = 0;
        $wallet_log->message = 'Wallet ' . $wallet->wallet_token . ' Initiated at ' . now()->format('d M, Y h:i a');
        $wallet_log->save();
        return $wallet;
    }

    public function request_wallet_topup_bank(Request $request)
    {
       $user = User::find($request->input('user_id'));
       $wallet = Wallet::find($request->input('wallet_id'));
       if($user != null && $wallet != null){
          WalletRequest::create($request->all());
           $wallet_log = new WalletLog();
           $wallet_log->wallet_id = $request->input('wallet_id');
           $wallet_log->status = "Top-up Request Through Bank Transfer";
           $wallet_log->amount = $request->input('amount');
           $wallet_log->message = 'A Top-up Request of Amount '.number_format($request->input('amount'),2).' USD Through Bank Transfer Against Wallet ' . $wallet->wallet_token . ' Requested At ' . now()->format('d M, Y h:i a');
           $wallet_log->save();
           $wallet->pending = $wallet->pending + $request->input('amount');
           $wallet->save();
          return redirect()->back()->with('success', 'Your Top-up Request Submit Successfully to Administration. Please Wait For Approval!');
       }
       else{
           return redirect()->back()->with('error', 'Something Goes Wrong!');
       }

    }

    public function index(){
        $admins = User::whereIn('email',['admin@wefullfill.com','super_admin@wefullfill.com'])->pluck('id')->toArray();
        $users  = User::whereNotIn('id',$admins)->get();
        foreach ($users as $user){
            if ($user->has_wallet == null) {
               $this->wallet_create($user->id);
            }
        }
        return view('setttings.wallets.index')->with([
            'users' => $users
        ]);
    }

    public function wallet_details(Request $request,$id){
        $wallet = Wallet::find($id);
        $user = User::find($wallet->user_id);
        return view('setttings.wallets.wallet_detail')->with([
            'user' => $user,
            'wallet' => $wallet
        ]);
    }

    public function approved_bank_statement($id){
        $req = WalletRequest::find($id);
        if($req->status == 0){
            $related_wallet = Wallet::find($req->wallet_id);
            if($related_wallet!= null){
                $related_wallet->pending =  $related_wallet->pending - $req->amount;
                $related_wallet->available =   $related_wallet->available + $req->amount;
                $related_wallet->save();
                $req->status = 1;
                $req->save();
                $wallet_log = new WalletLog();
                $wallet_log->wallet_id =$related_wallet->id;
                $wallet_log->status = "Bank Transfer Approved";
                $wallet_log->amount = $req->amount;
                $wallet_log->message = 'A Top-up Request of Amount '.number_format($req->amount,2).' USD Through Bank Transfer Against Wallet ' . $related_wallet->wallet_token . ' Approved At ' . now()->format('d M, Y h:i a'). ' By Administration';
                $wallet_log->save();
                return redirect()->back()->with('success','Top-up Request through Bank Transfer Approved Successfully!');
            }
            else{
                return redirect()->back()->with('error','No wallet found related to this request!');
            }
        }
        else{
            return redirect()->back()->with('error','You cant approve an already approved request!');
        }
    }

    public function topup_wallet_by_admin(Request $request){
        $wallet = Wallet::find($request->input('wallet_id'));
        if($wallet != null){
            if($request->input('amount') > 0){
                $wallet->available =  $wallet->available + $request->input('amount');
                $wallet->save();
                $wallet_log = new WalletLog();
                $wallet_log->wallet_id =$wallet->id;
                $wallet_log->status = "Top-up By Admin";
                $wallet_log->amount = $request->input('amount');
                $wallet_log->message = 'A Top-up of Amount '.number_format($request->input('amount'),2).' USD Added Against Wallet ' . $wallet->wallet_token . ' At ' . now()->format('d M, Y h:i a'). ' By Administration';
                $wallet_log->save();
                return redirect()->back()->with('success','Wallet Top-up Successfully!');
            }


        }else{
            return redirect()->back()->with('error','Wallet Not Found!');
        }

    }

}

