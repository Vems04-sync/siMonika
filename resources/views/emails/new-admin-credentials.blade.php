<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { padding: 30px; max-width: 600px; margin: auto; }
        .credentials { 
            background: #f8f9fa; 
            padding: 20px; 
            margin: 20px 0; 
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        .header { color: #2c3e50; margin-bottom: 25px; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Selamat Datang di Sistem Informasi Monitoring Aplikasi (SI MONIKA)</h2>
        </div>

        <p>Dengan hormat,</p>
        <p>Kepada Bapak/Ibu {{ $nama }},</p>

        <p>Kami informasikan bahwa akun administrator SI MONIKA telah berhasil dibuat untuk Anda. Sistem ini merupakan platform terpadu untuk monitoring dan evaluasi aplikasi yang digunakan di lingkungan Dinas Komunikasi dan Informatika.</p>
        
        <p>Berikut adalah informasi kredensial akun Anda:</p>
        
        <div class="credentials">
            <p><strong>Email Administrator:</strong> {{ $email }}</p>
            <p><strong>Kata Sandi Sementara:</strong> {{ $password }}</p>
        </div>

        <p>Demi keamanan dan kerahasiaan data, kami sangat menyarankan agar Anda segera melakukan langkah-langkah berikut:</p>
        <ol>
            <li>Akses halaman login SI MONIKA melalui: <a href="{{ url('/login') }}">{{ url('/login') }}</a></li>
            <li>Masuk menggunakan kredensial di atas</li>
            <li>Segera ubah kata sandi default dengan kata sandi baru yang kuat</li>
        </ol>

        <p>Beberapa hal yang perlu diperhatikan:</p>
        <ul>
            <li>Jaga kerahasiaan kredensial akun Anda</li>
            <li>Gunakan kata sandi yang kuat (minimal 8 karakter, kombinasi huruf, angka, dan simbol)</li>
            <li>Lakukan logout setelah selesai menggunakan sistem</li>
        </ul>

        <div class="footer">
            <p>Jika Anda mengalami kendala dalam mengakses sistem atau memiliki pertanyaan lebih lanjut, silakan menghubungi tim support kami melalui:</p>
            <p>Email: simonikait@gmail.com<br>
            Telepon: (0821-3906-9782)</p>

            <p>Hormat kami,<br>
            <strong>Tim SIMONIKA</strong><br>
            Sistem Informasi Monitoring Aplikasi</p>
        </div>
    </div>
</body>
</html>
