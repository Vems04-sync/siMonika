<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplikasi extends Model
{
    use HasFactory;

    protected $table = 'aplikasis';
    protected $primaryKey = 'id_aplikasi';

    protected $fillable = [
        'nama',
        'opd',
        'uraian',
        'tahun_pembuatan',
        'jenis',
        'basis_aplikasi',
        'bahasa_framework',
        'database',
        'pengembang',
        'lokasi_server',
        'status_pemakaian'
    ];

    public function atributs()
    {
        return $this->hasMany(AtributTambahan::class, 'id_aplikasi', 'id_aplikasi');
    }
}
