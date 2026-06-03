<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * UserApiController
 * Manajemen user oleh admin. Hanya bisa diakses role:admin.
 * Terdaftar di routes/api.php dengan middleware('role:admin').
 */
class UserApiController extends Controller
{
    // ─── GET /api/v1/users ───────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('q')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }

        $perPage = min((int) ($request->per_page ?? 15), 100);
        $items   = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'status' => true,
            'data'   => $items->map(fn($u) => $this->format($u)),
            'meta'   => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'total'        => $items->total(),
            ],
        ]);
    }

    // ─── GET /api/v1/users/{id} ──────────────────────────────────────────────
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);

        return response()->json(['status' => true, 'data' => $this->format($user)]);
    }

    // ─── POST /api/v1/users ──────────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'roles'    => 'nullable|array',
            'roles.*'  => 'exists:roles,id',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (! empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return response()->json([
            'status'  => true,
            'message' => 'User berhasil ditambahkan',
            'data'    => $this->format($user->load('roles')),
        ], 201);
    }

    // ─── PUT /api/v1/users/{id} ──────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'     => 'sometimes|string|max:100',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'roles'    => 'nullable|array',
            'roles.*'  => 'exists:roles,id',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update(\Arr::except($data, ['roles']));

        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return response()->json([
            'status'  => true,
            'message' => 'User berhasil diperbarui',
            'data'    => $this->format($user->fresh('roles')),
        ]);
    }

    // ─── DELETE /api/v1/users/{id} ───────────────────────────────────────────
    public function destroy(Request $request, $id)
    {
        if ((int) $id === $request->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Tidak dapat menghapus akun sendiri.',
            ], 422);
        }

        $user = User::findOrFail($id);
        $user->tokens()->delete();
        $user->roles()->detach();
        $user->delete();

        return response()->json(['status' => true, 'message' => 'User berhasil dihapus']);
    }

    private function format(User $u): array
    {
        return [
            'id'         => $u->id,
            'name'       => $u->name,
            'email'      => $u->email,
            'avatar_url' => $u->avatar ? asset('storage/' . $u->avatar) : null,
            'roles'      => $u->roles->map(fn($r) => ['id' => $r->id, 'name' => $r->name]),
            'created_at' => $u->created_at?->toDateTimeString(),
        ];
    }
}
