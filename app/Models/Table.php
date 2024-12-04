<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;
class Table extends Model
{
    protected $table = 'tables';
    protected $fillable = ['name', 'number', 'seats', 'status'];

    public function reservation()
    {
        return $this->hasOne(Reservation::class)->where('status', 'pending');
    }
}
