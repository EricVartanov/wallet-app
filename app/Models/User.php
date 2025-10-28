<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'balance'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    // All transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Transfers sent by user
    public function sentTransfers()
    {
        return $this->hasMany(Transaction::class, 'from_user_id');
    }

    // Transfers received by user
    public function receivedTransfers()
    {
        return $this->hasMany(Transaction::class, 'to_user_id');
    }
}
