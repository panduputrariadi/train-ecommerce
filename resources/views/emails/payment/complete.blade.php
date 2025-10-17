<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payment Confirmation - Order #{{ $order['code'] ?? 'N/A' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 40px 20px;
        }
        .email-container {
            background-color: #fff;
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 22px;
            margin: 0;
        }
        .content p {
            line-height: 1.6;
            font-size: 15px;
        }
        .details {
            background-color: #f8f8f8;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .details li {
            list-style: none;
            padding: 5px 0;
            font-size: 15px;
        }
        .details strong {
            display: inline-block;
            width: 130px;
        }
        .footer {
            text-align: center;
            font-size: 13px;
            color: #777;
            border-top: 1px solid #eee;
            margin-top: 25px;
            padding-top: 15px;
        }
        .btn {
            display: inline-block;
            background-color: #3498db;
            color: #fff !important;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Thank You for Your Payment!</h1>
        </div>

        <div class="content">
            <p>Hi {{ $profile['name'] ?? $user['email'] ?? 'Customer' }},</p>
            <p>Your payment has been successfully confirmed. Below are the details of your order:</p>

            <ul class="details">
                <li><strong>Order Code:</strong> {{ $order['code'] ?? 'N/A' }}</li>
                <li><strong>Total Amount:</strong> Rp {{ number_format($order['grand_total'] ?? 0, 0, ',', '.') }}</li>
                <li><strong>Status:</strong> {{ $order['status']['label'] ?? $order['status'] ?? 'N/A' }}</li>
            </ul>

            <p>You can find your official invoice attached to this email.</p>

            <p>
                <a href="#" class="btn">View Your Order</a>
            </p>

            <p>Thank you for shopping with <strong>{{ config('app.name') }}</strong>!
            We hope to serve you again soon.</p>
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
