<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Order Code</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Subtotal</th>
                <th>Tax</th>
                <th>Grand Total</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->code }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>{{ optional($order->user->profile)->name ?? $order->user->email }}</td>
                    <td>{{ ucfirst($order->status->value) }}</td>
                    <td>{{ number_format($order->sub_total, 2, '.', ',') }}</td>
                    <td>{{ number_format($order->tax_amount, 2, '.', ',') }}</td>
                    <td>{{ number_format($order->grand_total, 2, '.', ',') }}</td>
                    <td>{{ $order->payment->method->name ?? '-' }}</td>
                    <td>{{ $order->payment->status->value ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
