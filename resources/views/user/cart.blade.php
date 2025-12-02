<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja • GetBook</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 min-h-screen">

<div class="max-w-5xl mx-auto py-10 px-4">

    <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-10">
        Keranjang Belanja
    </h1>

    <!-- Notifikasi -->
    @if (session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ session('error') }}', timer: 2500, showConfirmButton: false });
        </script>
    @endif

    @if ($items->count() > 0)
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="text-sm font-bold text-gray-700 uppercase tracking-wider">
                            <th class="py-4 px-6 text-left">Foto</th>
                            <th class="py-4 px-6 text-left">Judul Buku</th>
                            <th class="py-4 px-6 text-left">Harga</th>
                            <th class="py-4 px-6 text-center">Jumlah</th>
                            <th class="py-4 px-6 text-center">Subtotal</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $total = 0 @endphp
                        @foreach ($items as $item)
                            @php 
                                $subtotal = $item->produk->harga * $item->jumlah;
                                $total += $subtotal;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-6 px-6">
                                    <img src="{{ $item->produk->foto ? asset('storage/' . $item->produk->foto) : asset('images/no-image.jpg') }}"
                                         alt="{{ $item->produk->nama }}"
                                         class="w-20 h-28 object-cover rounded-lg shadow">
                                </td>
                                <td class="py-6 px-6 font-semibold text-gray-800">
                                    {{ $item->produk->nama }}
                                </td>
                                <td class="py-6 px-6 text-gray-600">
                                    Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                </td>
                                <td class="py-6 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <!-- Kurangi -->
                                        <form action="{{ route('user.cart.update', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-xl font-bold">−</button>
                                        </form>

                                        <span class="w-12 text-center font-bold text-lg">{{ $item->jumlah }}</span>

                                        <!-- TAMBAH (INI YANG DULU SALAH!) -->
                                        <form action="{{ route('user.cart.update', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-xl font-bold">+</button>
                                        </form>
                                    </div>
                                </td>
                                <td class="py-6 px-6 text-center font-bold text-gray-700">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </td>
                                <td class="py-6 px-6 text-center">
                                    <form method="POST" action="{{ route('user.cart.remove', $item->id) }}" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-800 font-bold delete-btn">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total & Checkout -->
            <div class="bg-gray-100 px-8 py-6 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">
                    Total: Rp {{ number_format($total, 0, ',', '.') }}
                </h2>
                <a href="{{ route('user.checkout.form') }}"
                   class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-4 px-10 rounded-full shadow-lg transition transform hover:scale-105">
                    Checkout Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-20">
            <p class="text-2xl text-gray-600 font-medium">
                Keranjangmu masih kosong nih
            </p>
            <a href="{{ route('user.shop') }}" class="mt-6 inline-block bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 px-8 rounded-full">
                Yuk Belanja Sekarang!
            </a>
        </div>
    @endif
</div>

<!-- SweetAlert2 Konfirmasi Hapus -->
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Yakin hapus?',
                text: "Produk ini akan dihapus dari keranjang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>

</body>
</html>