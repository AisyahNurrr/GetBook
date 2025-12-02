<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
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
        $kategoris = Kategori::all();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('kategori', 'public');
        }

        Kategori::create($data);
        return redirect()->route('admin.kategori.index');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('kategori', 'public');
        }

        $kategori->update($data);
        return redirect()->route('admin.kategori.index');
    }

public function destroy(Kategori $kategori)
{
    // Load relasi produk dengan aman
    $kategori->load('produk');

    // Cek apakah ada produk dengan stok > 0
    if ($kategori->produk->where('stock', '>', 0)->count() > 0) {
        return back()->with('error', 'Kategori tidak bisa dihapus karena masih ada produk dengan stok tersedia!');
    }

    // Hapus semua produk di kategori ini (opsional)
    $kategori->produk()->delete();

    // Hapus kategori
    $kategori->delete();

    return back()->with('success', 'Kategori dan semua produk di dalamnya berhasil dihapus.');
}

}
