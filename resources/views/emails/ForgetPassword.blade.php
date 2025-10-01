<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Forget Password Verification Code</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f6f9fc; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f6f9fc; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 500px; background: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 30px;">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <h2 style="margin: 0; font-size: 20px; color: #333333;">
                                {{ config('app.name') }}
                            </h2>
                        </td>
                    </tr>

                    <!-- Title -->
                    <tr>
                        <td>
                            <h3 style="margin: 0 0 15px; font-size: 18px; color: #333333; text-align: center;">
                                Your Forget Password Verification Code
                            </h3>
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="font-size: 15px; color: #555555; line-height: 22px; padding-bottom: 15px;">
                            Hello,
                        </td>
                    </tr>

                    <!-- OTP Box -->
                    <tr>
                        <td align="center" style="padding: 20px 0;">
                            <div style="display: inline-block; padding: 15px 25px; font-size: 24px; font-weight: bold; letter-spacing: 4px; color: #2d3748; background: #f0f4f8; border: 1px solid #e2e8f0; border-radius: 6px;">
                                {{ $otp }}
                            </div>
                        </td>
                    </tr>

                    <!-- Instruction -->
                    <tr>
                        <td style="font-size: 15px; color: #555555; line-height: 22px; padding-bottom: 15px; text-align: center;">
                            This code will expire in <strong>10 minutes</strong>. <br>
                            Please do not share this code with anyone.
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="font-size: 14px; color: #999999; text-align: center; padding-top: 20px; border-top: 1px solid #eaeaea;">
                            Thank you,<br>
                            <strong>{{ config('app.name') }}</strong>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
