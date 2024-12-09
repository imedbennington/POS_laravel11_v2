<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodDrink extends Model
{
    protected $table = 'food_drinks';
    protected $fillable = ['description', 'unit_price'];

    public function foodOrders()
    {
        return $this->hasMany(FoodOrder::class, 'food_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'food_drink_id');
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }
}

