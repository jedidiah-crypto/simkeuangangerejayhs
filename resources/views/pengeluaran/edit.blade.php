<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:1rem;">
            <a href="{{ route('pengeluaran.index') }}" style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:rgba(180,140,50,0.06);border:1px solid var(--border);color:#57534e;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='rgba(180,140,50,0.1)'" onmouseout="this.style.background='rgba(180,140,50,0.06)'">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#f43f5e;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Edit Data</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#1a1200;margin:0;">Edit Pengeluaran</h1>
            </div>
        </div>
    </x-slot>

    <div style="max-width:720px;margin:0 auto;" class="animate-in">
        <div class="glass-card" style="overflow:hidden;">
            <div style="background:linear-gradient(135deg,rgba(244,63,94,0.1),rgba(225,29,72,0.05));padding:1.5rem;border-bottom:1px solid var(--border);">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="width:40px;height:40px;border-radius:12px;background:rgba(244,63,94,0.15);display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fb7185"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    </div>
                    <div>
                        <p style="font-size:0.875rem;font-weight:600;color:#1a1200;">{{ $pengeluaran->nomor_transaksi }}</p>
                        <p style="font-size:0.75rem;color:#78716c;">Ubah detail transaksi pengeluaran</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengeluaran.update', $pengeluaran) }}" method="POST" enctype="multipart/form-data" style="padding:1.75rem;">
                @csrf
                @method('PUT')
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" class="form-input" required value="{{ old('tanggal', $pengeluaran->tanggal->toDateString()) }}">
                    </div>
                    <div>
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" name="nominal" id="nominal" step="1" class="form-input" required placeholder="0" value="{{ old('nominal', $pengeluaran->nominal) }}">
                        <p id="nominalDisplay" style="font-size:0.75rem;color:#fb7185;margin-top:0.3rem;font-weight:600;"></p>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">Pilih kategori</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->id }}" {{ old('kategori_id', $pengeluaran->kategori_id) == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Rekening Sumber</label>
                        <select name="rekening_id" class="form-select">
                            <option value="">Pilih rekening</option>
                            @foreach($rekening as $item)
                                <option value="{{ $item->id }}" {{ old('rekening_id', $pengeluaran->rekening_id) == $item->id ? 'selected' : '' }}>{{ $item->nama }} - {{ $item->nomor_rekening ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Metode Pembayaran</label>
                    <input type="text" name="metode" class="form-input" placeholder="Tunai / Transfer / Virtual" value="{{ old('metode', $pengeluaran->metode) }}">
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="form-textarea" placeholder="Misalnya: belanja kebutuhan ibadah...">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Upload Nota / Struk</label>
                    @if($pengeluaran->nota)
                        <div style="margin-bottom:1rem;padding:1rem;background:rgba(244,63,94,0.08);border:1px solid rgba(244,63,94,0.2);border-radius:10px;display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:40px;height:40px;border-radius:8px;background:rgba(244,63,94,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fb7185"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div style="flex:1;">
                                <p style="font-size:0.875rem;color:#fb7185;font-weight:600;">Nota Saat Ini</p>
                                <p style="font-size:0.75rem;color:#57534e;margin-top:0.2rem;">
                                    <a href="{{ asset('storage/' . $pengeluaran->nota) }}" target="_blank" style="color:#38bdf8;text-decoration:underline;">Lihat File</a>
                                </p>
                            </div>
                        </div>
                    @endif
                    <div style="border:2px dashed rgba(244,63,94,0.2);border-radius:14px;padding:1.5rem;text-align:center;cursor:pointer;transition:border-color 0.2s;position:relative;" id="dropzone" onmouseover="this.style.borderColor='rgba(244,63,94,0.4)'" onmouseout="this.style.borderColor='rgba(244,63,94,0.2)'">
                        <input type="file" name="nota" id="nota-file" accept="image/*" style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;" onchange="showFileName(this)">
                        <div id="dropzoneContent">
                            <div style="font-size:1.75rem;margin-bottom:0.5rem;">🧾</div>
                            <p style="font-size:0.83rem;color:#57534e;">Klik atau drag & drop struk/nota</p>
                            <p style="font-size:0.72rem;color:#78716c;margin-top:0.2rem;">PNG, JPG hingga 2MB</p>
                        </div>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                    <a href="{{ route('pengeluaran.index') }}" class="btn-ghost">Batal</a>
                    <button type="submit" class="btn-danger">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Perbarui Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const nomInput = document.getElementById('nominal');
        const nomDisplay = document.getElementById('nominalDisplay');
        
        function updateNominalDisplay() {
            nomDisplay.textContent = nomInput.value ? 'Rp ' + parseInt(nomInput.value).toLocaleString('id-ID') : '';
        }
        
        nomInput.addEventListener('input', updateNominalDisplay);
        updateNominalDisplay();
        
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
