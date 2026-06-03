<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\PemasukanApiController;
use App\Http\Controllers\Api\PengeluaranApiController;
use App\Http\Controllers\Api\DonaturApiController;
use App\Http\Controllers\Api\KategoriApiController;
use App\Http\Controllers\Api\RekeningApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\UserApiController;

/*
|--------------------------------------------------------------------------
| API Routes — SIM Keuangan Gereja (Flutter Mobile/Tablet)
|--------------------------------------------------------------------------
| Prefix  : /api/v1
| Auth    : Laravel Sanctum (token-based, cocok untuk Flutter)
| File    : routes/api.php
*/

// ─── PUBLIC — tidak butuh token ─────────────────────────────────────────────
Route::prefix('v1')->group(function () {

    // Login & Register
    Route::post('login',    [AuthApiController::class, 'login']);
    Route::post('register', [AuthApiController::class, 'register']); // opsional, bisa dimatikan

    // ─── PROTECTED — butuh token Sanctum ────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('logout',        [AuthApiController::class, 'logout']);
        Route::get('me',             [AuthApiController::class, 'me']);
        Route::put('me/password',    [AuthApiController::class, 'changePassword']);
        Route::post('me/avatar',     [AuthApiController::class, 'uploadAvatar']);

        // ── Dashboard ────────────────────────────────────────────────────────
        Route::get('dashboard',      [DashboardApiController::class, 'index']);

        // ── Pemasukan ────────────────────────────────────────────────────────
        Route::prefix('pemasukan')->group(function () {
            Route::get('/',           [PemasukanApiController::class, 'index']);
            Route::post('/',          [PemasukanApiController::class, 'store']);
            Route::get('{id}',        [PemasukanApiController::class, 'show']);
            Route::put('{id}',        [PemasukanApiController::class, 'update']);
            Route::delete('{id}',     [PemasukanApiController::class, 'destroy']);
            Route::post('{id}/bukti', [PemasukanApiController::class, 'uploadBukti']);
        });

        // ── Pengeluaran ──────────────────────────────────────────────────────
        Route::prefix('pengeluaran')->group(function () {
            Route::get('/',            [PengeluaranApiController::class, 'index']);
            Route::post('/',           [PengeluaranApiController::class, 'store']);
            Route::get('{id}',         [PengeluaranApiController::class, 'show']);
            Route::put('{id}',         [PengeluaranApiController::class, 'update']);
            Route::delete('{id}',      [PengeluaranApiController::class, 'destroy']);
            Route::post('{id}/approve',[PengeluaranApiController::class, 'approve']);
            Route::post('{id}/nota',   [PengeluaranApiController::class, 'uploadNota']);
        });

        // ── Donatur ──────────────────────────────────────────────────────────
        Route::prefix('donatur')->group(function () {
            Route::get('/',        [DonaturApiController::class, 'index']);
            Route::post('/',       [DonaturApiController::class, 'store']);
            Route::get('{id}',     [DonaturApiController::class, 'show']);
            Route::put('{id}',     [DonaturApiController::class, 'update']);
            Route::delete('{id}',  [DonaturApiController::class, 'destroy']);
        });

        // ── Kategori Keuangan ────────────────────────────────────────────────
        Route::prefix('kategori')->group(function () {
            Route::get('/',           [KategoriApiController::class, 'index']);   // ?type=pemasukan|pengeluaran
            Route::post('/',          [KategoriApiController::class, 'store']);
            Route::put('{id}',        [KategoriApiController::class, 'update']);
            Route::delete('{id}',     [KategoriApiController::class, 'destroy']);
        });

        // ── Rekening ─────────────────────────────────────────────────────────
        Route::prefix('rekening')->group(function () {
            Route::get('/',       [RekeningApiController::class, 'index']);
            Route::post('/',      [RekeningApiController::class, 'store']);
            Route::put('{id}',    [RekeningApiController::class, 'update']);
            Route::delete('{id}', [RekeningApiController::class, 'destroy']);
        });

        // ── Laporan / Report ─────────────────────────────────────────────────
        Route::prefix('report')->group(function () {
            Route::get('period',         [ReportApiController::class, 'period']);        // ?from=&to=
            Route::get('summary',        [ReportApiController::class, 'summary']);       // ringkasan bulanan
            Route::get('export/pdf',     [ReportApiController::class, 'exportPdf']);     // download PDF
            Route::get('export/excel',   [ReportApiController::class, 'exportExcel']);   // download Excel
            Route::post('import',        [ReportApiController::class, 'importExcel']);   // upload Excel
            Route::get('import/history', [ReportApiController::class, 'importHistory']);
        });

        // ── User Management (admin only) ─────────────────────────────────────
        Route::middleware('role:admin')->prefix('users')->group(function () {
            Route::get('/',        [UserApiController::class, 'index']);
            Route::post('/',       [UserApiController::class, 'store']);
            Route::get('{id}',     [UserApiController::class, 'show']);
            Route::put('{id}',     [UserApiController::class, 'update']);
            Route::delete('{id}',  [UserApiController::class, 'destroy']);
        });

    }); // end auth:sanctum
}); // end v1
