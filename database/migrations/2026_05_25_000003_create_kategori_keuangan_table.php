<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriKeuanganTable extends Migration
{
    public function up()
    {
        Schema::create('kategori_keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->enum('type', ['pemasukan','pengeluaran'])->default('pemasukan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_keuangan');
    }
}
