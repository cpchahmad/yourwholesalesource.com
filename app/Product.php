<?php

namespace App;

use App\Traits\ProductVariantTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{


    protected $fillable = ['title','price','compare_price','cost','quantity','sku','recommended_price','description','type','vendor'];

    use ProductVariantTrait;
    public function hasVariants(){
        return $this->hasMany('App\ProductVariant');
    }
    public function has_images(){
        return $this->hasMany('App\Image','product_id');
    }
    public function has_categories(){
        return $this->belongsToMany('App\Category','category_product','product_id','category_id');
    }

    public function has_subcategories(){
        return $this->belongsToMany('App\SubCategory','subcategory_product','product_id','subcategory_id');
    }

    public function has_sub_sub_categories(){
        return $this->belongsToMany('App\SubSubCategory','sub_sub_category_product','product_id','sub_sub_category_id');
    }
    public function has_platforms(){
        return $this->belongsToMany('App\WarnedPlatform','product_platform','product_id','platform_id');
    }
    public function has_tabs(){
        return $this->hasMany('App\AdditionalTab','product_id');
    }

    public function has_preferences(){
        return $this->belongsToMany('App\Shop','product_shop','product_id','shop_id');
    }

    public function has_non_shopify_user_preferences(){
        return $this->belongsToMany('App\User','product_user','product_id','user_id');
    }

    public function has_retailer_products(){
        return $this->hasMany(RetailerProduct::class,'linked_product_id');
    }
    public function has_imported(){
        return $this->belongsToMany('App\Shop','retailer_product_shop','product_id','shop_id');
    }
    public function has_tiered_prices(){
        return $this->hasMany(TieredPrice::class);
    }

    public function has_warehouse_inventory() {
        return $this->hasMany(WarehouseInventory::class);
    }

    public function isUpdated($shop) {
       if(RetailerProduct::where('shop_id', $shop->id)->where('linked_product_id', $this->id)->exists() &&
          Notification::where('type_id', $this->id)->where('type', 'Product')->where('sub_type', 'Product Variant Added')->exists() &&
          RetailerProduct::where('shop_id', $shop->id)->where('linked_product_id', $this->id)->first()->hasVariants()->count() < $this->hasVariants()->count())
       {
           return true;
       }

       return false;
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function getStoreLinkAttribute() {
        $slug = Str::slug($this->title);
        return "https://wefullfill.com/product/".$slug;
    }

    public function getAdminLinkAttribute() {
        return "https://wefullfill.com/wp-admin/post.php?post=". $this->woocommerce_id ."&action=edit";
    }



    public function categoriesget(){
        return $this->belongsToMany(Category::class, 'category_product');
    }

}
