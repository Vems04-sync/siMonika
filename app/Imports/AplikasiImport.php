<?php

namespace App\Imports;

use App\Models\Aplikasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AplikasiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Aplikasi([
            'nama' => $row['nama'],
            'status_pemakaian' => $row['status_pemakaian'],
            'tahun_pembuatan' => $row['tahun_pembuatan'],
            'jenis' => $row['jenis'],
            'basis_aplikasi' => $row['basis_aplikasi'],
            'bahasa_framework' => $row['bahasa_framework'],
            'uraian' => $row['uraian'],
        ]);
    }
} 