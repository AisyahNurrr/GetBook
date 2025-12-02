<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public const HOME = '/redirect';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share jumlah pesan belum dibaca ke layout user
        View::composer('layouts.user', function ($view) {
            if (Auth::check() && Auth::user()->role === 'user') {
                $admin = User::where('role', 'admin')->first();

                $unreadChats = Message::where('sender_id', $admin->id)
                    ->where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->count();

                $view->with('unreadChats', $unreadChats);
            }
        });
    }
}
