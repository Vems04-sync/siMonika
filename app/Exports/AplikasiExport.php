<?php

namespace App\Exports;

use App\Models\Aplikasi;
use App\Models\AtributTambahan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Log;

class AplikasiExport implements FromCollection, WithHeadings
{
    protected $uniqueAtributs;

    public function __construct()
    {
        // Ambil semua nama atribut unik
        $this->uniqueAtributs = AtributTambahan::select('nama_atribut')
            ->distinct()
            ->pluck('nama_atribut');
    }

    public function headings(): array
    {
        // Kolom dasar
        $headers = [
            'Nama',
            'OPD',
            'Uraian',
            'Tahun Pembuatan',
            'Jenis',
            'Basis Aplikasi',
            'Bahasa/Framework',
            'Database',
            'Pengembang',
            'Lokasi Server',
            'Status Pemakaian'
        ];

        // Tambahkan atribut dinamis ke header
        foreach ($this->uniqueAtributs as $atribut) {
            $headers[] = $atribut;
        }

        return $headers;
    }

    public function collection()
    {
        try {
            Log::info('Fetching data for export');
            
            $aplikasis = Aplikasi::with('atributTambahan')->get();
            
            return $aplikasis->map(function ($aplikasi) {
                // Data dasar
                $row = [
                    $aplikasi->nama,
                    $aplikasi->opd,
                    $aplikasi->uraian,
                    $aplikasi->tahun_pembuatan,
                    $aplikasi->jenis,
                    $aplikasi->basis_aplikasi,
                    $aplikasi->bahasa_framework,
                    $aplikasi->database,
                    $aplikasi->pengembang,
                    $aplikasi->lokasi_server,
                    $aplikasi->status_pemakaian,
                ];

                // Tambahkan nilai atribut dinamis
                foreach ($this->uniqueAtributs as $atributName) {
                    $atribut = $aplikasi->atributTambahan
                        ? $aplikasi->atributTambahan->where('nama_atribut', $atributName)->first()
                        : null;
                    
                    $row[] = $atribut ? $atribut->nilai_atribut : '-';
                }

                return $row;
            });
        } catch (\Exception $e) {
            Log::error('Error in AplikasiExport collection: ' . $e->getMessage());
            throw $e;
        }
    }
}