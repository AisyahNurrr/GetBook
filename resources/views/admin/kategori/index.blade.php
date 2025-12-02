{{-- resources/views/admin/kategori/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Genre • Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .table-row:hover { background-color: #f8fafc; transition: all 0.2s; }
        .badge { @apply px-3 py-1 text-xs font-semibold rounded-full; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Header Admin -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Admin • Daftar Genre</h1>
            <div class="relative">
                <button id="menuButton" class="flex items-center gap-2 text-gray-700 hover:text-gray-900 font-medium">
                    <span>Admin</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border overflow-hidden z-50">
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" class="w-full text-left px-6 py-4 text-red-600 hover:bg-red-50 font-bold transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto mt-10 px-6 pb-20">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            <!-- Header Section -->
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-10 py-12 text-white">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h2 class="text-4xl md:text-5xl font-black">Kelola Genre Buku</h2>
                        <p class="text-indigo-100 mt-3 text-lg opacity-90">Atur semua genre yang tersedia di GetBook</p>
                    </div>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-8 py-4 bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-full font-bold text-lg transition transform hover:-translate-y-1 shadow-lg">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.kategori.create') }}" 
                           class="px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-full font-bold text-lg shadow-xl transform hover:-translate-y-1 transition flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Genre
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabel Genre -->
            <div class="p-8">
                @if($kategoris->count() > 0)
                    <div class="overflow-x-auto rounded-2xl border border-gray-200">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                                <tr>
                                    <th class="px-8 py-5 text-left text-sm font-bold uppercase tracking-wider">No</th>
                                    <th class="px-8 py-5 text-left text-sm font-bold uppercase tracking-wider">Nama Genre</th>
                                    <th class="px-8 py-5 text-left text-sm font-bold uppercase tracking-wider">Slug</th>
                                    <th class="px-8 py-5 text-left text-sm font-bold uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-8 py-5 text-center text-sm font-bold uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($kategoris as $index => $kategori)
                                    <tr class="table-row">
                                        <td class="px-8 py-6 text-center font-medium text-gray-600">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="font-bold text-gray-900 text-lg">{{ $kategori->nama }}</div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <code class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-lg font-mono">
                                                {{ $kategori->slug }}
                                            </code>
                                        </td>
                                        <td class="px-8 py-6 text-gray-600 max-w-md">
                                            {{ $kategori->deskripsi ? Str::limit($kategori->deskripsi, 100) : '<span class="text-gray-400 italic">Tidak ada deskripsi</span>' }}
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('admin.kategori.edit', $kategori->id) }}"
                                                   class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold rounded-full hover:shadow-lg transform hover:-translate-y-1 transition text-sm">
                                                    Edit
                                                </a>
                                                <button type="button"
                                                        onclick="confirmDelete({{ $kategori->id }}, '{{ addslashes($kategori->nama) }}')"
                                                        class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white font-bold rounded-full hover:shadow-lg transform hover:-translate-y-1 transition text-sm">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-20">
                        <div class="text-9xl mb-6 text-gray-200">Folder</div>
                        <h3 class="text-4xl font-bold text-gray-700 mb-4">Belum Ada Genre</h3>
                        <p class="text-xl text-gray-500 mb-8">Yuk tambah genre pertama untuk toko buku kamu!</p>
                        <a href="{{ route('admin.kategori.create') }}" 
                           class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold text-xl rounded-full shadow-2xl hover:shadow-3xl transform hover:-translate-y-2 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Genre Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Hidden Delete Forms -->
    @foreach($kategoris as $kategori)
        <form id="delete-form-{{ $kategori->id }}" 
              action="{{ route('admin.kategori.destroy', $kategori->id) }}" 
              method="POST" class="hidden">
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

        // Konfirmasi hapus dengan nama genre
        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus Genre?',
                html: `Yakin ingin menghapus genre <strong class="text-red-600">${nama}</strong>?<br><br>Data akan hilang permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Notifikasi flash
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}'
            });
        @endif
    </script>
</body>
</html>