<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';


    protected $fillable = ['kategori_id', 'nama', 'harga', 'deskripsi', 'foto', 'stock'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // App\Models\Produk.php
    public function transaksi()
{
    return $this->hasMany(\App\Models\Transaction::class, 'produk_id');
}

}

