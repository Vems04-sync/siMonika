<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtributTambahan extends Model
{
    protected $table = 'atribut_tambahans';
    protected $primaryKey = 'id_atribut';
    
    protected $fillable = [
        'nama_atribut',
        'tipe_data',
        'enum_options'
    ];

    protected $casts = [
        'enum_options' => 'array'
    ];

    public function getEnumOptionsAttribute($value)
    {
        return is_string($value) ? json_decode($value, true) : $value;
    }

    public function setEnumOptionsAttribute($value)
    {
        $this->attributes['enum_options'] = is_array($value) ? json_encode($value) : $value;
    }

    public function aplikasis()
    {
        return $this->belongsToMany(Aplikasi::class, 'aplikasi_atribut', 'id_atribut', 'id_aplikasi')
                    ->withPivot('nilai_atribut')
                    ->withTimestamps();
    }
}
