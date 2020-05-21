<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OhMyBrew\ShopifyApp\Models\Shop;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function has_shops(){
        return $this->belongsToMany(Shop::class,'user_shop','user_id','shop_id');
    }
    public function has_imported(){
        return $this->belongsToMany('App\Product','retailer_product_user','user_id','product_id');
    }
    public function has_wallet(){
        return $this->hasOne('App\Wallet','user_id');
    }
    public function has_manager(){
        return $this->belongsTo('App\User','sale_manager_id');
    }
    public function has_users(){
        return $this->hasMany('App\User','sale_manager_id');
    }
    public function has_sales_stores(){
        return $this->hasMany('App\Shop','sale_manager_id');
    }

    public function has_files(){
        return $this->hasMany('App\UserFile','user_id');
    }
    public function has_orders(){
        return $this->hasMany('App\RetailerOrder','user_id');
    }
    public function has_tickets(){
        return $this->hasMany('App\Ticket','user_id');
    }
    public function has_payments(){
        return $this->hasMany(OrderTransaction::class,'user_id');
    }
    public function has_customers(){
        return $this->hasMany(Customer::class,'user_id');
    }
}
