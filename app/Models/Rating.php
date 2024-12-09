<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['food_drink_id', 'user_id', 'rating', 'review'];

    public function foodDrink()
    {
        return $this->belongsTo(FoodDrink::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
