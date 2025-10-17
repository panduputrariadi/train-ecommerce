<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order['code'] ?? 'N/A' }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.6;
        }
        h2, h3 {
            margin-bottom: 8px;
            color: #222;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #f8f8f8;
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <h2>Payment Invoice</h2>

    <p><strong>Order Code:</strong> {{ $order['code'] ?? '-' }}</p>
    <p><strong>Date:</strong>
        {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y') : '-' }}
    </p>
    <p><strong>Customer Name:</strong>
        {{ $profile['name'] ?? $user['name'] ?? $user['email'] ?? '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th style="width: 45%">Product</th>
                <th style="width: 10%">Qty</th>
                <th style="width: 20%">Price</th>
                <th style="width: 25%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order['details'] ?? [] as $item)
                <tr>
                    <td>{{ $item['product_data']['name'] ?? $item['product']['name'] ?? '-' }}</td>
                    <td>{{ $item['quantity'] ?? 0 }}</td>
                    <td>Rp {{ number_format($item['unit_price'] ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['total_price'] ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Grand Total: Rp {{ number_format($order['grand_total'] ?? 0, 0, ',', '.') }}</p>

    <div class="footer">
        <p>Thank you for shopping with us!</p>
        <p><strong>{{ config('app.name') }}</strong></p>
    </div>
</body>
</html>
