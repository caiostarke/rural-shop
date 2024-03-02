<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'order_products');
    }

    public function order_products(){
        return $this->hasMany(order_products::class);
    } 

    use HasFactory;
}
