<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['order_date', 'total', 'user_id', 'status_id', 'point_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    public function point()
    {
        return $this->belongsTo(Point::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
}
