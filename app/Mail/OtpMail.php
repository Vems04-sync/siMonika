<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class OtpMail extends Mailable
{
    public $nama;
    public $otp;

    public function __construct($nama, $otp)
    {
        $this->nama = $nama;
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->view('emails.otp-reset')
                    ->subject('Kode OTP Reset Password SIMONIKA');
    }
}
