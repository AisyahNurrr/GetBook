{{-- resources/views/user/transactions/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi • GetBook</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen py-8">

<div class="max-w-5xl mx-auto px-5">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-800">Riwayat Transaksi</h2>
        <a href="{{ route('user.dashboard') }}" 
           class="flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-700 font-medium py-3 px-6 rounded-full shadow-md transition">
            Kembali
        </a>
    </div>

    @forelse($transaksi as $trx)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 border-l-6 transition-all hover:shadow-xl
            {{ $trx->status === 'pending' ? 'border-yellow-500' : '' }}
            {{ $trx->status === 'dikirim' ? 'border-blue-500' : '' }}
            {{ $trx->status === 'selesai' ? 'border-green-500' : '' }}
            {{ $trx->status === 'dibatalkan' ? 'border-red-500' : 'border-gray-300' }}">

            <div class="p-6">
               ady

                <!-- Header Transaksi -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            Invoice #{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $trx->created_at->translatedFormat('d M Y • H:i') }} WIB
                        </p>
                    </div>

                    <span class="px-5 py-2 rounded-full text-sm font-bold shadow-sm
                        {{ $trx->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $trx->status === 'dikirim' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $trx->status === 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $trx->status === 'dibatalkan' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700' }}">
                        @switch($trx->status)
                            @case('pending') Menunggu Pembayaran @break
                            @case('dikirim') Sedang Dikirim @break
                            @case('selesai') Selesai @break
                            @case('dibatalkan') Dibatalkan @break
                            @default {{ ucfirst($trx->status) }}
                        @endswitch
                    </span>
                </div>

                <!-- Daftar Item -->
                <div class="space-y-4 mb-6">
                    @foreach($trx->items as $item)
                        <div class="flex items-center gap-4 bg-gray-50 rounded-xl p-4">
                            <img src="{{ $item->produk->foto ? asset('storage/' . $item->produk->foto) : 'https://via.placeholder.com/60x80/efefef/999?text=No+Image' }}"
                                 alt="{{ $item->produk->nama }}"
                                 class="w-14 h-20 object-cover rounded-lg shadow-sm">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 text-sm">{{ $item->produk->nama }}</h4>
                                <p class="text-xs text-gray-600 mt-1">
                                    {{ $item->jumlah }} × Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </p>
                            </div>
                            <p class="font-bold text-gray-800 text-right">
                                Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <!-- Total -->
                <div class="border-t-2 border-dashed border-gray-300 pt-5">
                    <div class="flex justify-between items-center">
                        <p class="text-xl font-bold text-gray-800">Total Belanja</p>
                        <p class="text-2xl font-extrabold text-purple-700">
                            Rp {{ number_format($trx->items->sum(fn($i) => $i->harga * $i->jumlah), 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Aksi -->
                <div class="flex flex-wrap gap-3 mt-6">
                    <a href="{{ route('user.struk', $trx->id) }}"
                       class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-full shadow transition text-sm">
                        Struk PDF
                    </a>

                    @if($trx->status === 'dikirim')
                        <form method="POST" action="{{ route('user.transactions.selesai', $trx->id) }}" class="inline">
                            @csrf
                            <button type="button" onclick="konfirmasiSelesai(this)"
                                    class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-full shadow transition text-sm">
                                Sudah Diterima
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-24 bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl">
            <div class="text-8xl mb-6 opacity-20">Shopping Bag</div>
            <h3 class="text-3xl font-bold text-gray-700 mb-4">Belum Ada Transaksi</h3>
            <p class="text-gray-600 mb-8">Yuk belanja dulu biar ada riwayat!</p>
            <a href="{{ route('user.dashboard') }}" 
               class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-4 px-12 rounded-full shadow-lg transition">
                Mulai Belanja
            </a>
        </div>
    @endforelse
</div>

<script>
    function konfirmasiSelesai(button) {
        Swal.fire({
            title: 'Pesanan sudah sampai?',
            text: "Konfirmasi jika sudah diterima dengan baik",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, sudah!',
            cancelButtonText: 'Belum'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
</body>
</html>