{{-- resources/views/user/about.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami • GETBOOK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .gradient-text {
            background: linear-gradient(to right, #2563eb, #7c3aed);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-20 right-0 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-10 left-40 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="container mx-auto px-6 py-16 max-w-6xl">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-7xl font-black mb-6">
                    <span class="gradient-text">GETBOOK</span>
                </h1>
                <p class="text-2xl md:text-3xl font-medium text-gray-700">Get Your Dream Book</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
                <div class="order-2 md:order-1">
                    <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&q=80" 
                         alt="Tumpukan buku estetik" 
                         class="rounded-2xl shadow-2xl w-full object-cover">
                </div>
                <div class="order-1 md:order-2 space-y-6">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-800">Selamat Datang di GETBOOK!</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        GETBOOK adalah toko buku online yang hadir untuk memudahkan kamu menemukan, membaca, dan memiliki buku-buku impianmu — kapan pun dan di mana pun.
                    </p>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Mulai dari novel best-seller, buku pengembangan diri, komik, manga, buku anak, hingga literatur akademik — semuanya ada di sini dengan harga terbaik dan pengiriman cepat ke seluruh Indonesia.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl p-10 md:p-16 mb-16">
                <h2 class="text-4xl font-bold text-center text-gray-800 mb-12">Kenapa Harus GETBOOK?</h2>
                <div class="grid md:grid-cols-3 gap-10">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-book text-3xl text-blue-600"></i>
                        </div>
                        <h3 class="text-2xl font-semibold mb-3">Koleksi Terlengkap</h3>
                        <p class="text-gray-600">Ratusan ribu judul buku dari penerbit lokal hingga internasional.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-truck text-3xl text-purple-600"></i>
                        </div>
                        <h3 class="text-2xl font-semibold mb-3">Pengiriman Cepat</h3>
                        <p class="text-gray-600">Same-day shipping untuk Jabodetabek, gratis ongkir untuk pembelian tertentu.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-6 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shield-alt text-3xl text-indigo-600"></i>
                        </div>
                        <h3 class="text-2xl font-semibold mb-3">100% Original</h3>
                        <p class="text-gray-600">Semua buku dijamin asli dari penerbit resmi. No bajakan!</p>
                    </div>
                </div>
            </div>

            <div class="text-center>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-8">Siap Menemukan Buku Baru?</h2>
                <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                    Yuk, mulai petualangan literasimu bersama GETBOOK. Satu klik, ribuan cerita menanti!
                </p>
                <a href="{{ route('user.dashboard') }}" 
                   class="inline-block px-16 py-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-2xl rounded-full hover:shadow-2xl transform hover:scale-105 transition duration-300 shadow-lg">
                    <i class="fas fa-arrow-left mr-3"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 12s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>

</body>
</html>