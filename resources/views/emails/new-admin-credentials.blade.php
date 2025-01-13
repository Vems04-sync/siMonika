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
        .credentials { 
            background: #ebf8ff; 
            padding: 25px;
            margin: 25px 0;
            border-radius: 10px;
            border-left: 5px solid #4299e1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .header { 
            color: #2b6cb0;
            margin-bottom: 30px;
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .footer { 
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #e2e8f0;
            color: #4a5568;
            font-size: 0.95em;
        }
        ol, ul {
            padding-left: 25px;
        }
        li {
            margin-bottom: 10px;
        }
        a {
            color: #4299e1;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .credentials p {
            margin: 10px 0;
            font-size: 1.1em;
        }
        .credentials strong {
            color: #2b6cb0;
        }
        .contact-info {
            background: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .signature {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-style: italic;
        }
        .warning-text {
            color: #e53e3e;
            font-size: 0.9em;
            margin-top: 15px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4299e1;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 15px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #3182ce;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Selamat Datang di SIMONIKA</h2>
            <p style="margin-top: 10px; color: #4a5568;">Sistem Informasi Monitoring Aplikasi</p>
        </div>

        <p>Dengan hormat,</p>
        <p>Kepada Bapak/Ibu <strong>{{ $nama }}</strong>,</p>

        <p>Kami informasikan bahwa akun administrator SIMONIKA telah berhasil dibuat untuk Anda. Sistem ini merupakan platform terpadu untuk monitoring dan evaluasi aplikasi yang digunakan di lingkungan Dinas Komunikasi dan Informatika.</p>
        
        <p>Berikut adalah informasi kredensial akun Anda:</p>
        
        <div class="credentials">
            <p><strong>Email Administrator:</strong> {{ $email }}</p>
            <p><strong>Kata Sandi Sementara:</strong> {{ $password }}</p>
        </div>

        <a href="{{ url('/login') }}" class="button">Akses SIMONIKA Sekarang</a>

        <p>Demi keamanan dan kerahasiaan data, kami sangat menyarankan agar Anda segera melakukan langkah-langkah berikut:</p>
        <ol>
            <li>Akses halaman login SIMONIKA melalui tautan di atas</li>
            <li>Masuk menggunakan kredensial yang telah diberikan</li>
            <li>Segera ubah kata sandi default dengan kata sandi baru yang kuat</li>
        </ol>

        <p><strong>Beberapa hal yang perlu diperhatikan:</strong></p>
        <ul>
            <li>Jaga kerahasiaan kredensial akun Anda</li>
            <li>Gunakan kata sandi yang kuat (minimal 8 karakter, kombinasi huruf, angka, dan simbol)</li>
            <li>Lakukan logout setelah selesai menggunakan sistem</li>
        </ul>

        <p class="warning-text">* Demi keamanan, harap segera ubah kata sandi Anda setelah login pertama kali.</p>

        <div class="footer">
            <p>Jika Anda mengalami kendala dalam mengakses sistem atau memiliki pertanyaan lebih lanjut, silakan menghubungi tim support kami melalui:</p>
            
            <div class="contact-info">
                <p><strong>Email:</strong> simonikait@gmail.com<br>
                <strong>Telepon:</strong> (0821-3906-9782)</p>
            </div>

            <div class="signature">
                <p>Hormat kami,<br>
                <strong>Tim SIMONIKA</strong><br>
                Sistem Informasi Monitoring Aplikasi</p>
            </div>
        </div>
    </div>
</body>
</html>
