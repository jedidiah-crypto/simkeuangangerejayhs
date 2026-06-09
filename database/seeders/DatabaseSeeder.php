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
        // ----------------------------------------------------------------
        // Roles
        // PERUBAHAN: tidak ada lagi role 'admin' generik.
        // Pendeta = pemegang otoritas (approve), Bendahara = operator keuangan.
        // ----------------------------------------------------------------
        Role::insert([
            ['name' => 'pendeta',   'label' => 'Pendeta',   'created_at' => now(), 'updated_at' => now()],
            ['name' => 'bendahara', 'label' => 'Bendahara', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Akun pendeta (admin gereja)
        User::factory()->create([
            'name'     => 'Pendeta Utama',
            'email'    => 'pendeta@gereja.com',
            'password' => bcrypt('password'),
        ])->roles()->attach(Role::where('name', 'pendeta')->first()->id);

        // Akun bendahara
        User::factory()->create([
            'name'     => 'Bendahara Gereja',
            'email'    => 'bendahara@gereja.com',
            'password' => bcrypt('password'),
        ])->roles()->attach(Role::where('name', 'bendahara')->first()->id);

        // ----------------------------------------------------------------
        // Kategori
        // ----------------------------------------------------------------
        KategoriKeuangan::insert([
            ['nama' => 'Persembahan Ibadah', 'type' => 'pemasukan',   'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Perpuluhan',          'type' => 'pemasukan',   'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Operasional Gereja',  'type' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Listrik dan Air',     'type' => 'pengeluaran', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ----------------------------------------------------------------
        // Rekening
        // ----------------------------------------------------------------
        Rekening::create([
            'nama'   => 'Kas Utama',
            'nomor'  => '0001',
            'bank'   => 'Gereja Bank',
            'saldo'  => 1000000,
        ]);

        // ----------------------------------------------------------------
        // Sample donatur
        // ----------------------------------------------------------------
        Donatur::factory()->count(10)->create();
    }
}
