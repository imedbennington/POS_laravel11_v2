<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'product_id',
        'provider_id',
        'quantity',
        'total_price',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
