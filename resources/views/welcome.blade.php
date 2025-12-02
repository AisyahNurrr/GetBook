<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookStore USK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Navbar Minimal -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm fixed top-0 w-full z-50 border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-8 py-5 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">GetBook</h1>

            <div class="flex items-center space-x-8">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-gray-800 hover:text-gray-900 font-medium border-b-2 border-transparent hover:border-gray-900 transition">
                        Register
                    </a>
                @else
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                       class="px-7 py-3 bg-gray-900 text-white rounded-full font-medium hover:bg-gray-800 transition shadow-sm">
                        Dashboard →
                    </a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- Hero Aesthetic -->
    <main class="flex-1 flex items-center justify-center px-8">
        <div class="text-center max-w-3xl">
            <h1 class="text-6xl md:text-8xl font-bold text-gray-900 mb-6 leading-tight">
                BookStore<br>
            </h1>
            
            <p class="text-xl md:text-2xl text-gray-600 leading-relaxed">
                              Cari buku jadi gampang.<br>
                Semua ada di GetBook.
            </p>

            <!-- Optional subtle decoration -->
            <div class="mt-16 flex justify-center space-x-3">
                <div class="w-2 h-2 bg-indigo-600 rounded-full"></div>
                <div class="w-2 h-2 bg-purple-600 rounded-full"></div>
                <div class="w-2 h-2 bg-pink-600 rounded-full"></div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 text-center text-gray-500 text-sm border-t border-gray-200">
        <p>© {{ date('Y') }} GetBook — Get Your Book</p>
    </footer>

    <!-- SweetAlert kalau ada pesan -->
    @if(session('status'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session("status") }}',
                confirmButtonColor: '#6366f1',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        </script>
    @endif
</body>
</html>