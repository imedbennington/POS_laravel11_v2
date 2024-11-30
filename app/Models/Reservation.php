<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
class Reservation extends Model
{
    protected $fillable = ['client_id', 'table_id', 'reservation_time', 'status'];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
