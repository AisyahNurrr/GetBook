<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\TransactionItem; // ✅ Import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            (new \App\Http\Middleware\RoleMiddleware)->handle($request, function () {}, 'admin');
            return $next($request);
        });
    }

    public function index()
    {
        $produk = Produk::with('kategori')->get();
        return view('admin.produk.index', compact('produk'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.produk.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama'        => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'stock'       => 'required|integer|min:0',
            'deskripsi'   => 'nullable|string',
            'foto'        => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $produk   = Produk::findOrFail($id);
        $kategori = Kategori::all();

        return view('admin.produk.edit', compact('produk', 'kategori'));
    }

    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama'        => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'stock'       => 'required|integer|min:0',
            'deskripsi'   => 'nullable|string',
            'foto'        => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Produk $produk)
    {
        // ✅ Cek stok masih ada
        if ($produk->stock > 0) {
            return redirect()->back()->with('error', 'Produk tidak bisa dihapus karena stok masih ada!');
        }

        // ✅ Cek apakah produk sedang ada di transaksi pending/dikirim
        $sedangDiproses = TransactionItem::where('produk_id', $produk->id)
            ->whereHas('transaction', function ($q) {
                $q->whereIn('status', ['pending', 'dikirim']);
            })
            ->exists();

        if ($sedangDiproses) {
            return redirect()->back()->with('error', 'Produk tidak bisa dihapus karena sedang dalam transaksi aktif!');
        }

        // Hapus foto jika ada
        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus');
    }
}
