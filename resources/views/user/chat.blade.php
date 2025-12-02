<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat dengan Admin • sera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(to bottom, #fdf2f8, #f3e8ff); }
        .chat-bubble {
            max-width: 78%;
            padding: 14px 20px;
            border-radius: 22px;
            margin-bottom: 18px;
            animation: fadeIn 0.4s ease-out;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            word-wrap: break-word;
        }
        .from-user { 
            background: linear-gradient(135deg, #ec4899, #db2777); 
            color: white; 
            align-self: flex-end; 
            border-bottom-right-radius: 4px; 
        }
        .from-admin { 
            background: white; 
            color: #1f2937; 
            align-self: flex-start; 
            border: 1px solid #e5e7eb; 
            border-bottom-left-radius: 4px; 
        }
        .time { 
            font-size: 11px; 
            opacity: 0.8; 
            margin-top: 6px; 
            text-align: right; 
            font-weight: 500; 
        }
        @keyframes fadeIn { 
            from { opacity: 0; transform: translateY(12px); } 
            to { opacity: 1; transform: none; } 
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white shadow-lg border-b border-pink-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <a href="{{ route('user.dashboard') }}" 
               class="flex items-center gap-3 text-pink-600 font-bold text-lg hover:text-pink-700 transition">
                ← Kembali
            </a>
            <h1 class="text-2xl font-black text-gray-800">
                Chat dengan Admin
            </h1>
            <div class="w-32"></div>
        </div>
    </header>

    <!-- Quick Messages -->
    <div class="bg-gradient-to-r from-pink-50 to-purple-50 border-b border-pink-100 px-6 py-5">
        <div class="flex flex-wrap justify-center gap-3">
            @php
                $quick = [
                    'Cek status pesanan', 'Cara bayar?', 'Berapa lama kirim?',
                    'Ada promo?', 'Bisa COD?', 'Buku ready?', 'Bisa bungkus kado?', 'Kapan dikirim?'
                ];
            @endphp
            @foreach($quick as $text)
                <button onclick="quickSend('{{ addslashes($text) }}')"
                        class="px-5 py-2.5 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-full shadow-md transition transform hover:scale-105">
                    {{ $text }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Chat Container -->
    <main class="flex-1 max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-3xl shadow-2xl border border-pink-100 flex flex-col h-full" style="max-height: calc(100vh - 260px);">
            
            <!-- Messages Area -->
            <div id="chatBox" class="flex-1 overflow-y-auto p-8 space-y-6">
                @forelse($messages as $m)
                    <div class="flex {{ $m->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="chat-bubble {{ $m->sender_id == auth()->id() ? 'from-user' : 'from-admin' }}">
                            <div class="font-medium leading-relaxed">{{ $m->message }}</div>
                            <div class="time">
                                {{ $m->sender_id == auth()->id() ? 'Kamu' : 'Admin' }}
                                • {{ $m->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-24 text-gray-400">
                        <svg class="w-20 h-20 mx-auto mb-5 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-2xl font-bold text-gray-600 mb-2">Belum ada pesan</p>
                        <p class="text-lg">Mulai chat yuk!</p>
                    </div>
                @endforelse
            </div>

            <!-- Form Kirim Pesan — PASTI JALAN! -->
            <form method="POST" action="{{ route('chat.send') }}" class="p-6 bg-gradient-to-r from-pink-50 to-purple-50 border-t border-pink-100" id="chatForm">
                @csrf
                <!-- AMAN: Ambil ID admin dinamis, bukan hardcode "1" -->
                <input type="hidden" name="receiver_id" value="{{ $admin->id }}">
                
                <div class="flex gap-4">
                    <input name="message" required autocomplete="off" 
                           placeholder="Ketik pesan..."
                           class="flex-1 px-6 py-4 rounded-full border-2 border-pink-200 focus:outline-none focus:border-pink-500 text-lg font-medium transition">
                    <button type="submit"
                            class="px-10 py-4 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-bold rounded-full shadow-lg transition transform hover:scale-105">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Auto scroll
        const chatBox = document.getElementById('chatBox');
        if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;

        // Quick send
        function quickSend(text) {
            const input = document.querySelector('input[name="message"]');
            input.value = text;
            document.getElementById('chatForm').submit();
        }

        // Focus input
        document.querySelector('input[name="message"]').focus();

        // Success toast
        @if(session('chat_success'))
            Swal.fire({
                icon: 'success',
                title: 'Terkirim!',
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