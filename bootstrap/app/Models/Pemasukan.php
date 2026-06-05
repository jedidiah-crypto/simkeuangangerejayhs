<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    use HasFactory;

    protected $table = 'pemasukan';
    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(KategoriKeuangan::class, 'kategori_id');
    }

    public function donatur()
    {
        return $this->belongsTo(Donatur::class);
    }

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }
}
