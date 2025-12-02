<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Admin Controllers
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminAboutController;

// User Controllers
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\ShopController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\PDFController;
use App\Http\Controllers\User\UserAboutController;

// Lainnya
use App\Http\Controllers\ChatController;

// ========================== GUEST & AUTH ==========================
Route::get('/', fn() => view('welcome'))->name('welcome');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Redirect setelah login
Route::get('/redirect', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.dashboard');
})->middleware('auth')->name('redirect');

// ========================== ADMIN ROUTES ==========================
Route::prefix('admin')
    ->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        Route::resource('produk', ProdukController::class);
        Route::resource('kategori', KategoriController::class);

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/about', [AdminAboutController::class, 'index'])->name('about');

        // Transaksi Admin
        Route::get('/transactions', function () {
            $transactions = \App\Models\Transaction::with(['user', 'items.produk'])->latest()->get();
            return view('admin.transactions.index', compact('transactions'));
        })->name('transactions.index');

        Route::post('/transactions/konfirmasi/{id}', function ($id) {
            \App\Models\Transaction::findOrFail($id)->update(['status' => 'dikirim']);
            return back()->with('success', 'Transaksi dikonfirmasi!');
        })->name('transactions.konfirmasi');
    });

// ========================== USER ROUTES ==========================
Route::prefix('user')
    ->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':user'])
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/shop', [ShopController::class, 'index'])->name('shop');
        Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('produk.show');
        Route::get('/about', [UserAboutController::class, 'index'])->name('about');

        // Keranjang
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart/add/{produk}', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

        // Checkout & Transaksi
        Route::get('/checkout', [TransactionController::class, 'checkoutForm'])->name('checkout.form');
        Route::post('/checkout', [TransactionController::class, 'processCheckout'])->name('checkout.process');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
        Route::post('/transactions/selesai/{id}', [TransactionController::class, 'terimaPesanan'])->name('transactions.selesai');

        // PDF Struk
        Route::get('/struk/{id}', [PDFController::class, 'cetakStruk'])->name('struk');
    });

// ========================== CHAT ROUTES — DIPAKAI BERSAMA USER & ADMIN ==========================
// Semua user (termasuk admin) bisa akses chat
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    
    // Kirim pesan (dipakai user & admin)
    Route::post('/chat/send', [ChatController::class, 'store'])->name('chat.send');

    // Chatbot (khusus user)
    Route::post('/chat/chatbot', [ChatController::class, 'chatbot'])->name('chat.chatbot');

    // Biar route lama gak error (kompatibilitas)
    Route::post('/user/chat/send', fn() => redirect()->back())
        ->name('user.chat.send'); // tetap ada, tapi redirect ke chat.send
});

// Optional: Home route kalau masih dipake
Route::get('/home', fn() => redirect()->route('user.dashboard'))
    ->middleware('auth')
    ->name('home');