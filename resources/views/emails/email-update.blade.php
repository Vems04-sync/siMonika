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
        .email-update-box { 
            background: #ebf8ff; 
            padding: 25px;
            margin: 25px 0;
            border-radius: 10px;
            border-left: 5px solid #4299e1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .warning-text {
            color: #e53e3e;
            font-size: 0.9em;
            margin-top: 15px;
        }
        .footer { 
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.95em;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4299e1;
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            margin: 15px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Pemberitahuan Perubahan Email</h2>
            <p style="margin-top: 10px; color: #4a5568;">SIMONIKA - Sistem Informasi Monitoring Aplikasi</p>
        </div>

        <p>Dengan hormat,</p>
        <p>Kepada Bapak/Ibu <strong>{{ $nama }}</strong>,</p>

        <p>Kami informasikan bahwa telah terjadi perubahan alamat email pada akun SIMONIKA Anda.</p>
        
        <div class="email-update-box">
            <p><strong>Email Lama:</strong> {{ $oldEmail }}</p>
            <p><strong>Email Baru:</strong> {{ $newEmail }}</p>
            <p><strong>Waktu Perubahan:</strong> {{ now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
        </div>

        <p>Password akun Anda tidak mengalami perubahan dan tetap dapat digunakan seperti biasa.</p>

        <div style="text-align: center;">
            <a href="{{ url('/login') }}" class="button">Akses SIMONIKA</a>
        </div>

        <p class="warning-text">* Jika Anda tidak melakukan perubahan ini, segera hubungi Super Admin atau tim support kami.</p>

        <div class="footer">
            <p>Butuh bantuan? Hubungi kami di:</p>
            <p><strong>Email:</strong> simonikait@gmail.com<br>
            <strong>Telepon:</strong> (0821-3906-9782)</p>
            
            <p style="margin-top: 20px;">
                Hormat kami,<br>
                <strong>Tim SIMONIKA</strong><br>
                Sistem Informasi Monitoring Aplikasi
            </p>
        </div>
    </div>
</body>
</html>
