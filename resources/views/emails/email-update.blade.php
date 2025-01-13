@component('mail::message')
    # Perubahan Email Admin

    Halo {{ $nama }},

    Email akun admin Anda di SiMonika telah diubah dari {{ $oldEmail }} menjadi {{ $newEmail }}.

    Password Anda tetap sama seperti sebelumnya.

    Jika Anda tidak mengenali perubahan ini, silakan hubungi Super Admin.

    Terima kasih,<br>
    {{ config('app.name') }}
@endcomponent
