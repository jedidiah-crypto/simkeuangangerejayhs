<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:0.72rem;font-weight:600;color:#8b5cf6;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">Data</p>
                <h1 class="font-display" style="font-size:1.4rem;font-weight:700;color:#e2e8f0;margin:0;">Import Data</h1>
                <p style="font-size:0.8rem;color:#64748b;margin-top:0.2rem;">Import transaksi dari file Excel atau CSV</p>
            </div>
        </div>
    </x-slot>

    <div class="glass-card animate-in" style="max-width:600px;margin:0 auto;">
        @if ($errors->any())
            <div style="padding:1rem;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:12px;margin-bottom:1.5rem;">
                <div style="display:flex;gap:0.75rem;margin-bottom:0.75rem;">
                    <div style="color:#ef4444;font-size:1.2rem;">⚠️</div>
                    <div>
                        <p style="font-weight:600;color:#fecaca;margin:0;">Validasi gagal</p>
                        <ul style="margin:0.5rem 0 0;padding-left:1.5rem;color:#fca5a5;font-size:0.85rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div style="padding:1rem;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:12px;margin-bottom:1.5rem;">
                <div style="display:flex;gap:0.75rem;">
                    <div style="color:#22c55e;font-size:1.2rem;">✅</div>
                    <div>
                        <p style="font-weight:600;color:#86efac;margin:0;">{{ session('success') }}</p>
                        @if (session('errors'))
                            <ul style="margin:0.5rem 0 0;padding-left:1.5rem;color:#fca5a5;font-size:0.8rem;">
                                @foreach (session('errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div style="padding:1rem;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:12px;margin-bottom:1.5rem;">
                <div style="display:flex;gap:0.75rem;">
                    <div style="color:#ef4444;font-size:1.2rem;">❌</div>
                    <p style="color:#fca5a5;margin:0;font-size:0.9rem;">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <form action="{{ route('import.process') }}" method="POST" enctype="multipart/form-data" style="padding:0;">
            @csrf

            <div style="padding:2rem;text-align:center;border:2px dashed rgba(139,92,246,0.3);border-radius:12px;cursor:pointer;transition:all 0.3s;" onmouseover="this.style.background='rgba(139,92,246,0.05)'" onmouseout="this.style.background='transparent'" onclick="document.getElementById('fileInput').click()">
                <input type="file" id="fileInput" name="file" accept=".xlsx,.xls,.csv" style="display:none;" onchange="updateFileName(this)">
                <div style="font-size:2.5rem;margin-bottom:0.75rem;">📁</div>
                <p style="margin:0 0 0.5rem;font-weight:600;color:#e2e8f0;">Pilih file Excel atau CSV</p>
                <p style="margin:0;font-size:0.85rem;color:#94a3b8;">Format: .xlsx, .xls, atau .csv (max 5MB)</p>
                <p id="fileName" style="margin:1rem 0 0;padding:0.75rem;background:rgba(139,92,246,0.1);border-radius:8px;font-size:0.8rem;color:#a78bfa;display:none;"></p>
            </div>

            <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid rgba(139,92,246,0.2);">
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Import Data
                </button>
            </div>
        </form>

        <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid rgba(139,92,246,0.2);">
            <p style="font-size:0.8rem;color:#94a3b8;margin:0 0 0.75rem;font-weight:600;">📋 Format yang diperlukan:</p>
            <div style="background:rgba(139,92,246,0.05);padding:1rem;border-radius:8px;border-left:3px solid #8b5cf6;">
                <table style="width:100%;font-size:0.8rem;color:#e2e8f0;border-collapse:collapse;">
                    <tr>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);font-weight:600;color:#a78bfa;"><strong>Kolom</strong></td>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);font-weight:600;color:#a78bfa;"><strong>Deskripsi</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">type</td>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">"pemasukan" atau "pengeluaran"</td>
                    </tr>
                    <tr>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">tanggal</td>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">YYYY-MM-DD atau DD/MM/YYYY</td>
                    </tr>
                    <tr>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">nominal</td>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">Angka (bisa dengan pemisah . atau ,)</td>
                    </tr>
                    <tr>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">kategori</td>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">Nama kategori yang ada di sistem</td>
                    </tr>
                    <tr>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">metode</td>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">Untuk pengeluaran: "Tunai" atau "Transfer"</td>
                    </tr>
                    <tr>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">rekening_sumber</td>
                        <td style="padding:0.4rem;border-bottom:1px solid rgba(139,92,246,0.2);">Nama rekening (opsional untuk pengeluaran)</td>
                    </tr>
                    <tr>
                        <td style="padding:0.4rem;">keterangan</td>
                        <td style="padding:0.4rem;">Catatan transaksi (opsional)</td>
                    </tr>
                </table>
            </div>

            <div style="margin-top:1rem;padding:1rem;background:rgba(59,130,246,0.05);border-radius:8px;border-left:3px solid #3b82f6;">
                <p style="font-size:0.8rem;color:#93c5fd;margin:0;"><strong>💡 Contoh baris data:</strong></p>
                <p style="font-size:0.75rem;color:#bfdbfe;margin:0.5rem 0;font-family:monospace;">pemasukan | 2026-06-01 | 1500000 | Persembahan Mingguan | Tunai | | Donasi rutin minggu</p>
                <p style="font-size:0.75rem;color:#bfdbfe;margin:0.5rem 0;font-family:monospace;">pengeluaran | 2026-06-03 | 250000 | Gaji/Honor | Transfer | BCA Gereja | Gaji ketua</p>
            </div>
        </div>

        <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid rgba(139,92,246,0.2);text-align:center;">
            <a href="{{ route('pemasukan.index') }}" class="btn-secondary" style="display:inline-flex;gap:0.5rem;font-size:0.8rem;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Pemasukan
            </a>
        </div>
    </div>
</x-app-layout>

<script>
    function updateFileName(input) {
        const fileName = input.files[0]?.name || '';
        const fileDisplay = document.getElementById('fileName');
        if (fileName) {
            fileDisplay.textContent = '✓ File dipilih: ' + fileName;
            fileDisplay.style.display = 'block';
        } else {
            fileDisplay.style.display = 'none';
        }
    }
</script>
