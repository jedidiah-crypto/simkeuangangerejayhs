<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:1rem;">
            <a href="{{ route('pemasukan.index') }}" style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:rgba(180,140,50,0.06);border:1px solid var(--border);color:#57534e;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='rgba(180,140,50,0.1)'" onmouseout="this.style.background='rgba(180,140,50,0.06)'">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#10b981;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Tambah Data</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#1a1200;margin:0;">Catat Pemasukan</h1>
            </div>
        </div>
    </x-slot>

    <div style="max-width:720px;margin:0 auto;" class="animate-in">
        <div class="glass-card" style="padding:0;overflow:hidden;">
            <div style="background:linear-gradient(135deg,rgba(16,185,129,0.12),rgba(5,150,105,0.05));padding:1.5rem;border-bottom:1px solid var(--border);">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="width:40px;height:40px;border-radius:12px;background:rgba(16,185,129,0.2);display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#34d399"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    </div>
                    <div>
                        <p style="font-size:0.875rem;font-weight:600;color:#1a1200;">Dana Masuk Gereja</p>
                        <p style="font-size:0.75rem;color:#78716c;">Isi semua detail transaksi pemasukan dengan benar</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('pemasukan.store') }}" method="POST" enctype="multipart/form-data" style="padding:1.75rem;">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" class="form-input" required value="{{ old('tanggal', now()->toDateString()) }}">
                        @error('tanggal') <p style="font-size:0.75rem;color:#fb7185;margin-top:0.35rem;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" name="nominal" step="1" class="form-input" required placeholder="0" id="nominalInput">
                        @error('nominal') <p style="font-size:0.75rem;color:#fb7185;margin-top:0.35rem;">{{ $message }}</p> @enderror
                        <p id="nominalDisplay" style="font-size:0.75rem;color:#34d399;margin-top:0.3rem;font-weight:600;"></p>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">Pilih kategori</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Rekening Tujuan</label>
                        <select name="rekening_id" class="form-select">
                            <option value="">Pilih rekening</option>
                            @foreach($rekening as $item)
                                <option value="{{ $item->id }}" {{ old('rekening_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }} - {{ $item->nomor_rekening ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label">Donatur</label>
                        <select name="donatur_id" class="form-select">
                            <option value="">Pilih donatur (opsional)</option>
                            @foreach($donatur as $item)
                                <option value="{{ $item->id }}" {{ old('donatur_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Metode Pembayaran</label>
                        <input type="text" name="metode" class="form-input" placeholder="Tunai / Transfer / Virtual" value="{{ old('metode') }}">
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Sumber Dana</label>
                    <input type="text" name="sumber_dana" class="form-input" placeholder="Contoh: Persembahan, Donasi khusus" value="{{ old('sumber_dana') }}">
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-textarea" placeholder="Deskripsi singkat transaksi...">{{ old('keterangan') }}</textarea>
                </div>

                <div style="margin-bottom:1.75rem;">
                    <label class="form-label">Upload Bukti Transaksi</label>
                    <div style="border:2px dashed rgba(99,179,237,0.2);border-radius:14px;padding:1.5rem;text-align:center;cursor:pointer;transition:border-color 0.2s;position:relative;" id="dropzone" onmouseover="this.style.borderColor='rgba(56,189,248,0.4)'" onmouseout="this.style.borderColor='rgba(99,179,237,0.2)'">
                        <input type="file" name="bukti" accept="image/*,application/pdf" style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;" id="fileInput" onchange="showFileName(this)">
                        <div id="dropzoneContent">
                            <div style="font-size:1.75rem;margin-bottom:0.5rem;">📎</div>
                            <p style="font-size:0.83rem;color:#57534e;">Klik atau drag & drop file</p>
                            <p style="font-size:0.72rem;color:#78716c;margin-top:0.2rem;">PNG, JPG, PDF hingga 2MB</p>
                        </div>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                    <a href="{{ route('pemasukan.index') }}" class="btn-ghost">Batal</a>
                    <button type="submit" class="btn-success">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Pemasukan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const nominalInput = document.getElementById('nominalInput');
        const nominalDisplay = document.getElementById('nominalDisplay');
        nominalInput.addEventListener('input', function() {
            if (this.value) {
                nominalDisplay.textContent = 'Rp ' + parseInt(this.value).toLocaleString('id-ID');
            } else {
                nominalDisplay.textContent = '';
            }
        });
        function showFileName(input) {
            if (input.files && input.files[0]) {
                document.getElementById('dropzoneContent').innerHTML = `
                    <div style="font-size:1.5rem;margin-bottom:0.5rem;">✅</div>
                    <p style="font-size:0.83rem;color:#34d399;font-weight:600;">${input.files[0].name}</p>
                    <p style="font-size:0.72rem;color:#78716c;margin-top:0.2rem;">${(input.files[0].size/1024).toFixed(1)} KB</p>
                `;
            }
        }
    </script>
</x-app-layout>
