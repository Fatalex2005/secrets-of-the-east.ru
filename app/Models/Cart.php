<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['total', 'quantity', 'user_id', 'product_color_size_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function productColorSize()
    {
        return $this->belongsTo(ProductColorSize::class);
    }
}
