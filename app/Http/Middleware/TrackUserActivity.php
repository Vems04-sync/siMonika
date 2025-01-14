<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;

class TrackUserActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            $path = $request->path();
            $method = $request->method();
            
            // Tentukan tipe aktivitas dan detail berdasarkan request
            $aktivitas = $this->getActivityType($method, $path);
            $detail = $this->getActivityDetail($request, $method, $path);
            
            // Simpan log
            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => $aktivitas,
                'modul' => $this->getModulName($path),
                'detail' => $detail,
                'tipe_aktivitas' => strtolower($method)
            ]);
        }

        return $response;
    }

    private function getActivityType($method, $path)
    {
        if ($method === 'POST') return 'Menambahkan';
        if ($method === 'PUT' || $method === 'PATCH') return 'Mengubah';
        if ($method === 'DELETE') return 'Menghapus';
        return 'Mengakses';
    }

    private function getActivityDetail($request, $method, $path)
    {
        // Untuk aplikasi
        if (str_contains($path, 'aplikasi')) {
            $nama = $request->nama ?? $request->route('nama');
            $oldNama = $request->route('nama');
            
            // Debug lebih detail dengan data spesifik
            logger('DETAIL PERUBAHAN APLIKASI:', [
                'METHOD' => $method,
                'PATH' => $path,
                'OLD_NAME' => $oldNama,
                'NEW_NAME' => $nama,
                'ALL_REQUEST_DATA' => $request->all(),
                'ROUTE_PARAMETERS' => $request->route() ? $request->route()->parameters() : 'No Route'
            ]);

            if ($method === 'POST') {
                return "Menambahkan aplikasi: $nama";
            }
            
            if ($method === 'PUT') {
                $changes = [];
                foreach ($request->all() as $key => $value) {
                    if (!in_array($key, ['_token', '_method'])) {
                        $changes[] = ucfirst($key) . ": $value";
                    }
                }
                return "Mengubah data aplikasi: " . implode(', ', $changes);
            }
            
            if ($method === 'DELETE') {
                return "Menghapus aplikasi: $nama";
            }
        }

        // Untuk atribut
        if (str_contains($path, 'atribut')) {
            $namaAtribut = $request->nama_atribut ?? '';
            $aplikasi = $request->id_aplikasi ? \App\Models\Aplikasi::find($request->id_aplikasi)->nama : '';
            
            if ($method === 'POST') return "Menambahkan atribut '$namaAtribut' pada aplikasi $aplikasi";
            if ($method === 'PUT') return "Mengubah atribut '$namaAtribut' pada aplikasi $aplikasi";
            if ($method === 'DELETE') return "Menghapus atribut dari aplikasi $aplikasi";
        }

        return '';
    }

    private function getModulName($path)
    {
        if (str_contains($path, 'aplikasi')) return 'Aplikasi';
        if (str_contains($path, 'atribut')) return 'Atribut';
        return 'Sistem';
    }
}
