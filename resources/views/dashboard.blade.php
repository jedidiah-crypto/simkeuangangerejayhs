<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div>
                <p style="font-size:0.75rem; font-weight:600; color:#38bdf8; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:0.3rem;">
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </p>
                <h1 class="font-display" style="font-size:1.5rem; font-weight:700; color:#fef3c7; margin:0;">Dashboard Keuangan</h1>
                <p style="font-size:0.825rem; color:#d4b896; margin-top:0.2rem;">Ringkasan keuangan gereja bulan ini</p>
            </div>
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                <a href="{{ route('reports.import.form') }}" class="btn-ghost" style="font-size:0.8rem; padding:0.55rem 1rem;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Impor Excel
                </a>
                <a href="{{ route('reports.export.pdf', ['from' => now()->startOfMonth()->toDateString(),'to' => now()->endOfMonth()->toDateString()]) }}" class="btn-primary" style="font-size:0.8rem; padding:0.55rem 1rem;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Cetak PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div style="display:flex; flex-direction:column; gap:1.5rem;">


@if(auth()->user()->isPendeta() && $pendingCount > 0)
<div class="animate-in" style="
    background: linear-gradient(135deg, rgba(180,100,0,0.18), rgba(200,150,26,0.08));
    border: 1.5px solid rgba(200,150,26,0.45);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
">
    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <div style="width:38px;height:38px;border-radius:10px;background:rgba(200,150,26,0.2);display:flex;align-items:center;justify-content:center;">
                <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="#f5d280">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p style="font-size:0.82rem;font-weight:700;color:#f5d280;">
                    Menunggu Persetujuan Anda
                </p>
                <p style="font-size:0.72rem;color:#a8997a;margin-top:0.1rem;">
                    {{ $pendingCount }} pengeluaran perlu ditinjau
                </p>
            </div>
        </div>
        <a href="{{ route('pengeluaran.index', ['status' => 'pending']) }}"
           style="font-size:0.75rem;color:#c8961a;font-weight:600;text-decoration:none;
                  border:1px solid rgba(200,150,26,0.35);padding:0.4rem 0.9rem;border-radius:8px;
                  transition:all 0.2s;"
           onmouseover="this.style.background='rgba(200,150,26,0.12)'"
           onmouseout="this.style.background='transparent'">
            Lihat semua →
        </a>
    </div>

    {{-- Tabel pending --}}
    <div style="display:flex;flex-direction:column;gap:0.5rem;">
        @foreach($pendingItems->take(5) as $item)
        <div style="
            display:flex; align-items:center; justify-content:space-between;
            padding:0.85rem 1rem; border-radius:12px;
            background:rgba(26,17,0,0.4); border:1px solid rgba(200,150,26,0.15);
            flex-wrap:wrap; gap:0.5rem;
        ">
            {{-- Info transaksi --}}
            <div style="display:flex;align-items:center;gap:0.75rem;min-width:0;flex:1;">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(244,63,94,0.12);
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#f43f5e">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
                <div style="min-width:0;">
                    <p style="font-size:0.82rem;font-weight:600;color:#f5d280;white-space:nowrap;
                               overflow:hidden;text-overflow:ellipsis;">
                        {{ $item->nomor_transaksi }}
                    </p>
                    <p style="font-size:0.72rem;color:#a8997a;margin-top:0.1rem;">
                        {{ $item->kategori->nama ?? '-' }}
                        &bull; {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMM Y') }}
                    </p>
                </div>
            </div>

            {{-- Nominal --}}
            <div style="text-align:right;flex-shrink:0;">
                <p style="font-size:0.88rem;font-weight:700;color:#f87171;">
                    -Rp {{ number_format($item->nominal, 0, ',', '.') }}
                </p>
                @if($item->keterangan)
                <p style="font-size:0.7rem;color:#78716c;margin-top:0.1rem;max-width:160px;
                           overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ $item->keterangan }}
                </p>
                @endif
            </div>

            {{-- Tombol aksi --}}
            <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                {{-- Tombol Setujui --}}
                <form method="POST" action="{{ route('pengeluaran.approve', $item) }}"
                      onsubmit="return confirm('Setujui pengeluaran {{ $item->nomor_transaksi }}?')">
                    @csrf
                    <button type="submit" style="
                        font-size:0.75rem;font-weight:600;color:#fff;
                        background:rgba(16,185,129,0.85);border:none;
                        padding:0.45rem 0.9rem;border-radius:8px;cursor:pointer;
                        transition:opacity 0.2s;
                    " onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">
                        ✓ Setujui
                    </button>
                </form>

                {{-- Tombol Tolak — pakai modal sederhana --}}
                <button type="button"
                        onclick="document.getElementById('modal-reject-{{ $item->id }}').style.display='flex'"
                        style="
                            font-size:0.75rem;font-weight:600;color:#f87171;
                            background:rgba(244,63,94,0.1);border:1px solid rgba(244,63,94,0.3);
                            padding:0.45rem 0.9rem;border-radius:8px;cursor:pointer;
                            transition:all 0.2s;
                        " onmouseover="this.style.background='rgba(244,63,94,0.2)'"
                           onmouseout="this.style.background='rgba(244,63,94,0.1)'">
                    ✕ Tolak
                </button>
            </div>
        </div>

        {{-- Modal Tolak per item --}}
        <div id="modal-reject-{{ $item->id }}" style="
            display:none; position:fixed; inset:0; z-index:999;
            background:rgba(0,0,0,0.65); align-items:center; justify-content:center;
        ">
            <div style="
                background:#1a1100; border:1px solid rgba(200,150,26,0.3);
                border-radius:16px; padding:1.5rem; width:90%; max-width:420px;
                box-shadow:0 20px 60px rgba(0,0,0,0.6);
            ">
                <h3 style="font-size:0.95rem;font-weight:700;color:#f5d280;margin-bottom:0.35rem;">
                    Tolak Pengeluaran
                </h3>
                <p style="font-size:0.78rem;color:#a8997a;margin-bottom:1rem;">
                    {{ $item->nomor_transaksi }} —
                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                </p>
                <form method="POST" action="{{ route('pengeluaran.reject', $item) }}">
                    @csrf
                    <textarea name="catatan_reject"
                              placeholder="Alasan penolakan (opsional)..."
                              rows="3"
                              style="
                                  width:100%;box-sizing:border-box;
                                  background:rgba(255,255,255,0.05);
                                  border:1px solid rgba(200,150,26,0.2);
                                  border-radius:8px;padding:0.65rem 0.85rem;
                                  color:#f5d280;font-size:0.82rem;
                                  resize:vertical;font-family:inherit;
                                  margin-bottom:1rem;
                              "
                    ></textarea>
                    <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                        <button type="button"
                                onclick="document.getElementById('modal-reject-{{ $item->id }}').style.display='none'"
                                style="font-size:0.8rem;color:#a8997a;background:none;border:1px solid rgba(200,150,26,0.2);
                                       padding:0.5rem 1rem;border-radius:8px;cursor:pointer;">
                            Batal
                        </button>
                        <button type="submit"
                                style="font-size:0.8rem;color:#fff;background:rgba(220,38,38,0.8);
                                       border:none;padding:0.5rem 1.1rem;border-radius:8px;
                                       cursor:pointer;font-weight:600;">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach

        @if($pendingCount > 5)
        <p style="text-align:center;font-size:0.75rem;color:#78716c;margin-top:0.25rem;">
            + {{ $pendingCount - 5 }} pengeluaran lainnya —
            <a href="{{ route('pengeluaran.index', ['status' => 'pending']) }}"
               style="color:#c8961a;text-decoration:none;">lihat semua</a>
        </p>
        @endif
    </div>
</div>
@endif

{{-- ============================================================
     Jika user adalah bendahara, tampilkan info status pengajuan mereka
     ============================================================ --}}
@if(auth()->user()->isBendahara())
@php
    $myPending  = \App\Models\Pengeluaran::where('status','pending')->where('created_by', auth()->id())->count();
    $myRejected = \App\Models\Pengeluaran::where('status','rejected')->where('created_by', auth()->id())
                    ->whereNull('catatan_dibaca_at')->count();
@endphp
@if($myPending > 0 || $myRejected > 0)
<div class="animate-in" style="
    background:rgba(26,17,0,0.5); border:1px solid rgba(200,150,26,0.2);
    border-radius:16px; padding:1rem 1.5rem;
    display:flex; align-items:center; gap:1rem; flex-wrap:wrap;
">
    @if($myPending > 0)
    <div style="display:flex;align-items:center;gap:0.5rem;">
        <span style="width:8px;height:8px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>
        <span style="font-size:0.8rem;color:#f5d280;">
            <strong>{{ $myPending }}</strong> pengajuan sedang menunggu persetujuan pendeta
        </span>
    </div>
    @endif
    @if($myRejected > 0)
    <div style="display:flex;align-items:center;gap:0.5rem;">
        <span style="width:8px;height:8px;border-radius:50%;background:#f87171;display:inline-block;"></span>
        <a href="{{ route('pengeluaran.index', ['status' => 'rejected']) }}"
           style="font-size:0.8rem;color:#f87171;text-decoration:none;">
            <strong>{{ $myRejected }}</strong> pengajuan ditolak — klik untuk lihat alasan
        </a>
    </div>
    @endif
</div>
@endif
@endif

        <!-- Stat Cards -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px,1fr)); gap:1rem;" class="animate-in">
            <div class="stat-card stat-emerald">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <p style="font-size:0.75rem; font-weight:700; color:#1a7a4a; text-transform:uppercase; letter-spacing:0.08em;">Pemasukan Bulan Ini</p>
                        <p style="font-size:1.75rem; font-weight:800; color:#1a1200; margin-top:0.5rem; letter-spacing:-0.02em; font-family:'Plus Jakarta Sans',sans-serif;">Rp {{ number_format($totalPemasukan,0,',','.') }}</p>
                    </div>
                    <div style="width:42px;height:42px;border-radius:12px;background:rgba(16,185,129,0.15);display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#1a7a4a"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    </div>
                </div>
                <a href="{{ route('pemasukan.index') }}" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:#1a7a4a;font-weight:600;text-decoration:none;margin-top:1rem;transition:opacity 0.2s;" onmouseover="this.style.opacity=0.7" onmouseout="this.style.opacity=1">
                    Lihat detail →
                </a>
            </div>

            <div class="stat-card stat-rose">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <p style="font-size:0.75rem; font-weight:700; color:#b91c1c; text-transform:uppercase; letter-spacing:0.08em;">Pengeluaran Bulan Ini</p>
                        <p style="font-size:1.75rem; font-weight:800; color:#1a1200; margin-top:0.5rem; letter-spacing:-0.02em; font-family:'Plus Jakarta Sans',sans-serif;">Rp {{ number_format($totalPengeluaran,0,',','.') }}</p>
                    </div>
                    <div style="width:42px;height:42px;border-radius:12px;background:rgba(244,63,94,0.12);display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#b91c1c"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    </div>
                </div>
                <a href="{{ route('pengeluaran.index') }}" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:#b91c1c;font-weight:600;text-decoration:none;margin-top:1rem;transition:opacity 0.2s;" onmouseover="this.style.opacity=0.7" onmouseout="this.style.opacity=1">
                    Lihat detail →
                </a>
            </div>

            <div class="stat-card stat-violet">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <p style="font-size:0.75rem; font-weight:700; color:#7c3aed; text-transform:uppercase; letter-spacing:0.08em;">Saldo Kas Gereja</p>
                        <p style="font-size:1.75rem; font-weight:800; color:#1a1200; margin-top:0.5rem; letter-spacing:-0.02em; font-family:'Plus Jakarta Sans',sans-serif;">Rp {{ number_format($saldo,0,',','.') }}</p>
                    </div>
                    <div style="width:42px;height:42px;border-radius:12px;background:rgba(139,92,246,0.12);display:flex;align-items:center;justify-content:center;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#7c3aed"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                </div>
                @php $selisih = $totalPemasukan - $totalPengeluaran; @endphp
                <p style="font-size:0.75rem;font-weight:600;margin-top:1rem;color:{{ $selisih >= 0 ? '#1a7a4a' : '#b91c1c' }};">
                    {{ $selisih >= 0 ? '▲' : '▼' }} Rp {{ number_format(abs($selisih),0,',','.') }} bulan ini
                </p>
            </div>
        </div>

        <!-- Charts — wrapper div wajib ada agar Chart.js bisa hitung tinggi -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;" class="animate-in delay-2">
            <div class="glass-card" style="padding:1.5rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
                    <h3 class="font-display" style="font-size:0.9rem;font-weight:700;color:#1a1200;">Tren Pemasukan &amp; Pengeluaran</h3>
                    <span style="font-size:0.72rem;color:#78716c;background:rgba(180,140,50,0.08);padding:0.3rem 0.7rem;border-radius:8px;border:1px solid rgba(180,140,50,0.2);">Mingguan</span>
                </div>
                {{-- Wrapper dengan height eksplisit agar Chart.js tidak collapse --}}
                <div style="position:relative; height:220px;">
                    <canvas id="chart"></canvas>
                </div>
            </div>

            <div class="glass-card" style="padding:1.5rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
                    <h3 class="font-display" style="font-size:0.9rem;font-weight:700;color:#1a1200;">Distribusi Pengeluaran</h3>
                    <span style="font-size:0.72rem;color:#78716c;background:rgba(180,140,50,0.08);padding:0.3rem 0.7rem;border-radius:8px;border:1px solid rgba(180,140,50,0.2);">Per Kategori</span>
                </div>
                {{-- Wrapper dengan height eksplisit agar Chart.js tidak collapse --}}
                <div style="position:relative; height:220px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="glass-card animate-in delay-3" style="padding:1.5rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
                <h3 class="font-display" style="font-size:0.9rem;font-weight:700;color:#1a1200;">Transaksi Terbaru</h3>
                <div style="display:flex;gap:0.5rem;">
                    <a href="{{ route('pemasukan.index') }}" class="btn-ghost" style="font-size:0.72rem;padding:0.35rem 0.75rem;">Semua Pemasukan</a>
                    <a href="{{ route('pengeluaran.index') }}" class="btn-ghost" style="font-size:0.72rem;padding:0.35rem 0.75rem;">Semua Pengeluaran</a>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
                @forelse($latest as $t)
                    @php $isPemasukan = $t->_type === 'pemasukan'; @endphp
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:0.9rem 1rem;border-radius:14px;background:rgba(180,140,50,0.03);border:1px solid rgba(180,140,50,0.1);transition:background 0.15s;cursor:default;"
                         onmouseover="this.style.background='rgba(200,150,26,0.06)'" onmouseout="this.style.background='rgba(180,140,50,0.03)'">
                        <div style="display:flex;align-items:center;gap:0.85rem;">
                            <div style="width:36px;height:36px;border-radius:10px;background:{{ $isPemasukan ? 'rgba(26,122,74,0.12)' : 'rgba(185,28,28,0.1)' }};display:flex;align-items:center;justify-content:center;font-size:1rem;color:{{ $isPemasukan ? '#1a7a4a' : '#b91c1c' }};">
                                {{ $isPemasukan ? '↑' : '↓' }}
                            </div>
                            <div>
                                <p style="font-size:0.85rem;font-weight:600;color:#1a1200;">{{ $t->nomor_transaksi ?? '-' }}</p>
                                <p style="font-size:0.75rem;color:#78716c;margin-top:0.1rem;">{{ $t->keterangan ?? ($t->kategori->nama ?? '-') }}</p>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <p class="{{ $isPemasukan ? 'amount-in' : 'amount-out' }}" style="font-size:0.9rem;">
                                {{ $isPemasukan ? '+' : '-' }}Rp {{ number_format($t->nominal,0,',','.') }}
                            </p>
                            <p style="font-size:0.72rem;color:#78716c;margin-top:0.1rem;">{{ $t->tanggal ?? $t->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:3rem;color:#78716c;">
                        <div style="font-size:2.5rem;margin-bottom:0.75rem;">📊</div>
                        <p style="font-size:0.875rem;">Belum ada transaksi.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const weeklyLabels     = @json($weeklyLabels);
        const pemasukanSeries  = @json($pemasukanSeries);
        const pengeluaranSeries= @json($pengeluaranSeries);
        const categoryLabels   = @json($categoryLabels);
        const categoryValues   = @json($categoryValues);

        Chart.defaults.color       = '#78716c';
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

        // --- Line Chart: Tren Mingguan ---
        const ctx = document.getElementById('chart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weeklyLabels,
                    datasets: [{
                        label: 'Pemasukan',
                        data: pemasukanSeries,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.08)',
                        fill: true, tension: 0.4, borderWidth: 2.5,
                        pointBackgroundColor: '#10b981', pointRadius: 4, pointHoverRadius: 6
                    }, {
                        label: 'Pengeluaran',
                        data: pengeluaranSeries,
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244,63,94,0.08)',
                        fill: true, tension: 0.4, borderWidth: 2.5,
                        pointBackgroundColor: '#f43f5e', pointRadius: 4, pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#44403c', padding: 20, font: { size: 12 } } },
                        tooltip: {
                            backgroundColor: 'rgba(26,18,0,0.92)',
                            borderColor: 'rgba(200,150,26,0.3)', borderWidth: 1,
                            titleColor: '#fef3c7', bodyColor: '#fde68a',
                            padding: 12, cornerRadius: 10,
                            callbacks: {
                                label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        x: { grid: { color: 'rgba(180,140,50,0.1)' }, ticks: { color: '#78716c' } },
                        y: {
                            grid: { color: 'rgba(180,140,50,0.1)' },
                            ticks: {
                                color: '#78716c',
                                callback: v => 'Rp ' + (v >= 1000000
                                    ? (v/1000000).toFixed(1) + 'jt'
                                    : v.toLocaleString('id-ID'))
                            }
                        }
                    }
                }
            });
        }

        // --- Donut Chart: Distribusi Pengeluaran ---
        const donutCtx = document.getElementById('categoryChart');
        if (donutCtx) {
            const hasData = categoryValues.length > 0 && categoryValues.some(v => v > 0);
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: hasData ? categoryLabels : ['Belum ada data'],
                    datasets: [{
                        data: hasData ? categoryValues : [1],
                        backgroundColor: hasData
                            ? ['#f43f5e','#f97316','#f59e0b','#38bdf8','#6366f1','#10b981','#8b5cf6']
                            : ['rgba(255,255,255,0.06)'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#44403c', padding: 16, font: { size: 11 } }
                        },
                        tooltip: {
                            enabled: hasData,
                            backgroundColor: 'rgba(26,18,0,0.92)',
                            borderColor: 'rgba(200,150,26,0.3)', borderWidth: 1,
                            titleColor: '#fef3c7', bodyColor: '#fde68a',
                            padding: 12, cornerRadius: 10,
                            callbacks: {
                                label: ctx => ' Rp ' + ctx.parsed.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
