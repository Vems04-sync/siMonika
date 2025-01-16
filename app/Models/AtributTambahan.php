<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtributTambahan extends Model
{
    protected $table = 'atribut_tambahans';
    protected $primaryKey = 'id_atribut';
    
    protected $fillable = [
        'nama_atribut',
        'tipe_data'
    ];

    public function aplikasis()
    {
        return $this->belongsToMany(Aplikasi::class, 'aplikasi_atribut', 'id_atribut', 'id_aplikasi')
                    ->withPivot('nilai_atribut')
                    ->withTimestamps();
    }
}
