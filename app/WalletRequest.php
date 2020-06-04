<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletRequest extends Model
{
   protected $fillable = [
       'user_id', 'wallet_id','amount','bank_name','cheque','cheque_title','notes','status','type'
   ];
}
