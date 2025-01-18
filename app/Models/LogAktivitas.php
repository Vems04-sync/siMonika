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

    // Definisikan tipe aktivitas yang valid
    public const TIPE_AKTIVITAS = [
        'create',
        'read',
        'update',
        'delete',
        'login',
        'logout',
        'auth'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!in_array($model->tipe_aktivitas, self::TIPE_AKTIVITAS)) {
                throw new \Exception('Tipe aktivitas tidak valid');
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id', 'id_user');
    }
}
