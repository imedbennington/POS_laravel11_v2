<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Waiter;

class Admin extends User
{
    use HasFactory;
   // use HasRoles;
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];
    protected $table = 'admins';

    public function __construct(array $attributes = [])
    {
        // Call the parent constructor to keep Eloquent functionality
        parent::__construct($attributes);
    }

    public function providers()
    {
        return $this->hasMany(Provider::class); // Each admin manages many providers
    }

    public function waiters()
    {
        return $this->hasMany(Waiter::class); // Each admin manages many providers
    }

    public function providers2()
    {
        return $this->hasMany(Provider2::class); // Each admin manages many providers
    }
}
