<?php

namespace App\Imports;

use App\Models\KategoriKeuangan;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PemasukanPengeluaranImport implements ToCollection, WithHeadingRow
{
    protected $userId;
    protected $importedCount = 0;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $type = strtolower(trim($row['type'] ?? ''));
            if (!in_array($type, ['pemasukan', 'pengeluaran'])) {
                continue;
            }

            $kategoriName = trim($row['kategori'] ?? 'Umum');
            $kategori = KategoriKeuangan::firstOrCreate(
                ['nama' => $kategoriName, 'type' => $type],
                ['type' => $type]
            );

            $tanggal = trim($row['tanggal'] ?? '');
            if (is_numeric($tanggal)) {
                try {
                    $tanggal = ExcelDate::excelToDateTimeObject($tanggal)->format('Y-m-d');
                } catch (\Throwable $e) {
                    $tanggal = Carbon::now()->toDateString();
                }
            }

            try {
                $tanggal = $tanggal ? Carbon::parse($tanggal)->toDateString() : Carbon::now()->toDateString();
            } catch (\Throwable $e) {
                $tanggal = Carbon::now()->toDateString();
            }

            $data = [
                'nomor_transaksi' => strtoupper(($type === 'pemasukan' ? 'PM' : 'PG').now()->format('Ymd').Str::random(6)),
                'tanggal' => $tanggal,
                'nominal' => floatval(str_replace([',', ' '], ['', ''], $row['nominal'] ?? 0)),
                'kategori_id' => $kategori->id,
                'metode' => trim($row['metode'] ?? null),
                'keterangan' => trim($row['keterangan'] ?? null),
                'created_by' => $this->userId,
            ];

            if ($type === 'pemasukan') {
                $data['sumber_dana'] = trim($row['sumber_dana'] ?? null);
                Pemasukan::create($data);
            } else {
                $data['status'] = 'pending';
                Pengeluaran::create($data);
            }

            $this->importedCount++;
        }
    }
}
