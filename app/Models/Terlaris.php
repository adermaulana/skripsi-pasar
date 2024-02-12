<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Product;

class Terlaris extends Model
{
    use HasFactory;

    protected $fillable=['user_id','quantity'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
