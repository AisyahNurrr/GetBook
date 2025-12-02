@if ($transaksi->status !== 'pending')
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            padding: 30px;
            color: #1f2937;
            background-color: #f9fafb;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 10px;
        }

        .sub-header {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .info p {
            margin: 4px 0;
            color: #374151;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid #e5e7eb;
        }

        th, td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #facc15;
            color: #1f2937;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f3f4f6;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-style: italic;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            GetBook
        </div>
        <div class="sub-header">
            📄 Struk Pembelian #{{ $transaksi->id }}
        </div>

        <div class="info">
            <p><strong> Nama:</strong> {{ $transaksi->user->name }}</p>
            <p><strong> Alamat:</strong> {{ $transaksi->alamat }}</p>
            <p><strong> Telepon:</strong> {{ $transaksi->telepon }}</p>
            <p><strong> Metode Pembayaran:</strong> {{ ucfirst($transaksi->metode_pembayaran) }}</p>
            <p><strong> Status:</strong> {{ ucfirst($transaksi->status) }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th> Produk</th>
                    <th> Harga</th>
                    <th> Jumlah</th>
                    <th> Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($transaksi->items as $item)
                    @php $subtotal = $item->harga * $item->jumlah; $total += $subtotal; @endphp
                    <tr>
                        <td>{{ $item->produk->nama }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3" align="right">Total</td>
                    <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            Terima kasih telah berbelanja di GetBook 📚
        </div>
    </div>
</body>
</html>
@else
    <div class="text-center text-red-600 font-bold mt-20">
        🚫 Transaksi masih dalam status <strong>Pending</strong>. Struk belum tersedia.
    </div>
@endif
