<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColorSize extends Model
{
    protected $fillable = ['quantity', 'product_id', 'color_id', 'size_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
