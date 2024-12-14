<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = ['waiter_id', 'issue', 'details'];

    // Relationship with the Waiter model
    public function waiter()
    {
        return $this->belongsTo(Waiter::class, 'waiter_id');
    }

    // Relationship with the Admin model
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id'); // Only needed if admins are linked explicitly
    }
}
