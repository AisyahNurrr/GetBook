<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home • GetBook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .badge { @apply absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg animate-pulse; }
        .animate-scaleIn { animation: scaleIn 0.3s ease-out; }
        @keyframes scaleIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
        <div class="flex items-center gap-8">
            <nav class="hidden md:flex items-center gap-8 text-gray-700 font-medium">
                <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600 transition font-semibold">Home</a>
                <a href="{{ route('user.about') }}" class="hover:text-blue-600 transition">About Us</a>

                <div class="relative group">
                    <button class="flex items-center gap-1 hover:text-blue-600 transition font-semibold">
                        Kategori
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="{{ route('user.dashboard') }}" class="block px-6 py-3 hover:bg-gray-50 font-medium {{ !request('kategori') ? 'bg-blue-100 text-blue-600' : 'text-gray-700' }}">Semua Buku</a>
                        @foreach($kategoris as $kategori)
                            <a href="{{ route('user.dashboard', ['kategori' => $kategori->id]) }}"
                               class="block px-6 py-3 hover:bg-gray-50 font-medium {{ request('kategori') == $kategori->id ? 'bg-blue-100 text-blue-600' : 'text-gray-700' }}">
                                {{ $kategori->nama }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('user.cart') }}" class="hover:text-blue-600 transition">Cart</a>
                <a href="{{ route('user.transactions') }}" class="hover:text-blue-600 transition">History</a>
                <a href="{{ route('chat.index') }}" class="relative hover:text-blue-600 transition">
                    Chat
                    @php $unread = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count(); @endphp
                    @if($unread > 0)<span class="badge">{{ $unread > 99 ? '99+' : $unread }}</span>@endif
                </a>
            </nav>
        </div>

        <div class="relative">
            <button id="menuButton" class="flex items-center gap-2 text-gray-700 hover:text-blue-600 font-medium">
                <span>{{ auth()->user()->name }}</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border overflow-hidden z-50">
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit" class="w-full text-left px-6 py-4 text-red-600 hover:bg-red-50 font-bold">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-6 py-16">
    <div class="text-center mb-12">
        <h1 class="text-5xl md:text-6xl font-black text-gray-800 mb-3">Selamat Datang, {{ auth()->user()->name }}!</h1>
        <p class="text-xl text-gray-600">Temukan buku favoritmu di toko buku kami.</p>
    </div>

    <div class="max-w-2xl mx-auto mb-16">
        <form action="{{ route('user.dashboard') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" placeholder="Cari buku..." value="{{ request('search') }}"
                   class="flex-1 px-6 py-4 rounded-full border border-gray-300 focus:outline-none focus:border-blue-500 text-lg">
            <button type="submit" class="px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full shadow-lg transition">
                Cari
            </button>
        </form>
    </div>

    <div class="max-w-7xl mx-auto">
        <h3 class="text-2xl font-bold text-gray-800 mb-8">
            {{ $kategoriAktif ? 'Kategori: ' . $kategoriAktif->nama : 'Daftar Buku' }}
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
            @forelse($produks as $produk)
                <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-3 duration-300 cursor-pointer"
                     onclick="openDetailModal({{ $produk->id }})">
                    <div class="relative">
                        @if($produk->foto && file_exists(public_path('storage/' . $produk->foto)))
                            <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}"
                                 class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-gray-200 to-gray-400 flex items-center justify-center">
                                <span class="text-6xl font-black text-white opacity-50">Book</span>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                {{ $produk->kategori->nama ?? 'Umum' }}
                            </span>
                        </div>
                        <div class="absolute top-3 right-3">
                            @if($produk->stock > 0)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">{{ $produk->stock }} tersedia</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-bold rounded-full">Habis</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-gray-800 text-lg line-clamp-2">{{ $produk->nama }}</h4>
                        <p class="text-blue-600 font-bold text-xl mt-3">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-3xl text-gray-500 font-bold">Buku tidak ditemukan</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $produks->appends(request()->query())->links() }}
        </div>
    </div>
</main>

<div id="bookModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 px-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-screen overflow-y-auto animate-scaleIn">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <h2 id="modalTitle" class="text-3xl font-bold text-gray-800"></h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-3xl">×</button>
            </div>
            <div class="grid md:grid-cols-2 gap-10">
                <img id="modalImage" src="" alt="" class="w-full h-96 object-cover rounded-2xl">
                <div class="space-y-6">
                    <div>
                        <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-bold" id="modalKategori"></span>
                        <p class="text-3xl font-bold text-blue-600 mt-4" id="modalHarga"></p>
                    </div>
                    <div id="modalStok" class="text-lg font-medium"></div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Deskripsi</h3>
                        <p id="modalDeskripsi" class="text-gray-700 leading-relaxed"></p>
                    </div>
                    <form id="modalForm" method="POST" class="mt-8">
                        @csrf
                        <button type="submit" id="modalButton" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xl rounded-xl shadow-lg transition">
                            Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.books = @json($produks->getCollection());

    function openDetailModal(id) {
        const book = window.books.find(b => b.id == id);
        if (!book) return;

        document.getElementById('modalTitle').innerText = book.nama;
        document.getElementById('modalKategori').innerText = book.kategori?.nama || 'Umum';
        document.getElementById('modalHarga').innerText = 'Rp ' + Number(book.harga).toLocaleString('id-ID');
        document.getElementById('modalDeskripsi').innerText = book.deskripsi || 'Tidak ada deskripsi.';

        // Foto
        document.getElementById('modalImage').src = book.foto
            ? '{{ asset('storage') }}/' + book.foto
            : 'https://via.placeholder.com/600x800/cccccc/666666?text=No+Image';

        const stokEl = document.getElementById('modalStok');
        const btn    = document.getElementById('modalButton');
        const form   = document.getElementById('modalForm');

        if (book.stock > 0) {
            stokEl.innerHTML = `<span class="text-green-600 font-bold">${book.stock} tersedia</span>`;
            btn.innerText = 'Tambah ke Keranjang';
            btn.disabled = false;
            btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            form.action = "{{ url('/user/cart/add') }}/" + id; // INI YANG BENER!
        } else {
            stokEl.innerHTML = `<span class="text-red-600 font-bold">Stok Habis</span>`;
            btn.innerText = 'Stok Habis';
            btn.disabled = true;
            btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            btn.classList.add('bg-gray-400', 'cursor-not-allowed');
        }

        document.getElementById('bookModal').classList.replace('hidden', 'flex');
    }

    function closeModal() {
        document.getElementById('bookModal').classList.replace('flex', 'hidden');
    }

    document.getElementById('bookModal').addEventListener('click', e => {
        if (e.target === document.getElementById('bookModal')) closeModal();
    });

    document.getElementById('menuButton').addEventListener('click', e => {
        e.stopPropagation();
        document.getElementById('dropdown').classList.toggle('hidden');
    });

    document.addEventListener('click', () => {
        document.getElementById('dropdown').classList.add('hidden');
    });

    @if(session('cart_success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('cart_success') }}',
            timer: 2500,
            showConfirmButton: false
        });
    @endif
</script>
</body>
</html>