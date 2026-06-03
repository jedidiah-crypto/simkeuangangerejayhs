<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'SIM Keuangan YHS Church Solo') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                /* ── BACKGROUND ── */
                --bg-base:      #f7f5f0;
                --bg-card:      #ffffff;
                --bg-elevated:  #fefcf7;
                --bg-dark:      #1a1100;
                --bg-dark-2:    #2d1e00;

                /* ── BORDER ── */
                --border:         rgba(180,140,50,0.18);
                --border-bright:  rgba(180,140,50,0.45);

                /* ── TEXT ── */
                --text-primary:   #1a1200;
                --text-muted:     #78716c;
                --text-light:     #a8997a;

                /* ── GOLD PALETTE ── */
                --gold-1:    #c8961a;
                --gold-2:    #e8b84b;
                --gold-3:    #f5d280;
                --gold-dark: #8a6400;
                --glow-gold: rgba(200,150,26,0.18);

                /* ── SEMANTIC COLORS ── */
                --accent-green: #2d7a4a;
                --accent-red:   #c0392b;
                --accent-blue:  #1a5f8a;
                --accent-amber: #c8961a;
            }

            * { box-sizing: border-box; }

            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: var(--bg-base);
                color: var(--text-primary);
                min-height: 100vh;
                background-image:
                    radial-gradient(ellipse 70% 40% at 50% 0%, rgba(200,150,26,0.05) 0%, transparent 60%);
            }

            .font-display { font-family: 'Cinzel', serif; }

            /* ── GLASS / WHITE CARD ── */
            .glass-card {
                background: var(--bg-card);
                border: 1px solid var(--border);
                border-radius: 18px;
                box-shadow: 0 2px 16px rgba(0,0,0,0.05);
                transition: border-color 0.3s, box-shadow 0.3s;
            }
            .glass-card:hover {
                border-color: var(--border-bright);
                box-shadow: 0 4px 24px var(--glow-gold);
            }

            /* ── STAT CARDS ── */
            .stat-card {
                position: relative; overflow: hidden;
                border-radius: 18px; padding: 1.5rem;
                background: var(--bg-card);
                border: 1px solid var(--border);
                box-shadow: 0 2px 12px rgba(0,0,0,0.05);
                transition: transform 0.25s, box-shadow 0.25s;
            }
            .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px var(--glow-gold); }
            .stat-card::before {
                content: ''; position: absolute;
                top: 0; left: 0; right: 0; height: 3px;
            }
            .stat-blue::before   { background: linear-gradient(90deg, var(--accent-blue), #60a5fa); }
            .stat-emerald::before { background: linear-gradient(90deg, var(--accent-green), #4ade80); }
            .stat-rose::before   { background: linear-gradient(90deg, var(--accent-red), #f87171); }
            .stat-violet::before { background: linear-gradient(90deg, var(--gold-1), var(--gold-3)); }

            /* ── BUTTONS ── */
            .btn-primary {
                display: inline-flex; align-items: center; gap: 0.5rem;
                padding: 0.6rem 1.25rem; border-radius: 10px;
                font-size: 0.82rem; font-weight: 600;
                background: linear-gradient(135deg, var(--gold-1), var(--gold-dark));
                color: #fff; border: none; cursor: pointer;
                transition: all 0.2s;
                box-shadow: 0 3px 16px rgba(200,150,26,0.35);
                font-family: inherit;
            }
            .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 5px 22px rgba(200,150,26,0.5); }

            .btn-success {
                display: inline-flex; align-items: center; gap: 0.5rem;
                padding: 0.6rem 1.25rem; border-radius: 10px;
                font-size: 0.82rem; font-weight: 600;
                background: linear-gradient(135deg, #2d7a4a, #1a5c35);
                color: #fff; border: none; cursor: pointer;
                transition: all 0.2s; box-shadow: 0 3px 14px rgba(45,122,74,0.3);
                font-family: inherit;
            }
            .btn-success:hover { transform: translateY(-1px); box-shadow: 0 5px 20px rgba(45,122,74,0.4); }

            .btn-danger {
                display: inline-flex; align-items: center; gap: 0.5rem;
                padding: 0.6rem 1.25rem; border-radius: 10px;
                font-size: 0.82rem; font-weight: 600;
                background: linear-gradient(135deg, #c0392b, #922b21);
                color: #fff; border: none; cursor: pointer;
                transition: all 0.2s; box-shadow: 0 3px 14px rgba(192,57,43,0.3);
                font-family: inherit;
            }
            .btn-danger:hover { transform: translateY(-1px); box-shadow: 0 5px 20px rgba(192,57,43,0.4); }

            .btn-ghost {
                display: inline-flex; align-items: center; gap: 0.5rem;
                padding: 0.6rem 1.25rem; border-radius: 10px;
                font-size: 0.82rem; font-weight: 600;
                background: transparent; color: var(--text-muted);
                border: 1px solid var(--border); cursor: pointer;
                transition: all 0.2s; font-family: inherit;
            }
            .btn-ghost:hover { background: rgba(200,150,26,0.06); border-color: var(--gold-1); color: var(--gold-dark); }

            /* ── TABLE ── */
            .data-table { width: 100%; border-collapse: collapse; }
            .data-table thead th {
                padding: 0.85rem 1.25rem; text-align: left;
                font-size: 0.7rem; font-weight: 700;
                letter-spacing: 0.07em; text-transform: uppercase;
                color: var(--text-muted); border-bottom: 1px solid var(--border);
                background: #fdf9f0;
            }
            .data-table tbody tr {
                border-bottom: 1px solid rgba(180,140,50,0.07);
                transition: background 0.15s;
            }
            .data-table tbody tr:hover { background: rgba(200,150,26,0.04); }
            .data-table tbody td { padding: 0.95rem 1.25rem; font-size: 0.875rem; vertical-align: middle; }

            /* ── FORM INPUTS ── */
            .form-input, .form-select, .form-textarea {
                width: 100%;
                background: #fefcf7;
                color: var(--text-primary);
                border: 1.5px solid rgba(180,140,50,0.2);
                border-radius: 12px;
                padding: 0.65rem 1rem;
                font-size: 0.875rem;
                font-family: inherit;
                transition: border-color 0.2s, box-shadow 0.2s;
                outline: none;
            }
            .form-input:focus, .form-select:focus, .form-textarea:focus {
                border-color: var(--gold-1);
                box-shadow: 0 0 0 3px rgba(200,150,26,0.1);
            }
            .form-select option { background: #fff; color: var(--text-primary); }
            .form-label {
                display: block; font-size: 0.77rem; font-weight: 700;
                color: var(--text-muted); margin-bottom: 0.4rem;
                letter-spacing: 0.05em; text-transform: uppercase;
            }

            /* ── BADGES ── */
            .badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.25rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
            .badge-green { background: rgba(45,122,74,0.1);  color: #2d7a4a; border: 1px solid rgba(45,122,74,0.25); }
            .badge-amber { background: rgba(200,150,26,0.1); color: var(--gold-1); border: 1px solid rgba(200,150,26,0.25); }
            .badge-rose  { background: rgba(192,57,43,0.1);  color: #c0392b;  border: 1px solid rgba(192,57,43,0.25); }

            /* ── AMOUNTS ── */
            .amount-in  { color: var(--accent-green); font-weight: 700; }
            .amount-out { color: var(--accent-red);   font-weight: 700; }

            /* ── DIVIDER ── */
            .divider { height: 1px; background: var(--border); margin: 1.5rem 0; }

            /* ── PAGE ANIMATION ── */
            @keyframes fadeUp {
                from { opacity:0; transform: translateY(14px); }
                to   { opacity:1; transform: translateY(0); }
            }
            .animate-in  { animation: fadeUp 0.4s ease both; }
            .delay-1 { animation-delay: 0.05s; }
            .delay-2 { animation-delay: 0.10s; }
            .delay-3 { animation-delay: 0.15s; }
            .delay-4 { animation-delay: 0.20s; }
        </style>
    </head>
    <body>
        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header style="background: linear-gradient(135deg, #1a1100, #2d1e00); border-bottom: 1px solid rgba(200,150,26,0.2); position: sticky; top: 64px; z-index: 40;">
                    <div style="max-width:1280px; margin:0 auto; padding: 1.1rem 1.5rem;">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main style="max-width:1280px; margin:0 auto; padding: 2rem 1.5rem;">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
