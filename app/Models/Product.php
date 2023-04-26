<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'Products';
    protected $guarded = [];

    public function Stock(){
        return $this->hasMany(Stock::class);
    }

    public function getSale(){
        return $this->hasMany('Sale::class');
    }
}
