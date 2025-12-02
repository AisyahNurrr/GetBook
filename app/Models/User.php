<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke keranjang
    public function cart()
    {
        return $this->hasMany(\App\Models\Cart::class);
    }

    // Relasi pesan yang dikirim user ini
    public function sentMessages()
    {
        return $this->hasMany(\App\Models\Message::class, 'sender_id');
    }

    // Relasi pesan yang diterima user ini
    public function receivedMessages()
    {
        return $this->hasMany(\App\Models\Message::class, 'receiver_id');
    }

    // Relasi umum messages (gabungan)
    public function messages()
    {
        return $this->hasMany(\App\Models\Message::class, 'sender_id')
                    ->orWhere('receiver_id', $this->id);
    }
}
