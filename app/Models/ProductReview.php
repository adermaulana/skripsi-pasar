<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable=['user_id','product_id','rate_jenisproduk','rate_ketersediaanproduk','rate_pelayanan','rate_kebersihantoko','rate_kualitasproduk','rate_jumlahpenjualan','store_id','average','review','status'];

    public function user_info(){
        return $this->hasOne('App\User','id','user_id');
    }

    public static function getAllReview(){
        return ProductReview::with('user_info')->paginate(10);
    }
    public static function getAllUserReview(){
        return ProductReview::where('user_id',auth()->user()->id)->with('user_info')->paginate(10);
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'store_id');
    }

}
