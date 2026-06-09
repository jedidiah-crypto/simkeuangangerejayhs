<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\DonaturController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ----------------------------------------------------------------
    // Pemasukan — hanya bendahara yang bisa input
    // ----------------------------------------------------------------
    Route::resource('pemasukan', PemasukanController::class)->except(['show']);

    // ----------------------------------------------------------------
    // Pengeluaran — bendahara bisa CRUD, pendeta bisa approve
    // ----------------------------------------------------------------
    Route::resource('pengeluaran', PengeluaranController::class)->except(['show']);

    // BARU: route approve hanya untuk pendeta (admin)
    Route::post('pengeluaran/{pengeluaran}/approve', [PengeluaranController::class, 'approve'])
        ->name('pengeluaran.approve')
        ->middleware('role:pendeta');

    // BARU: route reject hanya untuk pendeta (admin)
    Route::post('pengeluaran/{pengeluaran}/reject', [PengeluaranController::class, 'reject'])
        ->name('pengeluaran.reject')
        ->middleware('role:pendeta');

    // ----------------------------------------------------------------
    // Donatur
    // ----------------------------------------------------------------
    Route::resource('donatur', DonaturController::class);

    // ----------------------------------------------------------------
    // Laporan
    // ----------------------------------------------------------------
    Route::get('reports/period',  [ReportController::class, 'period'])->name('reports.period');
    Route::get('reports/income',  [ReportController::class, 'incomeReport'])->name('reports.income');
    Route::get('reports/expense', [ReportController::class, 'expenseReport'])->name('reports.expense');
    Route::get('reports/cashflow',[ReportController::class, 'cashFlowReport'])->name('reports.cashflow');
    Route::get('reports/category',[ReportController::class, 'categoryReport'])->name('reports.category');

    Route::get('reports/import',          [ReportController::class, 'importForm'])->name('reports.import.form');
    Route::post('reports/import',         [ReportController::class, 'importExcel'])->name('reports.import.excel');
    Route::get('reports/import/history',  [ReportController::class, 'importHistory'])->name('reports.import.history');
    Route::get('reports/import/history/csv', [ReportController::class, 'exportImportHistoryCsv'])->name('reports.import.history.csv');
    Route::get('reports/export/pdf',      [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('reports/export/excel',    [ReportController::class, 'exportExcel'])->name('reports.export.excel');

    // ----------------------------------------------------------------
    // Import
    // ----------------------------------------------------------------
    Route::get('import',          [ImportController::class, 'show'])->name('import.show');
    Route::post('import',         [ImportController::class, 'process'])->name('import.process');
    Route::get('import/template', [ImportController::class, 'downloadTemplate'])->name('import.template');

    // ----------------------------------------------------------------
    // Profil
    // ----------------------------------------------------------------
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
