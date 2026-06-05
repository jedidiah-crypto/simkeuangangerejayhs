<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * AuthApiController
 * Menangani login, logout, profil, dan ganti password untuk Flutter.
 * Menggunakan Laravel Sanctum (token-based).
 */
class AuthApiController extends Controller
{
    // ─── POST /api/v1/login ─────────────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|email',
            'password'    => 'required|string',
            'device_name' => 'nullable|string|max:100',
        ]);

        $user = User::with('roles')->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // Revoke token lama untuk device ini (opsional, hindari token menumpuk)
        $deviceName = $request->device_name ?? ($request->userAgent() ?? 'flutter-app');
        $user->tokens()->where('name', $deviceName)->delete();

        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'data'    => [
                'token'      => $token,
                'token_type' => 'Bearer',
                'user'       => $this->formatUser($user),
            ],
        ]);
    }

    // ─── POST /api/v1/register ───────────────────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Default role: viewer
        $viewerRole = \App\Models\Role::where('name', 'viewer')->first();
        if ($viewerRole) $user->roles()->attach($viewerRole);

        $token = $user->createToken($request->device_name ?? 'flutter-app')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Registrasi berhasil',
            'data'    => [
                'token'      => $token,
                'token_type' => 'Bearer',
                'user'       => $this->formatUser($user->load('roles')),
            ],
        ], 201);
    }

    // ─── POST /api/v1/logout ────────────────────────────────────────────────
    public function logout(Request $request)
    {
        // Hapus token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil',
        ]);
    }

    // ─── GET /api/v1/me ─────────────────────────────────────────────────────
    public function me(Request $request)
    {
        $user = $request->user()->load('roles');

        return response()->json([
            'status' => true,
            'data'   => $this->formatUser($user),
        ]);
    }

    // ─── PUT /api/v1/me/password ────────────────────────────────────────────
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Password saat ini salah.',
            ], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Revoke semua token lain setelah ganti password (keamanan)
        $user->tokens()->where('id', '!=', $request->user()->currentAccessToken()->id)->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Password berhasil diubah',
        ]);
    }

    // ─── POST /api/v1/me/avatar ─────────────────────────────────────────────
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('avatar')->store('avatars', 'public');
        $request->user()->update(['avatar' => $path]);

        return response()->json([
            'status'  => true,
            'message' => 'Avatar diperbarui',
            'data'    => ['avatar_url' => asset('storage/' . $path)],
        ]);
    }

    // ─── Helper ─────────────────────────────────────────────────────────────
    private function formatUser(User $user): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'roles'      => $user->roles->pluck('name'),
            'created_at' => $user->created_at?->toDateTimeString(),
        ];
    }
}
