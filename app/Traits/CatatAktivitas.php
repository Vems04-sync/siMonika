<?php

namespace App\Traits;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

trait CatatAktivitas
{
    protected function catatAktivitas($aktivitas, $tipe, $modul, $detail = null)
    {
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => $aktivitas,
            'tipe_aktivitas' => $tipe,
            'modul' => $modul,
            'detail' => $detail
        ]);
    }
}
