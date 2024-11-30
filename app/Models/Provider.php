<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends User
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class); // Each provider is managed by one admin
    }
}
