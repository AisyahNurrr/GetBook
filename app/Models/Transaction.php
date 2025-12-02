<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'telepon',
        'alamat',
        'metode_bayar',     // INI YANG BENAR SESUAI DATABASE KAMU!
        'status',
        'bukti_bayar',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id')
                    ->with('produk');
    }

    public function getTotalFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}