<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtributTambahan extends Model
{
    protected $table = 'atribut_tambahans';
    protected $primaryKey = 'id_atribut';
    
    protected $fillable = [
        'id_aplikasi',
        'nama_atribut',
        'nilai_atribut'
    ];

    protected $guarded = ['id_atribut'];

    public function aplikasi()
    {
        return $this->belongsTo(Aplikasi::class, 'id_aplikasi', 'id_aplikasi');
    }
}
