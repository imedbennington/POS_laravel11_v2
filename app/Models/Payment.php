<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'client_id',
        'item_id',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function item()
    {
        return $this->belongsTo(FoodDrink::class);
    }
}
