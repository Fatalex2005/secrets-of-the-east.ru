<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $fillable = ['city', 'street', 'house'];

    public function orders(){
        return $this->hasMany(Order::class);
    }
}
