<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #3498db;">Reset Password SiMonika</h2>
        
        <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
        
        <p>Silakan klik tombol di bawah ini untuk melakukan reset password:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('password.reset', $token) }}" 
               style="background-color: #3498db; 
                      color: white; 
                      padding: 12px 25px; 
                      text-decoration: none; 
                      border-radius: 5px;
                      display: inline-block;">
                Reset Password
            </a>
        </div>
        
        <p>Link reset password ini akan kadaluarsa dalam 60 menit.</p>
        
        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
        
        <hr style="border: 1px solid #eee; margin: 30px 0;">
        
        <p style="color: #666; font-size: 12px;">
            Ini adalah email otomatis. Mohon tidak membalas email ini.<br>
            © {{ date('Y') }} SiMonika - Sistem Monitoring Aplikasi
        </p>
    </div>
</body>
</html> 