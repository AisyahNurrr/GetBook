<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi • Admin BukuKita</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen py-10">

<div class="max-w-6xl mx-auto px-5">

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-800">Daftar Transaksi</h1>
            <p class="text-gray-600 mt-1">Kelola pesanan pelanggan</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-700 font-bold py-3 px-6 rounded-full shadow-lg transition">
            Kembali
        </a>
    </div>

    <!-- Card List Transaksi (Lebih Ringkas & Cantik!) -->
    <div class="space-y-6">
        @forelse($transactions as $trx)
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 border-l-8
                {{ $trx->status === 'pending' ? 'border-yellow-500' : '' }}
                {{ $trx->status === 'dikirim' ? 'border-blue-500' : '' }}
                {{ $trx->status === 'selesai' ? 'border-green-500' : '' }}
                {{ $trx->status === 'dibatalkan' ? 'border-red-500' : 'border-gray-300' }}">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Kiri: Info Utama -->
                    <div class="md:col-span-2">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-purple-700">
                                    #{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}
                                </h3>
                                <p class="text-gray-600">
                                    {{ $trx->user->name }} • {{ $trx->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-extrabold text-green-600">
                                    Rp {{ number_format($trx->items->sum(fn($i) => $i->harga * $i->jumlah), 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $trx->items->count() }} item{{ $trx->items->count() > 1 ? 's' : '' }}
                                </p>
                            </div>
                        </div>

                        <!-- Detail -->
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Telepon</p>
                                <p class="font-semibold">{{ $trx->telepon ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Metode Bayar</p>
                                <p class="font-semibold text-purple-700">
                                    {{ ucfirst(str_replace('_', ' ', $trx->metode_pembayaran ?? 'Tidak diketahui')) }}
                                </p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-500">Alamat</p>
                                <p class="font-medium">{{ $trx->alamat ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Daftar Produk -->
                        <div class="mt-5 pt-5 border-t border-gray-200">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Produk yang dibeli:</p>
                            <div class="space-y-2">
                                @foreach($trx->items as $item)
                                    <div class="flex justify-between text-sm">
                                        <span>{{ $item->jumlah }} × {{ $item->produk->nama }}</span>
                                        <span class="font-medium">
                                            Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: Status + Aksi -->
                    <div class="flex flex-col justify-between items-end">
                        <!-- Status Badge -->
                        <div class="text-right mb-6">
                            <span class="inline-block px-6 py-3 rounded-full font-bold text-lg
                                {{ $trx->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $trx->status === 'dikirim' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $trx->status === 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $trx->status === 'dibatalkan' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $trx->status === 'pending' ? 'Menunggu Konfirmasi' : '' }}
                                {{ $trx->status === 'dikirim' ? 'Sedang Dikirim' : '' }}
                                {{ $trx->status === 'selesai' ? 'Selesai' : '' }}
                                {{ $trx->status === 'dibatalkan' ? 'Dibatalkan' : '' }}
                            </span>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="w-full">
                            @if($trx->status === 'pending')
                                <form method="POST" action="{{ route('admin.transactions.konfirmasi', $trx->id) }}" class="w-full">
                                    @csrf
                                    <button type="button" onclick="konfirmasiKirim(this)"
                                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:scale-105">
                                        Kirim Pesanan
                                    </button>
                                </form>
                            @elseif($trx->status === 'dikirim')
                                <div class="bg-blue-100 text-blue-700 font-bold py-4 px-6 rounded-xl text-center">
                                    Menunggu User Konfirmasi
                                </div>
                            @elseif($trx->status === 'selesai')
                                <div class="bg-green-100 text-green-700 font-bold py-4 px-6 rounded-xl text-center">
                                    Transaksi Selesai
                                </div>
                            @else
                                <div class="bg-gray-100 text-gray-500 py-4 px-6 rounded-xl text-center">
                                    Tidak Ada Aksi
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-24 bg-white rounded-3xl shadow-lg">
                <div class="text-8xl mb-6 opacity-20">Belum ada transaksi</div>
                <p class="text-2xl text-gray-600">Tunggu pelanggan mulai belanja ya!</p>
            </div>
        @endforelse
    </div>
</div>

<!-- SweetAlert Konfirmasi -->
<script>
    function konfirmasiKirim(button) {
        Swal.fire({
            title: 'Kirim pesanan ini?',
            text: "Status akan berubah jadi 'Dikirim'",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>

</body>
</html>