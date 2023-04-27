<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;
    protected $table = 'baskets';   
    protected $fillable = ['sale_id', 'product_id', 'product_price', 'piece', 'total_price'];

    public function getProduct(){
        return $this->belongsToMany('App\Models\Product');
    }
    public function getSale(){
        return $this->belongsTo('App\Models\Sale');
    }
}
