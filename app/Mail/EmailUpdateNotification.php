<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $newEmail;
    public $oldEmail;

    public function __construct($nama, $newEmail, $oldEmail)
    {
        $this->nama = $nama;
        $this->newEmail = $newEmail;
        $this->oldEmail = $oldEmail;
    }

    public function build()
    {
        return $this->markdown('emails.email-update')
            ->subject('Perubahan Email Admin SiMonika');
    }
}
