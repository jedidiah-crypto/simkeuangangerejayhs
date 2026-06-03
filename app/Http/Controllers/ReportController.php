<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\AuditLog;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ReportController extends Controller
{
    public function period(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        $pemasukan = Pemasukan::whereBetween('tanggal', [$from, $to])->get();
        $pengeluaran = Pengeluaran::whereBetween('tanggal', [$from, $to])->get();

        return view('reports.period', compact('pemasukan','pengeluaran','from','to'));
    }

    public function importForm()
    {
        return view('reports.import');
    }

    public function importHistory(Request $request)
    {
        $items = AuditLog::where('action', 'import_excel')->latest()->paginate(20);
        return view('reports.import-history', compact('items'));
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new \App\Imports\PemasukanPengeluaranImport($request->user()->id);
        try {
            Excel::import($import, $request->file('file'));

            $count = $import->getImportedCount();
            $message = "Impor berhasil, {$count} transaksi ditambahkan.";
            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'import_excel',
                'auditable_type' => 'import',
                'auditable_id' => null,
                'old_values' => null,
                'new_values' => json_encode(['file' => $request->file('file')->getClientOriginalName(), 'imported' => $count]),
                'ip' => $request->ip(),
            ]);

            return redirect()->route('reports.import.form')->with('success', $message);
        } catch (\Throwable $e) {
            AuditLog::create([
                'user_id' => $request->user()->id,
                'action' => 'import_excel',
                'auditable_type' => 'import',
                'auditable_id' => null,
                'old_values' => null,
                'new_values' => json_encode(['file' => $request->file('file')->getClientOriginalName(), 'error' => $e->getMessage()]),
                'ip' => $request->ip(),
            ]);

            return redirect()->route('reports.import.form')->with('error', 'Impor gagal: ' . $e->getMessage());
        }
    }

    public function exportImportHistoryCsv(Request $request)
    {
        $items = AuditLog::where('action', 'import_excel')->latest()->get();

        $callback = function () use ($items) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'File', 'Jumlah Terimpor', 'Status', 'Detail']);

            foreach ($items as $item) {
                $meta = json_decode($item->new_values, true) ?? [];
                $file = $meta['file'] ?? '';
                $imported = $meta['imported'] ?? '';
                $status = isset($meta['error']) ? 'Gagal' : 'Berhasil';
                $detail = $meta['error'] ?? 'Impor berhasil';

                fputcsv($handle, [
                    $item->created_at->format('Y-m-d H:i:s'),
                    $file,
                    $imported,
                    $status,
                    $detail,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, 'import-history-'.now()->format('YmdHis').'.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="import-history-'.now()->format('YmdHis').'.csv"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        $pemasukan = Pemasukan::whereBetween('tanggal', [$from, $to])->get();
        $pengeluaran = Pengeluaran::whereBetween('tanggal', [$from, $to])->get();

        $pdf = PDF::loadView('reports.pdf', compact('pemasukan','pengeluaran','from','to'));
        return $pdf->download("laporan_{$from}_{$to}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        return (new \App\Exports\PemasukanExport($from,$to))->download("pemasukan_{$from}_{$to}.xlsx");
    }

    public function incomeReport(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        $data = Pemasukan::whereBetween('tanggal', [$from, $to])
            ->with('kategori')
            ->get()
            ->groupBy('kategori.nama')
            ->map(function($items) {
                return [
                    'total' => $items->sum('nominal'),
                    'count' => $items->count(),
                    'transactions' => $items
                ];
            });

        return view('reports.income', compact('data','from','to'));
    }

    public function expenseReport(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        $data = Pengeluaran::whereBetween('tanggal', [$from, $to])
            ->with('kategori')
            ->get()
            ->groupBy('kategori.nama')
            ->map(function($items) {
                return [
                    'total' => $items->sum('nominal'),
                    'count' => $items->count(),
                    'transactions' => $items
                ];
            });

        return view('reports.expense', compact('data','from','to'));
    }

    public function cashFlowReport(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        $pemasukan = Pemasukan::whereBetween('tanggal', [$from, $to])->sum('nominal');
        $pengeluaran = Pengeluaran::whereBetween('tanggal', [$from, $to])->sum('nominal');
        $saldoAwal = 0;
        $saldoAkhir = $pemasukan - $pengeluaran;

        return view('reports.cashflow', compact('saldoAwal','pemasukan','pengeluaran','saldoAkhir','from','to'));
    }

    public function categoryReport(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->endOfMonth()->toDateString();

        $pemasukan = Pemasukan::whereBetween('tanggal', [$from, $to])
            ->with('kategori')
            ->get()
            ->groupBy('kategori.nama')
            ->map(fn($items) => $items->sum('nominal'));

        $pengeluaran = Pengeluaran::whereBetween('tanggal', [$from, $to])
            ->with('kategori')
            ->get()
            ->groupBy('kategori.nama')
            ->map(fn($items) => $items->sum('nominal'));

        return view('reports.category', compact('pemasukan','pengeluaran','from','to'));
    }
}
