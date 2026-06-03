<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:1rem;">
            <a href="{{ route('donatur.index') }}" style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.04);border:1px solid var(--border);color:#94a3b8;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='rgba(255,255,255,0.04)'">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#06b6d4;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Tambah Data</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#e2e8f0;margin:0;">Tambah Donatur</h1>
            </div>
        </div>
    </x-slot>

    <div style="max-width:600px;margin:0 auto;" class="animate-in">
        <div class="glass-card" style="padding:0;overflow:hidden;">
            <div style="background:linear-gradient(135deg,rgba(6,182,212,0.12),rgba(34,211,238,0.05));padding:1.5rem;border-bottom:1px solid var(--border);">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="width:40px;height:40px;border-radius:12px;background:rgba(6,182,212,0.2);display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#22d3ee"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <p style="font-size:0.875rem;font-weight:600;color:#e2e8f0;">Data Donatur Baru</p>
                        <p style="font-size:0.75rem;color:#64748b;">Tambahkan informasi donatur lengkap</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('donatur.store') }}" method="POST" style="padding:1.75rem;">
                @csrf
                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Nama Donatur *</label>
                    <input type="text" name="nama" class="form-input" required placeholder="Masukkan nama donatur" value="{{ old('nama') }}">
                    @error('nama') <p style="font-size:0.75rem;color:#fb7185;margin-top:0.35rem;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" placeholder="nama@contoh.com" value="{{ old('email') }}">
                    @error('email') <p style="font-size:0.75rem;color:#fb7185;margin-top:0.35rem;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-input" placeholder="08xxxxxxxxxx" value="{{ old('telepon') }}">
                    @error('telepon') <p style="font-size:0.75rem;color:#fb7185;margin-top:0.35rem;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:1.75rem;">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" rows="3" class="form-textarea" placeholder="Alamat lengkap donatur...">{{ old('alamat') }}</textarea>
                    @error('alamat') <p style="font-size:0.75rem;color:#fb7185;margin-top:0.35rem;">{{ $message }}</p> @enderror
                </div>

                <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                    <a href="{{ route('donatur.index') }}" class="btn-ghost">Batal</a>
                    <button type="submit" class="btn-primary">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Donatur
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
