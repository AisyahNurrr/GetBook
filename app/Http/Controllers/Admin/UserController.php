<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
{
    // HANYA AMBIL USER YANG ROLE-NYA 'user' (bukan admin)
    $users = User::where('role', 'user')
                 ->latest()
                 ->paginate(10);

    // HANYA HITUNG USER BIASA (bukan admin)
    $total = User::where('role', 'user')->count();

    return view('admin.users.index', compact('users', 'total'));
}
}

