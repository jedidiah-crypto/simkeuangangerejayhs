<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donatur;
use Illuminate\Http\Request;

/**
 * DonaturApiController
 * CRUD donatur + riwayat donasi untuk Flutter.
 */
class DonaturApiController extends Controller
{
    // ─── GET /api/v1/donatur ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Donatur::withCount('pemasukan');

        if ($request->filled('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        $perPage = min((int) ($request->per_page ?? 15), 100);
        $items   = $query->orderBy('nama')->paginate($perPage);

        return response()->json([
            'status' => true,
            'data'   => $items->map(fn($d) => $this->format($d)),
            'meta'   => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'total'        => $items->total(),
            ],
        ]);
    }

    // ─── GET /api/v1/donatur/{id} ────────────────────────────────────────────
    public function show($id)
    {
        $donatur = Donatur::with('pemasukan.kategori')->withCount('pemasukan')->findOrFail($id);

        $data            = $this->format($donatur);
        $data['riwayat'] = $donatur->pemasukan->map(fn($p) => [
            'id'              => $p->id,
            'nomor_transaksi' => $p->nomor_transaksi,
            'tanggal'         => $p->tanggal,
            'nominal'         => (int) $p->nominal,
            'keterangan'      => $p->keterangan,
            'kategori'        => $p->kategori?->nama,
        ]);
        $data['total_donasi'] = (int) $donatur->pemasukan->sum('nominal');

        return response()->json(['status' => true, 'data' => $data]);
    }

    // ─── POST /api/v1/donatur ────────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'    => 'required|string|max:150',
            'telepon' => 'nullable|string|max:30',
            'email'   => 'nullable|email|max:150',
            'alamat'  => 'nullable|string|max:500',
        ]);

        $donatur = Donatur::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Donatur berhasil ditambahkan',
            'data'    => $this->format($donatur),
        ], 201);
    }

    // ─── PUT /api/v1/donatur/{id} ────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $donatur = Donatur::findOrFail($id);

        $data = $request->validate([
            'nama'    => 'sometimes|string|max:150',
            'telepon' => 'nullable|string|max:30',
            'email'   => 'nullable|email|max:150',
            'alamat'  => 'nullable|string|max:500',
        ]);

        $donatur->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Donatur berhasil diperbarui',
            'data'    => $this->format($donatur->fresh()),
        ]);
    }

    // ─── DELETE /api/v1/donatur/{id} ─────────────────────────────────────────
    public function destroy($id)
    {
        $donatur = Donatur::withCount('pemasukan')->findOrFail($id);

        if ($donatur->pemasukan_count > 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Donatur tidak dapat dihapus karena memiliki riwayat donasi.',
            ], 422);
        }

        $donatur->delete();

        return response()->json(['status' => true, 'message' => 'Donatur berhasil dihapus']);
    }

    private function format(Donatur $d): array
    {
        return [
            'id'             => $d->id,
            'nama'           => $d->nama,
            'telepon'        => $d->telepon,
            'email'          => $d->email,
            'alamat'         => $d->alamat,
            'jumlah_donasi'  => $d->pemasukan_count ?? 0,
            'created_at'     => $d->created_at?->toDateTimeString(),
        ];
    }
}
