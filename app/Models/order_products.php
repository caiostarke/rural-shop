<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_products extends Model
{
    protected $fillable = [
        'product_id',
        'order_id',
        'price',
        'quantity',
    ];

    public function product() {
        $this->belongsTo(Product::class);
    }

    public function order() {
        $this->belongsTo(Order::class);
    }


    use HasFactory;
}
