<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemasukanTable extends Migration
{
    public function up()
    {
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi')->unique();
            $table->date('tanggal');
            $table->decimal('nominal', 15, 2);
            $table->foreignId('kategori_id')->constrained('kategori_keuangan');
            $table->foreignId('donatur_id')->nullable()->constrained('donaturs');
            $table->foreignId('rekening_id')->nullable()->constrained('rekenings');
            $table->string('metode')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('bukti')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemasukan');
    }
}
