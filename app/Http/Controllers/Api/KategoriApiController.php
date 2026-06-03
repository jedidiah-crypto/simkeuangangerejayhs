<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KategoriKeuangan;
use Illuminate\Http\Request;

/**
 * KategoriApiController
 * CRUD kategori keuangan (pemasukan & pengeluaran).
 */
class KategoriApiController extends Controller
{
    // ─── GET /api/v1/kategori?type=pemasukan ────────────────────────────────
    public function index(Request $request)
    {
        $query = KategoriKeuangan::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type); // pemasukan | pengeluaran
        }

        return response()->json([
            'status' => true,
            'data'   => $query->orderBy('nama')->get()->map(fn($k) => [
                'id'   => $k->id,
                'nama' => $k->nama,
                'type' => $k->type,
            ]),
        ]);
    }

    // ─── POST /api/v1/kategori ───────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'type' => 'required|in:pemasukan,pengeluaran',
        ]);

        $kategori = KategoriKeuangan::create($data);

        return response()->json([
            'status'  => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data'    => ['id' => $kategori->id, 'nama' => $kategori->nama, 'type' => $kategori->type],
        ], 201);
    }

    // ─── PUT /api/v1/kategori/{id} ───────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $kategori = KategoriKeuangan::findOrFail($id);

        $data = $request->validate([
            'nama' => 'sometimes|string|max:100',
            'type' => 'sometimes|in:pemasukan,pengeluaran',
        ]);

        $kategori->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Kategori diperbarui',
            'data'    => ['id' => $kategori->id, 'nama' => $kategori->nama, 'type' => $kategori->type],
        ]);
    }

    // ─── DELETE /api/v1/kategori/{id} ────────────────────────────────────────
    public function destroy($id)
    {
        $kategori = KategoriKeuangan::withCount(['pemasukan', 'pengeluaran'])->findOrFail($id);

        if ($kategori->pemasukan_count > 0 || $kategori->pengeluaran_count > 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Kategori tidak dapat dihapus karena masih digunakan dalam transaksi.',
            ], 422);
        }

        $kategori->delete();

        return response()->json(['status' => true, 'message' => 'Kategori berhasil dihapus']);
    }
}
