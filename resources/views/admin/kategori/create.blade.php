<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Genre • Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header Admin (sama kayak semua halaman admin lu) -->
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
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto mt-12 px-6 pb-20">
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-10 py-10">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-5xl font-bold text-white">Tambah Genre Buku</h2>
                        <p class="text-indigo-100 mt-3 text-lg">Buat genre baru untuk mengelompokkan buku</p>
                    </div>
                    <a href="{{ route('admin.kategori.index') }}" 
                       class="px-10 py-5 bg-white/20 backdrop-blur-sm text-white font-bold text-lg rounded-full hover:bg-white/30 transition shadow-lg transform hover:-translate-y-1">
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="p-10">
                <form action="{{ route('admin.kategori.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- Nama Kategori -->
                        <div>
                            <label class="block text-lg font-semibold text-gray-700 mb-3">Nama Genre</label>
                            <input type="text" name="nama" required 
                                   class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:border-indigo-500 focus:outline-none text-lg @error('nama') border-red-500 @enderror"
                                   placeholder="Contoh: Romance, Horror, Fantasy">
                            @error('nama')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-lg font-semibold text-gray-700 mb-3">Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" rows="4"
                                      class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:border-indigo-500 focus:outline-none text-lg @error('deskripsi') border-red-500 @enderror"
                                      placeholder="Jelaskan tentang kategori ini..."></textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    <!-- Tombol Simpan -->
                    <div class="text-center pt-8">
                        <button type="submit" 
                                class="px-16 py-6 bg-gradient-to-r from-green-600 to-emerald-700 text-white font-bold text-2xl rounded-full hover:shadow-2xl transform hover:-translate-y-3 transition duration-300 shadow-xl">
                            Simpan Genre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Script Dropdown -->
    <script>
        document.getElementById('menuButton').addEventListener('click', e => {
            e.stopPropagation();
            document.getElementById('dropdown').classList.toggle('hidden');
        });
        document.addEventListener('click', () => document.getElementById('dropdown').classList.add('hidden'));

        @if(session('success'))
            Swal.fire('Berhasil!', '{{ session('success') }}', 'success');
        @endif
    </script>
</body>
</html>