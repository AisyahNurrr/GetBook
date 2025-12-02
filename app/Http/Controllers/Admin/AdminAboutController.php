<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;

class AdminAboutController extends Controller
{
    public function index()
    {
        $bukuRandom = Produk::inRandomOrder()->take(3)->get();
        return view('admin.about', compact('bukuRandom'));
    }
}
