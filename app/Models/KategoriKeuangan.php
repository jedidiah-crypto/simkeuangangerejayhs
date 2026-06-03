<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKeuangan extends Model
{
    use HasFactory;

    protected $table = 'kategori_keuangan';
    protected $guarded = [];

    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'kategori_id');
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'kategori_id');
    }
}
