{{--
    ============================================================
    TAMBAHKAN KE navigation.blade.php
    Ganti bagian link "Pengeluaran" yang sudah ada dengan ini.
    Badge merah akan muncul otomatis jika ada pending.
    ============================================================
--}}

{{-- Di bagian <head> atau sebelum tag </style> di navigation.blade.php, tambahkan CSS badge: --}}
{{--
.nav-badge {
    position:absolute; top:-4px; right:-6px;
    min-width:16px; height:16px; border-radius:8px;
    background:#f43f5e; color:#fff;
    font-size:0.6rem; font-weight:700;
    display:flex; align-items:center; justify-content:center;
    padding:0 4px; border:1.5px solid #1a1100;
    line-height:1;
}
--}}

{{-- Ganti link Pengeluaran di desktop nav dengan ini: --}}
@php
    $navPendingCount = auth()->user()->isPendeta()
        ? \App\Models\Pengeluaran::where('status','pending')->count()
        : 0;
@endphp

<a href="{{ route('pengeluaran.index') }}"
   class="nav-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}"
   style="position:relative;">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
    </svg>
    Pengeluaran
    @if($navPendingCount > 0)
    <span class="nav-badge">{{ $navPendingCount > 9 ? '9+' : $navPendingCount }}</span>
    @endif
</a>

{{--
    ============================================================
    Tambahkan juga CSS ini di dalam <style> di navigation.blade.php
    (sisipkan setelah blok .nav-link)
    ============================================================
    .nav-badge {
        position:absolute; top:-4px; right:-6px;
        min-width:16px; height:16px; border-radius:8px;
        background:#f43f5e; color:#fff;
        font-size:0.6rem; font-weight:700;
        display:flex; align-items:center; justify-content:center;
        padding:0 4px; border:1.5px solid #1a1100;
        line-height:1;
    }
    ============================================================
--}}
