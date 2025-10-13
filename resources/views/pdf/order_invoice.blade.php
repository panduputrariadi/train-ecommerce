<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->code }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1, h2, h3 { margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        .text-right { text-align: right; }
        .no-border td { border: none; }
    </style>
</head>
<body>
    <h2>Invoice: {{ $order->code }}</h2>
    <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status->value) }}</p>

    <hr>

    <h3>Customer</h3>
    <p>
        {{ optional($order->user->profile)->name ?? $order->user->email }}<br>
        Email: {{ $order->user->email }}
    </p>

    <h3>Order Details</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->details as $item)
                @php
                    $snapshot = $item->product_data ? json_decode($item->product_data, true) : null;
                @endphp
                <tr>
                    <td>{{ $snapshot['name'] ?? $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">
                        {{ number_format($snapshot['price'] ?? $item->unit_price, 2, '.', ',') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item->discount_amount ?? 0, 2, '.', ',') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($item->total_price, 2, '.', ',') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="no-border">
        <tr>
            <td style="width:70%;"></td>
            <td>
                <table>
                    <tr>
                        <th>Subtotal</th>
                        <td class="text-right">{{ number_format($order->sub_total, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <th>Tax</th>
                        <td class="text-right">{{ number_format($order->tax_amount, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <th>Grand Total</th>
                        <td class="text-right"><strong>{{ number_format($order->grand_total, 2, '.', ',') }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <hr>

    <h3>Payment Information</h3>
    @if ($order->payment)
        <p>
            <strong>Method:</strong> {{ $order->payment->method->name ?? 'N/A' }}<br>
            <strong>Amount:</strong> ${{ number_format($order->payment->amount, 2, '.', ',') }}<br>
            <strong>Status:</strong> {{ ucfirst($order->payment->status->value) }}<br>
            @if ($order->payment->paid_at)
                <strong>Paid At:</strong> {{ $order->payment->paid_at->format('M d, Y H:i') }}
            @endif
        </p>
    @else
        <p>No payment recorded.</p>
    @endif

    @if ($order->note)
        <hr>
        <h3>Notes</h3>
        <p>{{ $order->note }}</p>
    @endif
</body>
</html>
