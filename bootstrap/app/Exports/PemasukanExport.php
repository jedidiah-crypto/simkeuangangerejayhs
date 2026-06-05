<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use App\Models\Pemasukan;

class PemasukanExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;

    public function __construct($from,$to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return Pemasukan::whereBetween('tanggal', [$this->from, $this->to])
            ->get(['nomor_transaksi','tanggal','nominal','sumber_dana','keterangan']);
    }

    public function headings(): array
    {
        return ['Nomor Transaksi','Tanggal','Nominal','Sumber Dana','Keterangan'];
    }
}
