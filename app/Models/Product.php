<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function order() {
        return $this->belongsToMany(Order::class, 'order_products');
    }

    protected $fillable = [
        'title',
        'description',
        'price',
        'quantity',
        'image',
        'user_id',
    ];
}
