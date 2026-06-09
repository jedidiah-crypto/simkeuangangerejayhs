<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Jalankan dengan: php artisan migrate
 *
 * Menambahkan kolom:
 *  - approved_at      : waktu approve/reject
 *  - catatan_reject   : alasan jika ditolak pendeta
 */
class AddApprovalFieldsToPengeluaranTable extends Migration
{
    public function up()
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('catatan_reject')->nullable()->after('approved_at');
        });
    }

    public function down()
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropColumn(['approved_at', 'catatan_reject']);
        });
    }
}
