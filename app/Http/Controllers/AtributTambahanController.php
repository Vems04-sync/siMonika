<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use App\Models\AtributTambahan;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AtributTambahanController extends Controller
{
    public function index()
    {
        $atributs = AtributTambahan::with('aplikasis')->get();
        $aplikasis = Aplikasi::all();
        return view('atribut.index', compact('atributs', 'aplikasis'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
    
            $validated = $request->validate([
                'nama_atribut' => 'required|string|max:100|unique:atribut_tambahans',
                'tipe_data' => 'required|in:varchar,number,date,text,enum',
                'enum_options' => 'required_if:tipe_data,enum|array'
            ]);
    
            // Prepare enum_options
            $enumOptions = null;
            $enumOptions = null;
            if ($validated['tipe_data'] === 'enum' && !empty($request->enum_options)) {
                $enumOptions = array_filter($request->enum_options, function($value) {
                    return !empty(trim($value));
                });
                $enumOptions = array_values($enumOptions); // Re-index array
            }
    
            $atribut = AtributTambahan::create([
                'nama_atribut' => $validated['nama_atribut'],
                'tipe_data' => $validated['tipe_data'],
                'enum_options' => $enumOptions ? json_encode($enumOptions) : null
            ]);
    
            // Attach to all applications with default value
            $aplikasis = Aplikasi::all();
            foreach ($aplikasis as $aplikasi) {
                $aplikasi->atributTambahans()->attach($atribut->id_atribut, [
                    'nilai_atribut' => $validated['nilai_default'] ?? null
                ]);
            }
    
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Tambah Atribut',
                'tipe_aktivitas' => 'create',
                'modul' => 'Atribut',
                'detail' => sprintf(
                    "Admin %s menambahkan atribut baru '%s' ke %d aplikasi",
                    Auth::user()->nama,
                    $validated['nama_atribut'],
                    count($aplikasis)
                )
            ]);
    
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Atribut berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan atribut: ' . $e->getMessage()
            ], 500);
        }
    }
    

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
    
            $aplikasi = Aplikasi::findOrFail($id);
            $atributUpdates = $request->input('atribut', []);
            $successCount = 0;
            $errors = [];
    
            foreach ($atributUpdates as $atributId => $nilai) {
                try {
                    $atribut = AtributTambahan::findOrFail($atributId);
                    
                    // Update nilai atribut
                    $aplikasi->atributTambahans()->updateExistingPivot($atributId, [
                        'nilai_atribut' => $nilai
                    ]);
    
                    // Log perubahan
                    LogAktivitas::create([
                        'user_id' => Auth::id(),
                        'aktivitas' => 'Update Nilai Atribut',
                        'tipe_aktivitas' => 'update',
                        'modul' => 'Atribut',
                        'detail' => sprintf(
                            "Mengubah nilai atribut '%s' pada aplikasi '%s'",
                            $atribut->nama_atribut,
                            $aplikasi->nama
                        )
                    ]);
    
                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Gagal mengupdate atribut {$atribut->nama_atribut}: " . $e->getMessage();
                }
            }
    
            if ($successCount > 0) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengupdate ' . $successCount . ' atribut'
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => implode("\n", $errors)
                ], 422);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating attributes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate atribut: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $atribut = AtributTambahan::with('aplikasis')->findOrFail($id);
            $namaAtribut = $atribut->nama_atribut;
            $jumlahAplikasi = $atribut->aplikasis->count();

            // Perbaikan format log
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Hapus Atribut',
                'tipe_aktivitas' => 'delete',
                'modul' => 'Atribut',
                'detail' => sprintf(
                    "Admin %s menghapus atribut '%s' dari %d aplikasi",
                    Auth::user()->nama,
                    $namaAtribut,
                    $jumlahAplikasi
                )
            ]);

            $atribut->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Atribut berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus atribut');
        }
    }

    public function detail($id)
    {
        try {
            $atribut = AtributTambahan::with(['aplikasis' => function ($query) {
                $query->orderBy('nama');
            }])->findOrFail($id);

            return response()->json([
                'success' => true,
                'aplikasis' => $atribut->aplikasis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail atribut'
            ], 500);
        }
    }

    public function updateNilai(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $atribut = AtributTambahan::findOrFail($id);
            $aplikasi = Aplikasi::findOrFail($request->id_aplikasi);

            // Validasi berdasarkan tipe data
            $rules = $this->getValidationRules($atribut->tipe_data);
            $validator = Validator::make(
                ['nilai_atribut' => $request->nilai_atribut],
                ['nilai_atribut' => $rules]
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nilai tidak sesuai dengan tipe data ' . $this->getTypeLabel($atribut->tipe_data)
                ], 422);
            }

            $oldValue = $aplikasi->getNilaiAtribut($id);

            $aplikasi->atributTambahans()->updateExistingPivot($id, [
                'nilai_atribut' => $request->nilai_atribut
            ]);

            // Log aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Update Nilai Atribut',
                'tipe_aktivitas' => 'update',
                'modul' => 'Atribut',
                'detail' => "Mengubah nilai atribut '{$atribut->nama_atribut}' pada aplikasi '{$aplikasi->nama}' dari '{$oldValue}' menjadi '{$request->nilai_atribut}'"
            ]);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate nilai atribut'
            ], 500);
        }
    }

    /**
     * Update nilai atribut untuk aplikasi tertentu
     */
    public function updateAtributValues(Request $request, $id)
    {
        try {
            DB::beginTransaction();
    
            $aplikasi = Aplikasi::findOrFail($id);
            $atributUpdates = $request->input('atribut', []);
    
            foreach ($atributUpdates as $atributId => $nilai) {
                $atribut = AtributTambahan::findOrFail($atributId);
                
                // Validasi nilai berdasarkan tipe data
                $rules = $this->getValidationRules($atribut->tipe_data, $atribut->enum_options);
                $validator = Validator::make(
                    ['nilai' => $nilai],
                    ['nilai' => $rules]
                );

                if ($validator->fails()) {
                    throw new \Exception("Nilai tidak valid untuk atribut {$atribut->nama_atribut}");
                }

                // Update nilai atribut
                $aplikasi->atributTambahans()->updateExistingPivot($atributId, [
                    'nilai_atribut' => $nilai
                ]);

                // Log perubahan
                LogAktivitas::create([
                    'user_id' => Auth::id(),
                    'aktivitas' => 'Update Nilai Atribut',
                    'tipe_aktivitas' => 'update',
                    'modul' => 'Atribut',
                    'detail' => sprintf(
                        "Mengubah nilai atribut '%s' pada aplikasi '%s'",
                        $atribut->nama_atribut,
                        $aplikasi->nama
                    )
                ]);
            }
    
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Atribut berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating attribute values: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method untuk mendapatkan aturan validasi berdasarkan tipe data
     */
    private function getValidationRules($tipeData, $enumOptions = null)
    {
        $rules = 'nullable|';
        
        switch ($tipeData) {
            case 'number':
                $rules .= 'numeric';
                break;
            case 'date':
                $rules .= 'date';
                break;
            case 'text':
                $rules .= 'string';
                break;
            case 'enum':
                if ($enumOptions) {
                    $options = is_string($enumOptions) ? json_decode($enumOptions, true) : $enumOptions;
                    $rules .= 'in:' . implode(',', $options);
                }
                break;
            default: // varchar
                $rules .= 'string|max:255';
        }
        
        return $rules;
    }

    private function getTypeLabel($tipeData)
    {
        switch ($tipeData) {
            case 'number':
                return 'Angka';
            case 'date':
                return 'Tanggal';
            case 'varchar':
                return 'Teks (max 255 karakter)';
            case 'text':
                return 'Teks Panjang';
                case 'enum':
                    return 'Pilihan (Enum)';
                default:
                    return 'Teks';
            }
    }
}
