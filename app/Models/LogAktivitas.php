<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $fillable = [
        'user_id',
        'aktivitas',
        'tipe_aktivitas',
        'modul',
        'detail'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
