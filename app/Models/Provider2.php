<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider2 extends User
{
    use HasFactory;
    protected $fillable = ['id','first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'zip', 'country', 'password','admin_id'];
    protected $hidden = [
        'password',
    ];
    protected $table = 'providers';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class); // Each provider is managed by one admin
    }
}
