<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Produk;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::with('produk')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.cart', compact('items'));
    }

    // DIUBAH JADI MENERIMA MODEL PRODUK LANGSUNG → LEBIH BERSIH & AMAN!
    public function add(Produk $produk)
    {
        // Cek stok dulu
        if ($produk->stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok produk ini habis.');
        }

        $cart = Cart::where('user_id', Auth::id())
                    ->where('produk_id', $produk->id)
                    ->first();

        if ($cart) {
            if ($cart->jumlah + 1 > $produk->stock) {
                return redirect()->back()->with('error', 'Jumlah di keranjang tidak boleh melebihi stok!');
            }
            $cart->jumlah += 1;
            $cart->save();
        } else {
            Cart::create([
                'user_id'     => Auth::id(),
                'produk_id'   => $produk->id,
                'jumlah'      => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Yeay! Buku berhasil ditambahkan ke keranjang');
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->with('produk')
                    ->firstOrFail();

        if ($request->action === 'increase') {
            if ($cart->jumlah >= $cart->produk->stock) {
                return redirect()->back()->with('error', 'Stok tidak cukup untuk menambah lagi!');
            }
            $cart->increment('jumlah');
        } 
        elseif ($request->action === 'decrease') {
            if ($cart->jumlah <= 1) {
                $cart->delete();
                return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
            }
            $cart->decrement('jumlah');
        }

        return redirect()->back()->with('success', 'Keranjang diperbarui!');
    }

    public function remove($id)
    {
        $cart = Cart::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->first();

        if ($cart) {
            $cart->delete();
            return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Item tidak ditemukan.');
    }
}