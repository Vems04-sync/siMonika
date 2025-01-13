<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LogAktivitasController extends Controller
{
    public function export()
    {
        $logs = LogAktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'Waktu');
        $sheet->setCellValue('B1', 'Admin');
        $sheet->setCellValue('C1', 'Aktivitas');
        $sheet->setCellValue('D1', 'Modul');
        $sheet->setCellValue('E1', 'Detail');

        // Populate data
        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue('A' . $row, $log->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB');
            $sheet->setCellValue('B' . $row, $log->user->nama);
            $sheet->setCellValue('C' . $row, $log->aktivitas);
            $sheet->setCellValue('D' . $row, $log->modul);
            $sheet->setCellValue('E' . $row, $log->detail);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'log_aktivitas_' . date('Y-m-d_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
