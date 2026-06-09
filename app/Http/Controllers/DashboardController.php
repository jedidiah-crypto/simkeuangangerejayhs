<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Rekening;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = now();

        $totalPemasukan   = Pemasukan::whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year)->sum('nominal');
        $totalPengeluaran = Pengeluaran::whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year)->sum('nominal');

        // Saldo dihitung dinamis dari seluruh transaksi
        $totalPemasukanAll   = Pemasukan::sum('nominal');
        $totalPengeluaranAll = Pengeluaran::sum('nominal');
        $saldo = $totalPemasukanAll - $totalPengeluaranAll;

        // ============================================================
        // BARU: Data approval untuk pendeta
        // ============================================================
        $pendingCount = Pengeluaran::where('status', 'pending')->count();
        $pendingItems = Pengeluaran::with(['kategori', 'rekening'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // ============================================================
        // Chart Mingguan
        // ============================================================
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
            $pemasukanSeries[]   = Pemasukan::whereBetween('tanggal', [
                $weekStart->toDateString(), $weekEnd->toDateString()
            ])->sum('nominal');
            $pengeluaranSeries[] = Pengeluaran::whereBetween('tanggal', [
                $weekStart->toDateString(), $weekEnd->toDateString()
            ])->sum('nominal');
        }

        // ============================================================
        // Kategori Pengeluaran untuk Donut Chart
        // ============================================================
        $categoryData = Pengeluaran::with('kategori')
            ->selectRaw('kategori_id, sum(nominal) as total')
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->groupBy('kategori_id')
            ->get();

        $categoryLabels = $categoryData->map(fn($item) => $item->kategori->nama ?? 'Lainnya')->toArray();
        $categoryValues = $categoryData->pluck('total')->toArray();

        // ============================================================
        // Transaksi Terbaru
        // ============================================================
        $latestPemasukan   = Pemasukan::latest()->take(8)->get()
            ->map(fn($i) => $i->setAttribute('_type', 'pemasukan'));
        $latestPengeluaran = Pengeluaran::latest()->take(8)->get()
            ->map(fn($i) => $i->setAttribute('_type', 'pengeluaran'));
        $latest = $latestPemasukan->merge($latestPengeluaran)
            ->sortByDesc('created_at')
            ->take(8);

        return view('dashboard', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'latest',
            'weeklyLabels',
            'pemasukanSeries',
            'pengeluaranSeries',
            'categoryLabels',
            'categoryValues',
            // BARU
            'pendingCount',
            'pendingItems',
        ));
    }
}
