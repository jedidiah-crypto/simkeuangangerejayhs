<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\KategoriKeuangan;
use App\Models\Donatur;
use App\Models\Rekening;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function show()
    {
        return view('import.index');
    }

    public function process(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File harus dipilih',
            'file.mimes' => 'File harus berformat Excel (.xlsx, .xls) atau CSV',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        try {
            $file = $request->file('file');
            $data = Excel::toArray([], $file);
            $rows = $data[0] ?? [];

            if (empty($rows)) {
                return back()->with('error', 'File Excel kosong atau tidak valid');
            }

            $header = $rows[0] ?? [];
            $imported = 0;
            $errors = [];

            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                if (empty(array_filter($row))) {
                    continue;
                }

                try {
                    $result = $this->processRow($row, $header, $i + 1);
                    if ($result['success']) {
                        $imported++;
                    } else {
                        $errors[] = $result['error'];
                    }
                } catch (\Exception $e) {
                    $errors[] = "Baris " . ($i + 1) . ": " . $e->getMessage();
                }
            }

            if ($imported > 0) {
                $message = "Berhasil import $imported data";
                if (!empty($errors)) {
                    $message .= ". " . count($errors) . " baris ada error.";
                    return back()->with('success', $message)->with('errors', $errors);
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Tidak ada data yang berhasil diimport. ' . implode(' ', $errors));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function processRow($row, $header, $rowNumber)
    {
        $data = [];
        foreach ($header as $index => $column) {
            $value = $row[$index] ?? null;
            $data[strtolower(trim($column))] = trim($value);
        }

        $type = strtolower($data['type'] ?? '');
        if (!in_array($type, ['pemasukan', 'pengeluaran'])) {
            return ['success' => false, 'error' => "Baris $rowNumber: Type harus 'pemasukan' atau 'pengeluaran'"];
        }

        $tanggal = $this->parseDate($data['tanggal'] ?? '');
        if (!$tanggal) {
            return ['success' => false, 'error' => "Baris $rowNumber: Format tanggal tidak valid (gunakan YYYY-MM-DD atau DD/MM/YYYY)"];
        }

        $nominal = floatval(str_replace(['.', ','], '', $data['nominal'] ?? 0));
        if ($nominal <= 0) {
            return ['success' => false, 'error' => "Baris $rowNumber: Nominal harus lebih dari 0"];
        }

        $kategori = trim($data['kategori'] ?? '');
        if (empty($kategori)) {
            return ['success' => false, 'error' => "Baris $rowNumber: Kategori tidak boleh kosong"];
        }

        $kategoriId = KategoriKeuangan::where('nama', $kategori)->first()?->id;
        if (!$kategoriId) {
            return ['success' => false, 'error' => "Baris $rowNumber: Kategori '$kategori' tidak ditemukan"];
        }

        $metode = trim($data['metode'] ?? 'Tunai');
        if (!in_array($metode, ['Tunai', 'Transfer'])) {
            return ['success' => false, 'error' => "Baris $rowNumber: Metode harus 'Tunai' atau 'Transfer'"];
        }

        if ($type === 'pemasukan') {
            return $this->importPemasukan($data, $tanggal, $nominal, $kategoriId, $rowNumber);
        } else {
            return $this->importPengeluaran($data, $tanggal, $nominal, $kategoriId, $metode, $rowNumber);
        }
    }

    private function importPemasukan($data, $tanggal, $nominal, $kategoriId, $rowNumber)
    {
        try {
            $rekeningId = null;
            if (!empty($data['rekening_sumber'])) {
                $rekeningId = Rekening::where('nama', trim($data['rekening_sumber']))
                    ->first()?->id;
                if (!$rekeningId) {
                    return ['success' => false, 'error' => "Baris $rowNumber: Rekening '" . $data['rekening_sumber'] . "' tidak ditemukan"];
                }
            }

            Pemasukan::create([
                'nomor_transaksi' => 'IMP-' . Str::random(12),
                'tanggal' => $tanggal,
                'nominal' => $nominal,
                'kategori_id' => $kategoriId,
                'metode' => trim($data['metode'] ?? ''),
                'sumber_dana' => trim($data['sumber_dana'] ?? ''),
                'keterangan' => trim($data['keterangan'] ?? ''),
                'rekening_id' => $rekeningId,
                'created_by' => auth()->id(),
            ]);

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => "Baris $rowNumber: " . $e->getMessage()];
        }
    }

    private function importPengeluaran($data, $tanggal, $nominal, $kategoriId, $metode, $rowNumber)
    {
        try {
            $rekeningId = null;
            if (!empty($data['rekening_sumber'])) {
                $rekeningId = Rekening::where('nama', trim($data['rekening_sumber']))
                    ->first()?->id;
                if (!$rekeningId) {
                    return ['success' => false, 'error' => "Baris $rowNumber: Rekening '" . $data['rekening_sumber'] . "' tidak ditemukan"];
                }
            }

            Pengeluaran::create([
                'nomor_transaksi' => 'IMP-' . Str::random(12),
                'tanggal' => $tanggal,
                'nominal' => $nominal,
                'kategori_id' => $kategoriId,
                'metode' => $metode,
                'rekening_id' => $rekeningId,
                'keterangan' => trim($data['keterangan'] ?? ''),
                'status' => 'setuju',
                'created_by' => auth()->id(),
            ]);

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => "Baris $rowNumber: " . $e->getMessage()];
        }
    }

    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        $dateString = trim($dateString);

        // Try YYYY-MM-DD format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
            return $dateString;
        }

        // Try DD/MM/YYYY format
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateString, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            return "$year-$month-$day";
        }

        // Try DD-MM-YYYY format
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $dateString, $matches)) {
            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $year = $matches[3];
            return "$year-$month-$day";
        }

        return null;
    }
}
