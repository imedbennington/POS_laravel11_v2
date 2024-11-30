<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'provider_id', 'admin_id'];

    public function provider()
    {
        return $this->belongsTo(Provider2::class, 'provider_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
