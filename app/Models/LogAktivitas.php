<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    
    protected $fillable = [
        'user_id',
        'aktivitas',
        'tipe_aktivitas',
        'modul',
        'detail'
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id', 'id_user');
    }
}
