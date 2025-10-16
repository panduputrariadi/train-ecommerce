<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border-bottom: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>
    <h2>Invoice Pembayaran</h2>
    <p><strong>Kode Order:</strong> {{ $order->code }}</p>
    <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}</p>
    <p><strong>Nama Pelanggan:</strong> {{ $user->profile->name ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->details as $item)
                <tr>
                    <td>{{ $item->product_data->name ?? $item->product->name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total: Rp {{ number_format($order->grand_total, 0, ',', '.') }}</h3>
</body>
</html>
