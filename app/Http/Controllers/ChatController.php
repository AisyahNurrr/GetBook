<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        // ========================== ADMIN VIEW ==========================
        if ($currentUser->role === 'admin') {
            $users = User::where('role', 'user')
                ->where(function ($q) use ($currentUser) {
                    $q->whereHas('sentMessages', fn($query) => $query->where('receiver_id', $currentUser->id))
                      ->orWhereHas('receivedMessages', fn($query) => $query->where('sender_id', $currentUser->id));
                })
                ->withCount([
                    'sentMessages as unread_count' => fn($q) => 
                        $q->where('receiver_id', $currentUser->id)->where('is_read', false)
                ])
                ->orderByDesc('unread_count')
                ->orderBy('name')
                ->get();

            $selectedUser = null;
            $messages = collect();

            if ($request->filled('user_id')) {
                $selectedUser = User::findOrFail($request->user_id);

                // Tandai semua pesan dari user ini sebagai sudah dibaca
                Message::where('sender_id', $selectedUser->id)
                    ->where('receiver_id', $currentUser->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

                $messages = Message::where(function ($q) use ($currentUser, $selectedUser) {
                    $q->where('sender_id', $currentUser->id)->where('receiver_id', $selectedUser->id);
                })->orWhere(function ($q) use ($currentUser, $selectedUser) {
                    $q->where('sender_id', $selectedUser->id)->where('receiver_id', $currentUser->id);
                })
                ->orderBy('created_at', 'asc')
                ->get();
            }

            return view('admin.chat', compact('users', 'selectedUser', 'messages'));
        }

        // ========================== USER VIEW ==========================
        $admin = User::where('role', 'admin')->firstOrFail();

        // Tandai pesan dari admin sebagai sudah dibaca
        Message::where('sender_id', $admin->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($q) use ($currentUser, $admin) {
            $q->where('sender_id', $currentUser->id)->where('receiver_id', $admin->id);
        })->orWhere(function ($q) use ($currentUser, $admin) {
            $q->where('sender_id', $admin->id)->where('receiver_id', $currentUser->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        return view('user.chat', compact('messages', 'admin'));
    }

    // INI YANG DIPAKE DI ROUTE chat.send
    public function store(Request $request)
    {
        $request->validate([
            'message'     => 'required|string|max:1000|min:1',
            'receiver_id' => 'required|exists:users,id'
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => strip_tags(trim($request->message)),
            'is_read'     => false
        ]);

        return back()->with('chat_success', true);
    }

    // Chatbot otomatis (opsional, tapi keren)
    public function chatbot(Request $request)
    {
        $pesan = strtolower($request->message);

        $balasan = "Admin sedang sibuk nih, tapi sebentar lagi dibales ya!";

        if (str_contains($pesan, 'status') || str_contains($pesan, 'pesanan')) {
            $balasan = "Cek status pesanan bisa di menu Riwayat Transaksi ya kak!";
        } elseif (str_contains($pesan, 'bayar') || str_contains($pesan, 'pembayaran')) {
            $balasan = "Bisa transfer ke BCA/Mandiri/BNI atau QRIS. Konfirmasi otomatis kok!";
        } elseif (str_contains($pesan, 'kirim') || str_contains($pesan, 'pengiriman')) {
            $balasan = "Pengiriman pakai JNE/TIKI/Sicepat. Estimasi 1-3 hari kerja untuk pulau Jawa.";
        }

        Message::create([
            'sender_id'   => User::where('role', 'admin')->first()->id,
            'receiver_id' => Auth::id(),
            'message'     => $balasan,
            'is_read'     => false
        ]);

        return response()->json(['reply' => $balasan]);
    }
}