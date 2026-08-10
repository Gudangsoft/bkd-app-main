<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 40px 30px;
            color: #333333;
            line-height: 1.6;
        }
        .email-body h2 {
            color: #667eea;
            margin-bottom: 20px;
        }
        .email-body p {
            margin-bottom: 15px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .reset-button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: transform 0.2s;
        }
        .reset-button:hover {
            transform: translateY(-2px);
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .divider {
            border-top: 1px solid #e9ecef;
            margin: 20px 0;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>{{ config('app.name') }}</h1>
        </div>

        <div class="email-body">
            <h2>Halo, {{ $user->name }}!</h2>

            <p>Anda menerima email ini karena kami menerima permintaan untuk mereset password akun Anda.</p>

            <div class="button-container">
                <a href="{{ $url }}" class="reset-button">Reset Password</a>
            </div>

            <div class="info-box">
                <p style="margin: 0;">
                    <strong>⏱️ Penting:</strong> Link reset password ini akan kadaluarsa dalam <strong>{{ $expireTime }} menit</strong>.
                </p>
            </div>

            <p>Jika Anda tidak melakukan permintaan reset password, abaikan email ini dan password Anda tidak akan berubah.</p>

            <div class="divider"></div>

            <p style="font-size: 13px; color: #6c757d;">
                <strong>Catatan Keamanan:</strong><br>
                Jika Anda mengalami kesulitan mengklik tombol "Reset Password", salin dan tempel URL berikut ke browser Anda:<br>
                <span style="word-break: break-all; color: #667eea;">{{ $url }}</span>
            </p>
        </div>

        <div class="email-footer">
            <p>
                Terima kasih,<br>
                <strong>Tim {{ config('app.name') }}</strong>
            </p>
            <p style="margin-top: 10px; font-size: 12px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
