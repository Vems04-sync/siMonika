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

    public function atributTambahans()
    {
        return $this->belongsToMany(AtributTambahan::class, 'aplikasi_atribut', 'id_aplikasi', 'id_atribut')
                    ->withPivot('nilai_atribut')
                    ->withTimestamps();
    }

    // Method untuk mendapatkan nilai atribut
    public function getNilaiAtribut($id_atribut)
    {
        $atribut = $this->atributTambahans()
            ->where('atribut_tambahans.id_atribut', $id_atribut)
            ->first();
            
        return $atribut ? $atribut->pivot->nilai_atribut : null;
    }

    // Method untuk menambah atribut ke semua aplikasi
    public static function addGlobalAttribute($namaAtribut)
    {
        $aplikasis = self::all();
        
        foreach ($aplikasis as $aplikasi) {
            AtributTambahan::create([
                'id_aplikasi' => $aplikasi->id_aplikasi,
                'nama_atribut' => $namaAtribut,
                'nilai_atribut' => null
            ]);
        }
    }
}
