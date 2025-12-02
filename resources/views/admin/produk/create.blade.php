<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header persis dashboard -->
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
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-4xl font-bold text-gray-800">Tambah Produk Baru</h2>
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center gap-3 px-8 py-4 bg-green-600 text-white font-bold text-lg rounded-full hover:bg-green-700 transition shadow-lg hover:shadow-2xl transform hover:-translate-y-2">
                    Kembali ke Dashboard
                </a>
            </div>

            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-3">Nama Produk</label>
                        <input type="text" name="nama" required class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none text-lg" 
                               placeholder="Contoh: Buku Novel Dilan 1990" value="{{ old('nama') }}">
                    </div>

                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-3">Genre</label>
                        <select name="kategori_id" required class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none text-lg">
                            <option value="">-- Pilih Genre --</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-3">Harga (Rp)</label>
                        <input type="number" name="harga" required class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none text-lg" 
                               placeholder="75000" value="{{ old('harga') }}">
                    </div>

                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-3">Stock</label>
                        <input type="number" name="stock" required min="0" class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none text-lg" 
                               value="{{ old('stock', 1) }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-lg font-semibold text-gray-700 mb-3">Deskripsi</label>
                        <textarea name="deskripsi" rows="5" class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none text-lg" 
                                  placeholder="Ceritakan tentang produk ini...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-lg font-semibold text-gray-700 mb-3">Foto Produk</label>
                        <input type="file" name="foto" accept="image/*" required class="w-full px-6 py-4 border-2 border-dashed border-gray-400 rounded-xl text-lg file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                    </div>
                </div>

                <div class="text-center pt-10">
                    <button type="submit" class="px-16 py-6 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold text-2xl rounded-full hover:shadow-2xl transition transform hover:-translate-y-3">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </main>

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