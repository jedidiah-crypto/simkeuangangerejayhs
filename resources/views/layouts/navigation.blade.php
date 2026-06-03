<nav x-data="{ open: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10)"
     :class="scrolled ? 'nav-scrolled' : ''"
     style="position: sticky; top: 0; z-index: 50; transition: all 0.3s;"
     class="sim-nav">

    <style>
        .sim-nav {
            background: linear-gradient(135deg, #1a1100 0%, #2d1e00 50%, #1a1100 100%);
            border-bottom: 2px solid #c8961a;
            box-shadow: 0 2px 20px rgba(200,150,26,0.2);
        }
        .nav-scrolled {
            background: rgba(26,17,0,0.97) !important;
            box-shadow: 0 4px 30px rgba(200,150,26,0.3) !important;
        }
        .nav-inner { max-width: 1280px; margin: 0 auto; padding: 0 1.5rem; display: flex; align-items: center; justify-content: space-between; height: 68px; }

        /* Brand / Logo */
        .nav-brand { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .nav-logo-wrap {
            width: 46px; height: 46px; border-radius: 10px;
            background: #000; overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 12px rgba(200,150,26,0.45);
            flex-shrink: 0;
        }
        .nav-logo-wrap img { width: 42px; height: 42px; object-fit: contain; }
        .nav-brand-text {
            font-family: 'Cinzel', serif;
            font-weight: 700; font-size: 0.95rem;
            background: linear-gradient(135deg, #f5d280, #c8961a);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }
        .nav-brand-sub { font-size: 0.62rem; color: #a8997a; font-weight: 400; display: block; letter-spacing: 0.04em; font-family: 'Plus Jakarta Sans', sans-serif; -webkit-text-fill-color: #a8997a; }

        /* Nav Links */
        .nav-links { display: flex; align-items: center; gap: 0.15rem; }
        .nav-link {
            display: flex; align-items: center; gap: 0.4rem;
            padding: 0.45rem 0.85rem; border-radius: 8px;
            font-size: 0.82rem; font-weight: 500; color: #c8b070;
            text-decoration: none; transition: all 0.2s; position: relative; border: 1px solid transparent;
        }
        .nav-link:hover { color: #f5d280; background: rgba(200,150,26,0.1); }
        .nav-link.active { color: #f5d280; background: rgba(200,150,26,0.18); border-color: rgba(200,150,26,0.3); }
        .nav-link svg { width: 15px; height: 15px; }
        .nav-separator { width: 1px; height: 20px; background: rgba(200,150,26,0.2); margin: 0 0.35rem; }

        /* User area */
        .nav-user { display: flex; align-items: center; gap: 0.65rem; position: relative; }
        .nav-user-name { font-size: 0.8rem; color: #c8b070; font-weight: 500; }
        .nav-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, #c8961a, #8a6400);
            border: 1.5px solid rgba(200,150,26,0.5);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 700; color: #fff; cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 10px rgba(200,150,26,0.3);
        }
        .nav-avatar:hover { transform: scale(1.05); box-shadow: 0 4px 16px rgba(200,150,26,0.5); }

        /* Dropdown */
        .dropdown-menu {
            position: absolute; right: 0; top: calc(100% + 8px);
            background: #1a1100; border: 1px solid rgba(200,150,26,0.25);
            border-radius: 14px; padding: 0.5rem; min-width: 190px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5), 0 0 30px rgba(200,150,26,0.1);
        }
        .dropdown-item {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.6rem 0.8rem; border-radius: 10px; font-size: 0.82rem;
            color: #c8b070; text-decoration: none; transition: all 0.15s;
            cursor: pointer; border: none; background: none; width: 100%; font-family: inherit;
        }
        .dropdown-item:hover { background: rgba(200,150,26,0.1); color: #f5d280; }
        .dropdown-item.danger:hover { background: rgba(192,57,43,0.12); color: #f87171; }
        .dropdown-divider { height: 1px; background: rgba(200,150,26,0.12); margin: 0.4rem 0; }

        /* Mobile hamburger */
        .mobile-menu-btn {
            display: none; background: none; border: none; cursor: pointer;
            color: #c8b070; padding: 0.4rem;
        }
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .mobile-menu-btn { display: block; }
        }

        /* Mobile panel */
        .mobile-nav {
            background: #1a1100;
            border-top: 1px solid rgba(200,150,26,0.15);
            padding: 0.75rem 1.5rem 1rem;
        }
        .mobile-nav .nav-link {
            display: flex; padding: 0.65rem 0.85rem;
            border-bottom: 1px solid rgba(200,150,26,0.08);
            border-radius: 0;
        }
        .mobile-nav .nav-link:last-child { border-bottom: none; }
    </style>

    <div class="nav-inner">
        <!-- ── BRAND / LOGO ── -->
        <a href="{{ route('dashboard') }}" class="nav-brand">
            {{--
                LOGO: Letakkan file logo di public/images/logo.png
                Logo YHS Church Solo memiliki background hitam,
                dibungkus kotak hitam agar terlihat bersih di navbar gelap.
            --}}
            <div class="nav-logo-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="YHS Church Solo Logo">
            </div>
            <div>
                <span class="nav-brand-text">YHS Church Solo</span>
                <span class="nav-brand-sub">Sistem Informasi Keuangan</span>
            </div>
        </a>

        <!-- ── DESKTOP LINKS ── -->
        <div class="nav-links hidden sm:flex">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('pemasukan.index') }}"
               class="nav-link {{ request()->routeIs('pemasukan.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                Pemasukan
            </a>
            <a href="{{ route('pengeluaran.index') }}"
               class="nav-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                Pengeluaran
            </a>
            <a href="{{ route('reports.period') }}"
               class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan
            </a>

            <div class="nav-separator"></div>

            <a href="{{ route('donatur.index') }}"
               class="nav-link {{ request()->routeIs('donatur.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Donatur
            </a>
        </div>

        <!-- ── USER MENU ── -->
        <div class="nav-user">
            <span class="nav-user-name hidden sm:block">{{ Auth::user()->name }}</span>
            <div x-data="{ open: false }" style="position:relative;">
                <div class="nav-avatar" @click="open = !open">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="dropdown-menu" x-show="open" @click.outside="open = false" x-cloak>
                    <div style="padding: 0.6rem 0.8rem 0.5rem; border-bottom: 1px solid rgba(200,150,26,0.12); margin-bottom: 0.35rem;">
                        <div style="font-size:0.82rem; color:#f5d280; font-weight:600;">{{ Auth::user()->name }}</div>
                        <div style="font-size:0.72rem; color:#a8997a;">{{ Auth::user()->email }}</div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profil Saya
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item danger">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile button -->
            <button class="mobile-menu-btn sm:hidden" @click="open = !open">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Panel -->
    <div class="mobile-nav sm:hidden" x-show="open" x-cloak>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('pemasukan.index') }}" class="nav-link {{ request()->routeIs('pemasukan.*') ? 'active' : '' }}">Pemasukan</a>
        <a href="{{ route('pengeluaran.index') }}" class="nav-link {{ request()->routeIs('pengeluaran.*') ? 'active' : '' }}">Pengeluaran</a>
        <a href="{{ route('reports.period') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Laporan</a>
        <a href="{{ route('donatur.index') }}" class="nav-link {{ request()->routeIs('donatur.*') ? 'active' : '' }}">Donatur</a>
    </div>
</nav>
