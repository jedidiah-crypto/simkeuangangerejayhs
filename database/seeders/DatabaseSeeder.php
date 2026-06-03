<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\KategoriKeuangan;
use App\Models\Rekening;
use App\Models\Donatur;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Role::insert([
            ['name'=>'admin','label'=>'Admin','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'bendahara','label'=>'Bendahara','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'pendeta','label'=>'Pendeta','created_at'=>now(),'updated_at'=>now()],
        ]);

        User::factory()->create(['name'=>'Administrator','email'=>'admin@example.com','password'=>bcrypt('password')])->roles()->attach(1);

        KategoriKeuangan::insert([
            ['nama'=>'Persembahan Ibadah','type'=>'pemasukan','created_at'=>now(),'updated_at'=>now()],
            ['nama'=>'Perpuluhan','type'=>'pemasukan','created_at'=>now(),'updated_at'=>now()],
            ['nama'=>'Operasional Gereja','type'=>'pengeluaran','created_at'=>now(),'updated_at'=>now()],
            ['nama'=>'Listrik dan Air','type'=>'pengeluaran','created_at'=>now(),'updated_at'=>now()],
        ]);

        Rekening::create(['nama'=>'Kas Utama','nomor'=>'0001','bank'=>'Gereja Bank','saldo'=>1000000]);

        Donatur::factory()->count(10)->create();

        // optional: create sample pemasukan/pengeluaran via factories
    }
}
