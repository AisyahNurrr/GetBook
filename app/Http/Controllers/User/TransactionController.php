<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function checkoutForm()
    {
        // PASTIKAN ADA with('produk') → foto & harga muncul!
        $items = Cart::with('produk')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.checkout', compact('items'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'alamat'            => 'required|string',
            'telepon'           => 'required|string|regex:/^[0-9]{10,15}$/',
            'metode_pembayaran' => 'required|string',
        ]);

        $user  = Auth::user();
        $items = Cart::with('produk')
            ->where('user_id', $user->id)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Keranjang kosong!');
        }

        $total = $items->sum(fn($item) => $item->produk->harga * $item->jumlah);

        try {
            DB::transaction(function () use ($user, $items, $request, $total) {
                $transaction = Transaction::create([
                    'user_id'           => $user->id,
                    'alamat'            => $request->alamat,
                    'telepon'           => $request->telepon,
                    'metode_bayar' => $request->metode_pembayaran,
                    'status'            => 'pending',
                    'total'             => $total,
                ]);

                foreach ($items as $item) {
                    $produk = $item->produk;

                    if ($produk->stock < $item->jumlah) {
                        throw new \Exception("Stok {$produk->nama} tidak mencukupi. Tersisa {$produk->stock}");
                    }

                    $produk->decrement('stock', $item->jumlah);

                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'produk_id'      => $produk->id,
                        'jumlah'         => $item->jumlah,
                        'harga'          => $produk->harga,
                    ]);
                }

                // Kosongkan keranjang
                Cart::where('user_id', $user->id)->delete();
            });

            return redirect()->route('user.transactions')
                ->with('success', 'Checkout berhasil! Pesanan sedang diproses.');
        } catch (\Exception $e) {
            return redirect()->route('user.cart')
                ->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }

    public function index()
    {
        // PASTIKAN ADA with('items.produk') → total & foto pasti muncul!
        $transaksi = Transaction::with('items.produk')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.transactions', ['transaksi' => $transaksi]);
    }

    public function terimaPesanan($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $transaction->update(['status' => 'selesai']);

        return back()->with('success', 'Pesanan selesai.');
    }
}