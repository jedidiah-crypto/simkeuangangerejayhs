<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nonaktifkan foreign key check dulu sebelum hapus data
        Schema::disableForeignKeyConstraints();
        DB::table('donaturs')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('donaturs')->insert([
            ['nama' => 'Ps. Jabhez Priyantoro',  'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ps. Priscilla Untari',   'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ps. Yohanes HBP Manik',  'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Ev. Yohanes Jatmiko',    'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('donaturs')->truncate();
        Schema::enableForeignKeyConstraints();
    }
};