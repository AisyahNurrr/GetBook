<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';  // INI WAJIB ADA!!!

    protected $fillable = ['nama', 'deskripsi', 'foto'];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id'); // pastikan foreign key benar
    }
}