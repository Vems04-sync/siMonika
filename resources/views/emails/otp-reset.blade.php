<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        .container { 
            padding: 40px;
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
        .header { 
            color: #2b6cb0;
            margin-bottom: 30px;
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        .otp-box { 
            background: #ebf8ff; 
            padding: 25px;
            margin: 25px 0;
            border-radius: 10px;
            border-left: 5px solid #4299e1;
            text-align: center;
            font-size: 24px;
            letter-spacing: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reset Password SIMONIKA</h2>
        </div>

        <p>Halo <strong>{{ $nama }}</strong>,</p>

        <p>Kami menerima permintaan untuk reset password akun SIMONIKA Anda. Berikut adalah kode OTP Anda:</p>
        
        <div class="otp-box">
            {{ $otp }}
        </div>

        <p>Kode OTP ini akan kadaluarsa dalam 5 menit.</p>
        
        <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>

        <div class="footer">
            <p>Hormat kami,<br>
            <strong>Tim SIMONIKA</strong></p>
        </div>
    </div>
</body>
</html>
