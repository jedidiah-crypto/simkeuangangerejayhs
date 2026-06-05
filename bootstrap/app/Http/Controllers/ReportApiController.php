<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * ReportApiController
 * Laporan periode, export PDF/Excel, import Excel untuk Flutter.
 */
class ReportApiController extends Controller
{
    // ─── GET /api/v1/report/period?from=&to= ────────────────────────────────
    public function period(Request $request)
    {
        $request->validate([
            'from' => 'nullable|date',
            'to'   => 'nullable|date|after_or_equal:from',
        ]);

        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->endOfMonth()->toDateString();

        $pemasukan   = Pemasukan::with(['kategori', 'donatur', 'rekening'])
            ->whereBetween('tanggal', [$from, $to])
            ->orderBy('tanggal')
            ->get();

        $pengeluaran = Pengeluaran::with(['kategori', 'rekening'])
            ->whereBetween('tanggal', [$from, $to])
            ->orderBy('tanggal')
            ->get();

        $totalPemasukan   = $pemasukan->sum('nominal');
        $totalPengeluaran = $pengeluaran->sum('nominal');

        return response()->json([
            'status' => true,
            'data'   => [
                'periode' => ['from' => $from, 'to' => $to],
                'summary' => [
                    'total_pemasukan'   => (int) $totalPemasukan,
                    'total_pengeluaran' => (int) $totalPengeluaran,
                    'selisih'           => (int) ($totalPemasukan - $totalPengeluaran),
                ],
                'pemasukan'   => $pemasukan->map(fn($p) => [
                    'id'              => $p->id,
                    'nomor_transaksi' => $p->nomor_transaksi,
                    'tanggal'         => $p->tanggal,
                    'nominal'         => (int) $p->nominal,
                    'keterangan'      => $p->keterangan,
                    'kategori'        => $p->kategori?->nama,
                    'donatur'         => $p->donatur?->nama,
                    'rekening'        => $p->rekening?->nama,
                    'bukti_url'       => $p->bukti ? asset('storage/' . $p->bukti) : null,
                ]),
                'pengeluaran' => $pengeluaran->map(fn($p) => [
                    'id'              => $p->id,
                    'nomor_transaksi' => $p->nomor_transaksi,
                    'tanggal'         => $p->tanggal,
                    'nominal'         => (int) $p->nominal,
                    'keterangan'      => $p->keterangan,
                    'status'          => $p->status,
                    'kategori'        => $p->kategori?->nama,
                    'rekening'        => $p->rekening?->nama,
                    'nota_url'        => $p->nota ? asset('storage/' . $p->nota) : null,
                ]),
            ],
        ]);
    }

    // ─── GET /api/v1/report/summary ─────────────────────────────────────────
    // Ringkasan 12 bulan terakhir (untuk grafik tren di Flutter)
    public function summary(Request $request)
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $months[] = [
                'label'       => $m->translatedFormat('M Y'),
                'bulan'       => $m->month,
                'tahun'       => $m->year,
                'pemasukan'   => (int) Pemasukan::whereMonth('tanggal', $m->month)
                    ->whereYear('tanggal', $m->year)->sum('nominal'),
                'pengeluaran' => (int) Pengeluaran::whereMonth('tanggal', $m->month)
                    ->whereYear('tanggal', $m->year)->sum('nominal'),
            ];
        }

        return response()->json(['status' => true, 'data' => $months]);
    }

    // ─── GET /api/v1/report/export/pdf?from=&to= ────────────────────────────
    // Flutter buka URL ini di in-app browser atau unduh file
    public function exportPdf(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->endOfMonth()->toDateString();

        $pemasukan   = Pemasukan::whereBetween('tanggal', [$from, $to])->get();
        $pengeluaran = Pengeluaran::whereBetween('tanggal', [$from, $to])->get();

        $pdf = app('Barryvdh\DomPDF\PDF');
        $pdf->loadView('reports.pdf', compact('pemasukan', 'pengeluaran', 'from', 'to'));

        return $pdf->download("laporan_{$from}_{$to}.pdf");
    }

    // ─── GET /api/v1/report/export/excel?from=&to= ──────────────────────────
    public function exportExcel(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to   ?? now()->endOfMonth()->toDateString();

        return (new \App\Exports\PemasukanExport($from, $to))
            ->download("pemasukan_{$from}_{$to}.xlsx");
    }

    // ─── POST /api/v1/report/import ──────────────────────────────────────────
    // Flutter kirim file Excel sebagai multipart/form-data
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new \App\Imports\PemasukanPengeluaranImport($request->user()->id);

        try {
            Excel::import($import, $request->file('file'));

            $count = $import->getImportedCount();

            AuditLog::create([
                'user_id'        => $request->user()->id,
                'action'         => 'import_excel',
                'auditable_type' => 'import',
                'auditable_id'   => null,
                'old_values'     => null,
                'new_values'     => json_encode([
                    'file'     => $request->file('file')->getClientOriginalName(),
                    'imported' => $count,
                ]),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status'  => true,
                'message' => "Impor berhasil, {$count} transaksi ditambahkan.",
                'data'    => ['imported_count' => $count],
            ]);
        } catch (\Throwable $e) {
            AuditLog::create([
                'user_id'        => $request->user()->id,
                'action'         => 'import_excel',
                'auditable_type' => 'import',
                'auditable_id'   => null,
                'old_values'     => null,
                'new_values'     => json_encode([
                    'file'  => $request->file('file')->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Impor gagal: ' . $e->getMessage(),
            ], 422);
        }
    }

    // ─── GET /api/v1/report/import/history ───────────────────────────────────
    public function importHistory(Request $request)
    {
        $perPage = min((int) ($request->per_page ?? 20), 100);
        $items   = AuditLog::where('action', 'import_excel')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data'   => $items->map(function ($item) {
                $meta     = json_decode($item->new_values, true) ?? [];
                $isError  = isset($meta['error']);
                return [
                    'id'         => $item->id,
                    'tanggal'    => $item->created_at?->toDateTimeString(),
                    'file'       => $meta['file'] ?? '',
                    'imported'   => $meta['imported'] ?? 0,
                    'status'     => $isError ? 'gagal' : 'berhasil',
                    'keterangan' => $isError ? $meta['error'] : 'Impor berhasil',
                ];
            }),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'total'        => $items->total(),
            ],
        ]);
    }
}
