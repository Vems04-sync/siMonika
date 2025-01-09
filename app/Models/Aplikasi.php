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
        'status_pemakaian',
        'tahun_pembuatan',
        'jenis',
        'basis_aplikasi',
        'bahasa_framework',
        'uraian'
    ];
}
