<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

/**
 * DashboardApiController
 * GET /api/v1/dashboard
 * Mengembalikan semua data summary + chart untuk halaman utama Flutter.
 */
class DashboardApiController extends Controller
{
    public function index(Request $request)
    {
        $now = now();

        // ── Ringkasan bulan ini ──────────────────────────────────────────────
        $totalPemasukan   = Pemasukan::whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)->sum('nominal');

        $totalPengeluaran = Pengeluaran::whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)->sum('nominal');

        // Saldo dihitung dari seluruh riwayat transaksi
        $saldo = Pemasukan::sum('nominal') - Pengeluaran::sum('nominal');

        // ── Chart mingguan (4 minggu bulan berjalan) ─────────────────────────
        $weeklyLabels      = [];
        $pemasukanSeries   = [];
        $pengeluaranSeries = [];
        $startOfMonth      = $now->copy()->startOfMonth();

        for ($week = 0; $week < 4; $week++) {
            $weekStart = $startOfMonth->copy()->addDays($week * 7);
            $weekEnd   = ($week < 3)
                ? $weekStart->copy()->addDays(6)->endOfDay()
                : $now->copy()->endOfMonth()->endOfDay();

            $weeklyLabels[]      = 'Minggu ' . ($week + 1);
            $pemasukanSeries[]   = (int) Pemasukan::whereBetween('tanggal', [
                $weekStart->toDateString(), $weekEnd->toDateString(),
            ])->sum('nominal');
            $pengeluaranSeries[] = (int) Pengeluaran::whereBetween('tanggal', [
                $weekStart->toDateString(), $weekEnd->toDateString(),
            ])->sum('nominal');
        }

        // ── Donut chart — pengeluaran per kategori (bulan ini) ───────────────
        $categoryData = Pengeluaran::with('kategori')
            ->selectRaw('kategori_id, sum(nominal) as total')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->groupBy('kategori_id')
            ->get()
            ->map(fn($item) => [
                'label' => $item->kategori->nama ?? 'Lainnya',
                'value' => (int) $item->total,
            ]);

        // ── 8 transaksi terbaru (gabungan pemasukan & pengeluaran) ───────────
        $latestPemasukan = Pemasukan::with(['kategori', 'donatur'])
            ->latest()->take(8)->get()
            ->map(fn($i) => $this->formatPemasukan($i));

        $latestPengeluaran = Pengeluaran::with(['kategori'])
            ->latest()->take(8)->get()
            ->map(fn($i) => $this->formatPengeluaran($i));

        $latest = $latestPemasukan->merge($latestPengeluaran)
            ->sortByDesc('created_at')
            ->take(8)
            ->values();

        return response()->json([
            'status' => true,
            'data'   => [
                'summary' => [
                    'total_pemasukan_bulan_ini'   => $totalPemasukan,
                    'total_pengeluaran_bulan_ini' => $totalPengeluaran,
                    'saldo'                       => $saldo,
                    'bulan'                       => $now->translatedFormat('F Y'),
                ],
                'chart_mingguan' => [
                    'labels'      => $weeklyLabels,
                    'pemasukan'   => $pemasukanSeries,
                    'pengeluaran' => $pengeluaranSeries,
                ],
                'chart_kategori_pengeluaran' => $categoryData,
                'transaksi_terbaru'          => $latest,
            ],
        ]);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function formatPemasukan($item): array
    {
        return [
            'id'               => $item->id,
            'type'             => 'pemasukan',
            'nomor_transaksi'  => $item->nomor_transaksi,
            'tanggal'          => $item->tanggal,
            'nominal'          => (int) $item->nominal,
            'keterangan'       => $item->keterangan,
            'kategori'         => $item->kategori?->nama,
            'donatur'          => $item->donatur?->nama,
            'created_at'       => $item->created_at?->toDateTimeString(),
        ];
    }

    private function formatPengeluaran($item): array
    {
        return [
            'id'              => $item->id,
            'type'            => 'pengeluaran',
            'nomor_transaksi' => $item->nomor_transaksi,
            'tanggal'         => $item->tanggal,
            'nominal'         => (int) $item->nominal,
            'keterangan'      => $item->keterangan,
            'kategori'        => $item->kategori?->nama,
            'status'          => $item->status,
            'created_at'      => $item->created_at?->toDateTimeString(),
        ];
    }
}
