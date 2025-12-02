{{-- resources/views/chat/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Admin • Toko Buku Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .chat-bubble {
            max-width: 75%;
            padding: 14px 18px;
            border-radius: 20px;
            margin-bottom: 16px;
            animation: fadeIn 0.35s ease-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            word-wrap: break-word;
        }
        .from-admin { 
            background: linear-gradient(135deg, #3b82f6, #2563eb); 
            color: white; 
            align-self: flex-end; 
            border-bottom-right-radius: 4px; 
        }
        .from-user { 
            background: white; 
            color: #1f2937; 
            align-self: flex-start; 
            border: 1px solid #e5e7eb; 
            border-bottom-left-radius: 4px; 
        }
        .time { font-size: 11px; opacity: 0.75; margin-top: 6px; text-align: right; font-weight: 500; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

        .user-item {
            transition: all 0.2s;
        }
        .user-item:hover { background: #eff6ff; }
        .user-item.active { background: #dbeafe; font-weight: 600; color: #1e40af; }
        .badge-unread { @apply bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full min-w-[20px] text-center; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header Admin -->
    <header class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-7 py-3 bg-yellow-400 hover:bg-yellow-500 text-black font-bold rounded-full shadow-lg transition transform hover:scale-105">
                ← Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-black text-gray-800">Live Chat Admin</h1>
            <div class="w-32"></div>
        </div>
    </header>

    <!-- Main Chat Layout -->
    <div class="flex-1 max-w-7xl mx-auto p-6 flex gap-6">

        <!-- Sidebar: Daftar User -->
        <div class="w-80 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800">Daftar User</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $users->count() }} user aktif</p>
            </div>
            <div class="flex-1 overflow-y-auto">
                @forelse($users as $u)
                    @php
                        // Hitung unread hanya dari user ini ke admin
                        $unread = $u->unread_count ?? \App\Models\Message::where('sender_id', $u->id)
                                    ->where('receiver_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                    @endphp
                    <a href="{{ route('chat.index', ['user_id' => $u->id]) }}"
                       class="user-item flex justify-between items-center p-4 border-b border-gray-100 {{ request('user_id') == $u->id ? 'active' : '' }}">
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-gray-800 truncate">{{ $u->name }}</div>
                            <div class="text-xs text-gray-500">Terakhir aktif: {{ $u->last_message_at ?? '—' }}</div>
                        </div>
                        @if($unread > 0)
                            <span class="badge-unread">{{ $unread > 99 ? '99+' : $unread }}</span>
                        @endif
                    </a>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <p>Belum ada user yang chat</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="flex-1 bg-white rounded-3xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden">
            @if($selectedUser)
                <!-- Header Chat -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 font-bold text-xl shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-2xl">
                            {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                        </div>
                        <div>
                            <div>{{ $selectedUser->name }}</div>
                            <div class="text-sm opacity-90">Online </div>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div id="chatBox" class="flex-1 overflow-y-auto p-8 space-y-6">
                    @forelse($messages as $m)
                        <div class="flex {{ $m->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div class="chat-bubble {{ $m->sender_id == auth()->id() ? 'from-admin' : 'from-user' }}">
                                <div class="font-medium leading-relaxed">{{ $m->message }}</div>
                                <div class="time">
                                    {{ $m->sender_id == auth()->id() ? 'Anda' : $selectedUser->name }}
                                    • {{ $m->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-24 text-gray-400">
                            <svg class="w-20 h-20 mx-auto mb-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-2xl font-bold text-gray-600 mb-2">Belum ada pesan</p>
                            <p class="text-lg">Kirim pesan pertama untuk memulai!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Form Kirim -->
                <form method="POST" action="{{ route('chat.send') }}" class="p-6 bg-gray-50 border-t border-gray-200">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">
                    <div class="flex gap-4">
                        <input name="message" required autocomplete="off" placeholder="Ketik balasan..."
                               class="flex-1 px-6 py-4 rounded-full border border-gray-300 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 text-lg font-medium transition">
                        <button type="submit"
                                class="px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold rounded-full shadow-lg transition transform hover:scale-105">
                            Kirim
                        </button>
                    </div>
                </form>

            @else
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <svg class="w-24 h-24 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2h2m4 0h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2V10a2 2 0 012-2z"/>
                        </svg>
                        <p class="text-2xl font-bold text-gray-700">Pilih user untuk mulai chat</p>
                        <p class="text-gray-500 mt-2">Semua pesan user akan muncul di sini</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Auto scroll ke paling bawah
        const chatBox = document.getElementById('chatBox');
        if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

        // Toast sukses kirim pesan
        @if(session('chat_success'))
            Swal.fire({
                icon: 'success',
                title: 'Pesan terkirim!',
                timer: 1500,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timerProgressBar: true
            });
        @endif
    </script>
</body>
</html>