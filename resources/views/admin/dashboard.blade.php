<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Admin Panel</h1>
            <div class="relative">
                <button id="menuButton" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 font-medium">
                    <span>Admin</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border z-50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto mt-12 px-6">
        <div class="bg-white shadow-2xl rounded-2xl p-10">
            <h2 class="text-4xl font-bold text-gray-800 mb-8">Welcome back, Admin!</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <a href="{{ route('admin.users.index') }}" class="block bg-blue-100 p-10 rounded-xl shadow-lg hover:bg-blue-200 hover:shadow-2xl transition transform hover:-translate-y-3 text-center">
                    <h3 class="text-3xl font-bold text-blue-900">User Management</h3>
                    <p class="text-blue-700 mt-4 text-lg">Kelola semua akun user</p>
                </a>

                <a href="{{ route('admin.produk.index') }}" class="block bg-gradient-to-br from-green-400 to-emerald-600 p-10 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-3 text-center text-white">
                    <h3 class="text-3xl font-bold">Kelola Produk</h3>
                    <p class="mt-4 text-lg opacity-90">Tambah, edit, hapus produk</p>
                </a>

                <a href="{{ route('admin.kategori.index') }}" class="block bg-yellow-100 p-10 rounded-xl shadow-lg hover:bg-yellow-200 hover:shadow-2xl transition transform hover:-translate-y-3 text-center">
                    <h3 class="text-3xl font-bold text-yellow-900">Genre</h3>
                    <p class="text-yellow-700 mt-4 text-lg">Atur genre buku</p>
                </a>

                <a href="{{ route('admin.transactions.index') }}" class="block bg-orange-100 p-10 rounded-xl shadow-lg hover:bg-orange-200 hover:shadow-2xl transition transform hover:-translate-y-3 text-center">
                    <h3 class="text-3xl font-bold text-orange-900">Transaksi</h3>
                    <p class="text-orange-700 mt-4 text-lg">Konfirmasi pesanan user</p>
                </a>

                <a href="{{ route('admin.about') }}" class="block bg-gradient-to-br from-purple-500 to-pink-500 p-10 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-3 text-center text-white">
                    <h3 class="text-3xl font-bold">About</h3>
                    <p class="mt-4 text-lg opacity-90">Who made this masterpiece?</p>
                </a>

                <a href="{{ route('chat.index') }}" 
   class="group block bg-gradient-to-br from-blue-500 to-indigo-600 p-10 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-3 text-center text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition"></div>
    <h3 class="text-3xl font-bold">Live Chat</h3>
    <p class="mt-4 text-lg opacity-90">Balas pesan user secara real-time</p>
    <div class="mt-6 flex justify-center">
        <span class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-bold rounded-full shadow-md hover:shadow-lg transition transform hover:scale-110">
            Buka Chat 
        </span>
    </div>
</a>
            </div>
        </div>
    </main>

    <script>
        // Dropdown Logout
        document.getElementById('menuButton').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('dropdown').classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
            document.getElementById('dropdown').classList.add('hidden');
        });

        // SweetAlert dengan tombol Continue
        Swal.fire({
            title: 'Welcome back, Admin',
            text: 'You have successfully logged in.',
            icon: 'success',
            confirmButtonText: 'Continue',
            confirmButtonColor: '#2563eb',
            allowOutsideClick: false,
            allowEscapeKey: false,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xl rounded-full shadow-lg hover:shadow-xl transition transform hover:-translate-y-1'
            }
        });
    </script>
</body>
</html>