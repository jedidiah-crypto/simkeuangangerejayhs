<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:1rem;">
            <a href="{{ route('pengeluaran.index') }}" style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:rgba(180,140,50,0.06);border:1px solid var(--border);color:#57534e;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='rgba(180,140,50,0.1)'" onmouseout="this.style.background='rgba(180,140,50,0.06)'">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#f43f5e;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Tambah Data</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#1a1200;margin:0;">Catat Pengeluaran</h1>
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
                        <p style="font-size:0.875rem;font-weight:600;color:#1a1200;">Dana Keluar Gereja</p>
                        <p style="font-size:0.75rem;color:#78716c;">Isi semua detail transaksi pengeluaran dengan benar</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengeluaran.store') }}" method="POST" enctype="multipart/form-data" style="padding:1.75rem;">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
                    <div>
                        <label class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" class="form-input" required value="{{ old('tanggal', now()->toDateString()) }}">
                    </div>
                    <div>
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" name="nominal" id="nominal" step="1" class="form-input" required placeholder="0">
                        <p id="nominalDisplay" style="font-size:0.75rem;color:#fb7185;margin-top:0.3rem;font-weight:600;"></p>
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
                        <label class="form-label">Rekening Sumber</label>
                        <select name="rekening_id" class="form-select">
                            <option value="">Pilih rekening</option>
                            @foreach($rekening as $item)
                                <option value="{{ $item->id }}" {{ old('rekening_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }} - {{ $item->nomor_rekening ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Metode Pembayaran</label>
                    <input type="text" name="metode" class="form-input" placeholder="Tunai / Transfer / Virtual" value="{{ old('metode') }}">
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="form-textarea" placeholder="Misalnya: belanja kebutuhan ibadah...">{{ old('keterangan') }}</textarea>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Upload Nota / Struk</label>
                    <div style="border:2px dashed rgba(244,63,94,0.2);border-radius:14px;padding:1.5rem;text-align:center;cursor:pointer;transition:border-color 0.2s;position:relative;" id="dropzone" onmouseover="this.style.borderColor='rgba(244,63,94,0.4)'" onmouseout="this.style.borderColor='rgba(244,63,94,0.2)'">
                        <input type="file" name="nota" id="nota-file" accept="image/*" style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;" onchange="showFileName(this)">
                        <div id="dropzoneContent">
                            <div style="font-size:1.75rem;margin-bottom:0.5rem;">🧾</div>
                            <p style="font-size:0.83rem;color:#57534e;">Klik atau drag & drop struk/nota</p>
                            <p style="font-size:0.72rem;color:#78716c;margin-top:0.2rem;">PNG, JPG hingga 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Scan Feature -->
                <div style="margin-bottom:1.75rem;padding:1rem;background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.2);border-radius:14px;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                    <div>
                        <p style="font-size:0.8rem;font-weight:600;color:#fbbf24;">✨ Scan Otomatis</p>
                        <p style="font-size:0.72rem;color:#78716c;margin-top:0.2rem;">Upload struk lalu klik scan untuk mengisi nominal & keterangan otomatis</p>
                    </div>
                    <button type="button" id="scan-button" style="margin-left:auto;display:inline-flex;align-items:center;gap:0.4rem;padding:0.55rem 1rem;border-radius:10px;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);color:#fbbf24;font-size:0.8rem;font-weight:600;cursor:pointer;transition:all 0.2s;font-family:inherit;" onmouseover="this.style.background='rgba(245,158,11,0.25)'" onmouseout="this.style.background='rgba(245,158,11,0.15)'">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Scan Struk
                    </button>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                    <a href="{{ route('pengeluaran.index') }}" class="btn-ghost">Batal</a>
                    <button type="submit" class="btn-danger">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/tesseract.js@2.1.4/dist/tesseract.min.js"></script>
    <script>
        const nomInput = document.getElementById('nominal');
        const nomDisplay = document.getElementById('nominalDisplay');
        nomInput.addEventListener('input', function() {
            nomDisplay.textContent = this.value ? 'Rp ' + parseInt(this.value).toLocaleString('id-ID') : '';
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
        document.getElementById('scan-button')?.addEventListener('click', async function () {
            const fileInput = document.getElementById('nota-file');
            if (!fileInput.files.length) { alert('Pilih file struk terlebih dahulu.'); return; }
            const file = fileInput.files[0];
            const imageUrl = URL.createObjectURL(file);
            const worker = Tesseract.createWorker({ logger: m => console.log(m) });
            this.disabled = true; this.textContent = 'Memindai...';
            await worker.load(); await worker.loadLanguage('eng'); await worker.initialize('eng');
            const { data: { text } } = await worker.recognize(imageUrl);
            await worker.terminate(); URL.revokeObjectURL(imageUrl);
            this.disabled = false; this.innerHTML = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg> Scan Struk';
            const nominalMatch = text.replace(/[.,]/g, '').match(/\b(\d{3,})\b/);
            if (nominalMatch) { document.getElementById('nominal').value = parseInt(nominalMatch[1], 10); nomInput.dispatchEvent(new Event('input')); }
            document.getElementById('keterangan').value = text.split('\n').slice(0, 3).join(' ');
            alert('✅ Pemindaian selesai! Periksa dan simpan transaksi.');
        });
    </script>
</x-app-layout>
