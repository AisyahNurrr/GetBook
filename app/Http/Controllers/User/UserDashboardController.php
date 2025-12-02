<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Cart; 
use App\Models\Kategori;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori untuk filter
        $kategoris = Kategori::all();

        // Query produk dengan filter
        $produks = Produk::with('kategori')
            ->when($request->search, function ($query) use ($request) {
                return $query->where('nama', 'like', '%' . $request->search . '%');
            })
            ->when($request->kategori, function ($query) use ($request) {
                return $query->where('kategori_id', $request->kategori);
            })
            ->latest()
            ->paginate(12); // ← WAJIB PAGINATE, biar nggak lemot + bisa pindah halaman
            // ->get(); ← hapus ini, ganti jadi paginate!

        // Ambil keranjang user
        $cart = Cart::with('produk')
            ->where('user_id', auth()->id())
            ->get();

        // Kirim ke view + tambahin kategori yang aktif (buat highlight)
        $kategoriAktif = $request->kategori 
            ? Kategori::find($request->kategori) 
            : null;

        return view('user.dashboard', compact('produks', 'kategoris', 'cart', 'kategoriAktif'));
    }
}