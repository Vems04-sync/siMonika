<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewAdminCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $email;
    public $password;

    public function __construct($nama, $email, $password)
    {
        $this->nama = $nama;
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Kredensial Akun Admin SiMonika')
                    ->view('emails.new-admin-credentials');
    }
}
