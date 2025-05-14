<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['total', 'quantity', 'order_id', 'product_color_size_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function productColorSize()
    {
        return $this->belongsTo(ProductColorSize::class);
    }
}
