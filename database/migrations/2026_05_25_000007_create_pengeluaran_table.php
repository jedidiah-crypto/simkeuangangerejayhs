<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaranTable extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi')->unique();
            $table->date('tanggal');
            $table->decimal('nominal', 15, 2);
            $table->foreignId('kategori_id')->constrained('kategori_keuangan');
            $table->foreignId('rekening_id')->nullable()->constrained('rekenings');
            $table->string('metode')->nullable();
            $table->string('status')->default('pending');
            $table->text('keterangan')->nullable();
            $table->string('nota')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran');
    }
}
