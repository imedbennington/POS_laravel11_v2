<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodOrder extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'food_id', 'total_price', 'order_date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function food()
    {
        return $this->belongsTo(FoodDrink::class, 'food_id');
    }
}
