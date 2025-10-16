<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Pembayaran #{{ $order->code }}</title>
</head>
<body>
    <h2>Halo, {{ $user->profile->name ?? $user->email }}</h2>

    <p>Pembayaran Anda telah berhasil kami konfirmasi.</p>
    <p>Berikut detail pesanan Anda:</p>

    <ul>
        <li><strong>Kode Order:</strong> {{ $payment->order->code }}</li>
        <li><strong>Total:</strong> Rp {{ number_format($payment->order->grand_total, 0, ',', '.') }}</li>
        <li><strong>Status:</strong> {{ $payment->order->status->getLabel() }} </li>
    </ul>

    <p>Invoice PDF terlampir pada email ini.</p>
    <p>Terima kasih telah berbelanja bersama kami!</p>
</body>
</html>
