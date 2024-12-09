<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
class Client extends User
{
    use HasFactory, HasRoles;
    protected $table = 'clients';
    protected $guard_name = 'web';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function foodOrders()
    {
        return $this->hasMany(FoodOrder::class);
    }
}
