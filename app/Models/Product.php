<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['photo', 'name', 'description', 'sex', 'quantity', 'price', 'category_id', 'country_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function productColorSizes()
    {
        return $this->hasMany(ProductColorSize::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
