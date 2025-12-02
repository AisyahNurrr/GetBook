<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk • Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header Admin (sama persis kayak create/edit) -->
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
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-10 py-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-4xl font-bold text-white">Daftar Produk</h2>
                        <p class="text-indigo-100 mt-2">Kelola semua produk di GetBook</p>
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-8 py-4 bg-white/20 backdrop-blur-sm text-white font-bold rounded-full hover:bg-white/30 transition shadow-lg">
                            ← Dashboard
                        </a>
                        <a href="{{ route('admin.produk.create') }}" 
                           class="px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-full hover:shadow-2xl transform hover:-translate-y-1 transition shadow-lg">
                            + Tambah Produk
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabel Produk -->
            <div class="p-10">
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                            <tr>
                                <th class="px-6 py-5 text-left text-xs font-bold uppercase tracking-wider">No</th>
                                <th class="px-6 py-5 text-left text-xs font-bold uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-5 text-left text-xs font-bold uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-5 text-left text-xs font-bold uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-5 text-center text-xs font-bold uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-5 text-center text-xs font-bold uppercase tracking-wider">Gambar</th>
                                <th class="px-6 py-5 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($produk as $index => $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-6 text-center text-gray-700 font-medium">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-6">
                                        <div class="font-semibold text-gray-900">{{ $item->nama }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($item->deskripsi, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <span class="px-4 py-2 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                                            {{ $item->kategori->nama ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6 font-bold text-gray-900">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        @if($item->stock > 0)
                                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-bold">
                                                {{ $item->stock }}
                                            </span>
                                        @else
                                            <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-bold">
                                                Habis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" 
                                                 alt="{{ $item->nama }}"
                                                 class="w-20 h-20 object-cover rounded-xl shadow-md mx-auto hover:scale-150 transition duration-300">
                                        @else
                                            <div class="w-20 h-20 bg-gray-200 border-2 border-dashed rounded-xl flex items-center justify-center text-gray-400 text-xs">
                                                No Image
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-6 text-center space-x-3">
                                        <a href="{{ route('admin.produk.edit', $item->id) }}"
                                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold rounded-full hover:shadow-xl transform hover:-translate-y-1 transition">
                                            Edit
                                        </a>
                                        <button type="button"
                                                onclick="confirmDelete({{ $item->id }}, {{ $item->stock }})"
                                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white font-bold rounded-full hover:shadow-xl transform hover:-translate-y-1 transition">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-16 text-gray-500">
                                        <div class="text-6xl mb-4">Empty Box</div>
                                        <p class="text-xl font-medium">Belum ada produk</p>
                                        <p class="text-gray-400">Yuk tambah produk pertama!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Form Hapus (hidden) -->
    @foreach($produk as $item)
        <form id="delete-form-{{ $item->id }}" action="{{ route('admin.produk.destroy', $item->id) }}" method="POST" class="hidden">
            @csrf @method('DELETE')
        </form>
    @endforeach

    <!-- Script -->
    <script>
        // Dropdown logout
        document.getElementById('menuButton').addEventListener('click', e => {
            e.stopPropagation();
            document.getElementById('dropdown').classList.toggle('hidden');
        });
        document.addEventListener('click', () => document.getElementById('dropdown').classList.add('hidden'));

        // Konfirmasi hapus dengan cek stock
        function confirmDelete(id, stock) {
            if (stock > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa Dihapus',
                    text: `Produk ini masih punya stock (${stock}). Kosongkan stock dulu ya!`,
                    confirmButtonColor: '#6366f1'
                });
                return;
            }

            Swal.fire({
                title: 'Yakin hapus produk ini?',
                text: "Data akan hilang permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Notifikasi otomatis
        @if(session('success'))
            Swal.fire('Berhasil!', '{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            Swal.fire('Gagal!', '{{ session('error') }}', 'error');
        @endif
    </script>
</body>
</html>