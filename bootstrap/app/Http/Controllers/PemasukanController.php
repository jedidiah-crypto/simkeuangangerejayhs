<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemasukan;
use App\Models\Donatur;
use App\Models\KategoriKeuangan;
use App\Models\Rekening;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PemasukanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemasukan::with(['kategori','donatur','rekening']);
        if ($request->filled('q')) {
            $query->where('keterangan','like','%'.$request->q.'%');
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('tanggal', [$request->from, $request->to]);
        }

        $items = $query->latest()->paginate(15);
        $kategori = KategoriKeuangan::where('type','pemasukan')->get();

        return view('pemasukan.index', compact('items','kategori'));
    }

    public function create()
    {
        $kategori = KategoriKeuangan::where('type','pemasukan')->get();
        $donatur = Donatur::all();
        $rekening = Rekening::all();

        return view('pemasukan.create', compact('kategori','donatur','rekening'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'=>'required|date',
            'nominal'=>'required|numeric',
            'kategori_id'=>'required|exists:kategori_keuangan,id',
            'donatur_id'=>'nullable|exists:donaturs,id',
            'rekening_id'=>'nullable|exists:rekenings,id',
            'metode'=>'nullable|string|max:100',
            'sumber_dana'=>'nullable|string|max:150',
            'keterangan'=>'nullable|string|max:1000',
            'bukti'=>'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti')->store('pemasukan/bukti', 'public');
        }

        $data['nomor_transaksi'] = 'PM'.now()->format('Ymd').Str::upper(Str::random(6));
        $data['created_by'] = $request->user()->id;

        Pemasukan::create($data);

        return redirect()->route('pemasukan.index')->with('success','Pemasukan tersimpan');
    }

    public function edit(Pemasukan $pemasukan)
    {
        $kategori = KategoriKeuangan::where('type','pemasukan')->get();
        $donatur = Donatur::all();
        $rekening = Rekening::all();

        return view('pemasukan.edit', compact('pemasukan','kategori','donatur','rekening'));
    }

    public function update(Request $request, Pemasukan $pemasukan)
    {
        $data = $request->validate([
            'tanggal'=>'required|date',
            'nominal'=>'required|numeric',
            'kategori_id'=>'required|exists:kategori_keuangan,id',
            'donatur_id'=>'nullable|exists:donaturs,id',
            'rekening_id'=>'nullable|exists:rekenings,id',
            'metode'=>'nullable|string|max:100',
            'sumber_dana'=>'nullable|string|max:150',
            'keterangan'=>'nullable|string|max:1000',
            'bukti'=>'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($request->hasFile('bukti')) {
            if ($pemasukan->bukti) {
                Storage::disk('public')->delete($pemasukan->bukti);
            }
            $data['bukti'] = $request->file('bukti')->store('pemasukan/bukti', 'public');
        }

        $pemasukan->update($data);

        return redirect()->route('pemasukan.index')->with('success','Pemasukan diperbarui');
    }

    public function destroy(Pemasukan $pemasukan)
    {
        if ($pemasukan->bukti) {
            Storage::disk('public')->delete($pemasukan->bukti);
        }

        $pemasukan->delete();

        return redirect()->route('pemasukan.index')->with('success','Pemasukan dihapus');
    }
}
