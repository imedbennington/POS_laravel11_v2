<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waiter extends User
{
    use HasFactory;
    protected $table = 'waiters';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'basic_salary',
        'days_off',
        'admin_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Default values or specific initialization logic for Waiter
        $this->attributes['basic_salary'] = $this->attributes['basic_salary'] ?? 0;  // Default to 0 if not set
        $this->attributes['days_off'] = $this->attributes['days_off'] ?? 0; // Default to 0 if not set
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
