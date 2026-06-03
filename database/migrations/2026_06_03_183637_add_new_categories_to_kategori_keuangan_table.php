<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('kategori_keuangan')->insert([
            ['nama' => 'Taburan', 'keterangan' => 'Dana tabungan dari jemaat', 'type' => 'pemasukan'],
            ['nama' => 'Persembahan Mingguan', 'keterangan' => 'Persembahan dari kebaktian mingguan', 'type' => 'pemasukan'],
            ['nama' => 'Perpuluhan', 'keterangan' => 'Perpuluhan dari jemaat', 'type' => 'pemasukan'],
            ['nama' => 'Donasi Khusus', 'keterangan' => 'Donasi khusus dari jemaat atau pihak luar', 'type' => 'pemasukan'],
            ['nama' => 'Operasional', 'keterangan' => 'Biaya operasional gereja', 'type' => 'pengeluaran'],
            ['nama' => 'Gaji/Honor', 'keterangan' => 'Gaji atau honor pegawai/pendeta', 'type' => 'pengeluaran'],
            ['nama' => 'Transportasi', 'keterangan' => 'Biaya transportasi', 'type' => 'pengeluaran'],
            ['nama' => 'Konsumsi', 'keterangan' => 'Biaya konsumsi/makanan', 'type' => 'pengeluaran'],
            ['nama' => 'Aset/Inventaris', 'keterangan' => 'Pembelian aset atau inventaris', 'type' => 'pengeluaran'],
            ['nama' => 'Biaya Bank', 'keterangan' => 'Biaya administrasi bank', 'type' => 'pengeluaran'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('kategori_keuangan')->whereIn('nama', [
            'Taburan', 'Persembahan Mingguan', 'Perpuluhan', 'Donasi Khusus',
            'Operasional', 'Gaji/Honor', 'Transportasi', 'Konsumsi', 'Aset/Inventaris', 'Biaya Bank'
        ])->delete();
    }
};

