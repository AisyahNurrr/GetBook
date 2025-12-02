<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;

class UserAboutController extends Controller
{
    public function index()
    {
        // Ambil 3 produk acak untuk ditampilkan
        $bukuRandom = Produk::inRandomOrder()->take(3)->get();
        return view('user.about', compact('bukuRandom'));
    }
}
