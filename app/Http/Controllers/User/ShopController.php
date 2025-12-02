<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        // Search
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        $produks = $query->latest()->paginate(12);
        $kategoris = Kategori::all();

        return view('user.dashboard', compact('produks', 'kategoris'));
    }

    public function show($slug)
    {
        $produk = Produk::where('slug', $slug)->firstOrFail();
        $related = Produk::where('kategori_id', $produk->kategori_id)
                         ->where('id', '!=', $produk->id)
                         ->latest()
                         ->take(4)
                         ->get();

        return view('user.produk-show', compact('produk', 'related'));
    }
}