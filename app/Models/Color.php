<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'hex'];

    public function productColorSizes(){
        return $this->hasMany(ProductColorSize::class);
    }
}
