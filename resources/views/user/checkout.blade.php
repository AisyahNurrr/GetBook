<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout • GetBook</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-50 min-h-screen">

<div class="max-w-3xl mx-auto py-12 px-6">

    <h1 class="text-4xl md:text-5xl font-extrabold text-center text-gray-800 mb-10">
        Checkout Pesananmu
    </h1>

    @if ($items->count() > 0)
        {{-- Ringkasan Pesanan --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                Ringkasan Pesanan
            </h2>

            <div class="space-y-4">
                @php $total = 0 @endphp
                @foreach ($items as $item)
                    <div class="flex justify-between items-center py-3 border-b border-gray-200 last:border-0">
                        <div class="flex items-center gap-4">
                            <img src="{{ $item->produk->foto ? asset('storage/' . $item->produk->foto) : asset('images/no-image.jpg') }}"
                                 alt="{{ $item->produk->nama }}"
                                 class="w-16 h-20 object-cover rounded-lg shadow">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $item->produk->nama }}</p>
                                <p class="text-sm text-gray-500">{{ $item->jumlah }} × Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <p class="font-bold text-gray-700">
                            Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}
                        </p>
                    </div>
                    @php $total += $item->produk->harga * $item->jumlah @endphp
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t-4 border-yellow-400">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-bold text-gray-800">Total Bayar</h3>
                    <p class="text-3xl font-extrabold text-green-600">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Form Checkout --}}
        <form method="POST" action="{{ route('user.checkout.process') }}" class="bg-white rounded-2xl shadow-xl p-8 space-y-8">
            @csrf

            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">Alamat Pengiriman</label>
                <textarea name="alamat" rows="4" required
                          class="w-full px-5 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-yellow-400 transition"
                          placeholder="Contoh: Jl. Merdeka No. 123, Kel. Sukamaju, Kec. Cibiru, Kota Bandung, Jawa Barat 40615"></textarea>
            </div>

            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">Nomor Telepon (WhatsApp)</label>
                <input type="text" name="telepon" required pattern="[0-9]{10,15}"
                       class="w-full px-5 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-yellow-400 transition"
                       placeholder="081234567890">
            </div>

            <div>
                <label class="block text-lg font-bold text-gray-700 mb-2">Metode Pembayaran</label>
                <select name="metode_pembayaran" required
                        class="w-full px-5 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-yellow-400 transition text-gray-700">
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="OVO">OVO</option>
                    <option value="Dana">Dana</option>
                    <option value="Gopay">Gopay</option>
                    <option value="ShopeePay">ShopeePay</option>
                    <option value="Transfer Bank">Transfer Bank (BCA/Mandiri/BNI)</option>
                </select>
            </div>

            {{-- Info Transfer --}}
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-xl border-2 border-yellow-300">
                <p class="text-center font-bold text-gray-800 text-lg mb-2">Silakan Transfer ke Nomor Berikut:</p>
                <p class="text-center text-3xl font-extrabold text-green-600">0812 3456 789</p>
                <p class="text-center text-sm text-gray-600 mt-1">a.n. Admin GetBook</p>
            </div>

            <button type="submit"
                    class="w-full bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-black font-extrabold text-xl py-5 rounded-full shadow-2xl transform transition hover:scale-105">
                Konfirmasi & Proses Pesanan
            </button>
        </form>

    @else
        <div class="text-center py-20 bg-white rounded-2xl shadow-xl">
            <p class="text-3xl font-bold text-red-600 mb-6">Keranjang Kosong</p>
            <p class="text-gray-600 text-lg mb-8">Yuk tambahkan buku dulu sebelum checkout!</p>
            <a href="{{ route('user.shop') }}"
               class="inline-block bg-pink-600 hover:bg-pink-700 text-white font-bold text-xl py-4 px-12 rounded-full shadow-lg transition transform hover:scale-105">
                Belanja Sekarang
            </a>
        </div>
    @endif
</div>

</body>
</html>