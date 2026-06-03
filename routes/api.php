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

Route::prefix('v1')->group(function () {

    Route::post('login',    [AuthApiController::class, 'login']);
    Route::post('register', [AuthApiController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout',     [AuthApiController::class, 'logout']);
        Route::get('me',          [AuthApiController::class, 'me']);
        Route::put('me/password', [AuthApiController::class, 'changePassword']);

        Route::get('dashboard',   [DashboardApiController::class, 'index']);

        Route::prefix('pemasukan')->group(function () {
            Route::get('/',           [PemasukanApiController::class, 'index']);
            Route::post('/',          [PemasukanApiController::class, 'store']);
            Route::get('{id}',        [PemasukanApiController::class, 'show']);
            Route::put('{id}',        [PemasukanApiController::class, 'update']);
            Route::delete('{id}',     [PemasukanApiController::class, 'destroy']);
            Route::post('{id}/bukti', [PemasukanApiController::class, 'uploadBukti']);
        });

        Route::prefix('pengeluaran')->group(function () {
            Route::get('/',            [PengeluaranApiController::class, 'index']);
            Route::post('/',           [PengeluaranApiController::class, 'store']);
            Route::get('{id}',         [PengeluaranApiController::class, 'show']);
            Route::put('{id}',         [PengeluaranApiController::class, 'update']);
            Route::delete('{id}',      [PengeluaranApiController::class, 'destroy']);
            Route::post('{id}/approve',[PengeluaranApiController::class, 'approve']);
            Route::post('{id}/nota',   [PengeluaranApiController::class, 'uploadNota']);
        });

        Route::prefix('donatur')->group(function () {
            Route::get('/',       [DonaturApiController::class, 'index']);
            Route::post('/',      [DonaturApiController::class, 'store']);
            Route::get('{id}',    [DonaturApiController::class, 'show']);
            Route::put('{id}',    [DonaturApiController::class, 'update']);
            Route::delete('{id}', [DonaturApiController::class, 'destroy']);
        });

        Route::prefix('kategori')->group(function () {
            Route::get('/',       [KategoriApiController::class, 'index']);
            Route::post('/',      [KategoriApiController::class, 'store']);
            Route::put('{id}',    [KategoriApiController::class, 'update']);
            Route::delete('{id}', [KategoriApiController::class, 'destroy']);
        });

        Route::prefix('rekening')->group(function () {
            Route::get('/',       [RekeningApiController::class, 'index']);
            Route::post('/',      [RekeningApiController::class, 'store']);
            Route::put('{id}',    [RekeningApiController::class, 'update']);
            Route::delete('{id}', [RekeningApiController::class, 'destroy']);
        });

        Route::prefix('report')->group(function () {
            Route::get('period',         [ReportApiController::class, 'period']);
            Route::get('summary',        [ReportApiController::class, 'summary']);
            Route::get('export/pdf',     [ReportApiController::class, 'exportPdf']);
            Route::get('export/excel',   [ReportApiController::class, 'exportExcel']);
            Route::post('import',        [ReportApiController::class, 'importExcel']);
            Route::get('import/history', [ReportApiController::class, 'importHistory']);
        });

        Route::middleware('role:admin')->prefix('users')->group(function () {
            Route::get('/',       [UserApiController::class, 'index']);
            Route::post('/',      [UserApiController::class, 'store']);
            Route::get('{id}',    [UserApiController::class, 'show']);
            Route::put('{id}',    [UserApiController::class, 'update']);
            Route::delete('{id}', [UserApiController::class, 'destroy']);
        });

    });
});