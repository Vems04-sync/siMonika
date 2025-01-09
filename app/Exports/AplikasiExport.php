<?php

namespace App\Exports;

use App\Models\Aplikasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AplikasiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Aplikasi::select('nama', 'opd', 'uraian', 'tahun_pembuatan', 'jenis', 'basis_aplikasi', 'bahasa_framework', 'database','pengembang','lokasi_server','status_pemakaian')->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'OPD',
            'Uraian',
            'Tahun Pembuatan',
            'Jenis',
            'Basis Aplikasi',
            'Bahasa Framework',
            'Database',
            'Pengembang',
            'Lokasi Server',
            'Status Pemakaian'
        ];
    }
} 