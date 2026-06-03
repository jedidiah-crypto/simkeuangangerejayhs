<x-guest-layout>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background-color: #f7f5f0;
            background-image:
                radial-gradient(ellipse 80% 60% at 50% -5%, rgba(200,150,26,0.1) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 90% 100%, rgba(200,150,26,0.06) 0%, transparent 50%);
            display: flex; align-items: center; justify-content: center; padding: 2rem;
        }

        .login-wrap { width: 100%; max-width: 440px; }

        /* Brand */
        .login-brand { text-align: center; margin-bottom: 2rem; }
        .login-logo-box {
            display: inline-block;
            background: #000;
            border-radius: 20px;
            padding: 10px;
            box-shadow: 0 6px 28px rgba(200,150,26,0.35);
            margin-bottom: 1rem;
        }
        .login-logo-box img { width: 80px; height: 80px; object-fit: contain; display: block; }
        .login-title {
            font-family: 'Cinzel', serif;
            font-size: 1.4rem; font-weight: 700;
            color: #8a6400; margin-bottom: 0.25rem;
        }
        .login-sub { font-size: 0.8rem; color: #78716c; letter-spacing: 0.03em; }

        /* Card */
        .login-card {
            background: #ffffff;
            border: 1px solid rgba(200,150,26,0.2);
            border-radius: 24px;
            padding: 2.2rem;
            box-shadow: 0 8px 48px rgba(200,150,26,0.1), 0 2px 8px rgba(0,0,0,0.06);
        }
        .login-card-header {
            text-align: center; margin-bottom: 1.75rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid rgba(200,150,26,0.12);
        }
        .login-card-title { font-family: 'Cinzel', serif; font-size: 1.05rem; font-weight: 600; color: #8a6400; margin-bottom: 0.25rem; }
        .login-card-sub  { font-size: 0.8rem; color: #78716c; }

        /* Form */
        .form-group { margin-bottom: 1.2rem; }
        .form-label { display: block; font-size: 0.75rem; font-weight: 700; color: #78716c; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.5rem; }
        .form-input {
            width: 100%; background: #fefcf7; color: #1a1200;
            border: 1.5px solid rgba(180,140,50,0.22); border-radius: 12px;
            padding: 0.72rem 1rem; font-size: 0.9rem; font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s; outline: none;
        }
        .form-input:focus { border-color: #c8961a; box-shadow: 0 0 0 3px rgba(200,150,26,0.1); }
        .form-input::placeholder { color: #b0a898; }

        /* Actions row */
        .form-actions { display: flex; align-items: center; justify-content: space-between; margin: 1rem 0 1.5rem; }
        .remember-label { display: flex; align-items: center; gap: 0.45rem; font-size: 0.82rem; color: #78716c; cursor: pointer; }
        .remember-cb { accent-color: #c8961a; width: 15px; height: 15px; }
        .forgot-link { font-size: 0.8rem; color: #c8961a; font-weight: 600; text-decoration: none; transition: color 0.2s; }
        .forgot-link:hover { color: #8a6400; }

        /* Submit button */
        .login-btn {
            width: 100%; padding: 0.85rem;
            background: linear-gradient(135deg, #c8961a, #a07010);
            color: #fff; border: none; border-radius: 12px;
            font-family: 'Cinzel', serif; font-size: 0.95rem; font-weight: 600;
            cursor: pointer; letter-spacing: 0.05em;
            box-shadow: 0 4px 20px rgba(200,150,26,0.4);
            transition: all 0.2s;
        }
        .login-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 28px rgba(200,150,26,0.55); }
        .login-btn:active { transform: translateY(0); }

        /* Messages */
        .error-msg { font-size: 0.78rem; color: #c0392b; margin-top: 0.4rem; }
        .alert-success {
            background: rgba(45,122,74,0.08); color: #2d7a4a;
            border: 1px solid rgba(45,122,74,0.25);
            border-radius: 10px; padding: 0.8rem 1rem;
            font-size: 0.82rem; margin-bottom: 1.25rem;
        }
        .alert-error {
            background: rgba(192,57,43,0.08); color: #c0392b;
            border: 1px solid rgba(192,57,43,0.25);
            border-radius: 10px; padding: 0.8rem 1rem;
            font-size: 0.82rem; margin-bottom: 1.25rem;
        }

        /* Footer text */
        .login-footer { text-align: center; margin-top: 1.5rem; font-size: 0.74rem; color: #a8997a; }
        .login-footer a { color: #c8961a; font-weight: 600; text-decoration: none; }
    </style>

    <div class="login-wrap">
        <!-- Brand -->
        <div class="login-brand">
            <div class="login-logo-box">
                {{--
                    Pastikan logo sudah disimpan di: public/images/logo.png
                --}}
                <img src="{{ asset('images/logo.png') }}" alt="YHS Church Solo">
            </div>
            <div class="login-title">YHS Church Solo</div>
            <div class="login-sub">Sistem Informasi Manajemen Keuangan Gereja</div>
        </div>

        <!-- Card -->
        <div class="login-card">
            <div class="login-card-header">
                <div class="login-card-title">Masuk ke Sistem</div>
                <div class="login-card-sub">Gunakan akun yang telah terdaftar</div>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="alert-success" :status="session('status')" />

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-input" id="email" type="email" name="email"
                           value="{{ old('email') }}" required autofocus autocomplete="username"
                           placeholder="admin@yhschurch.org">
                    @error('email')<div class="error-msg">{{ $message }}</div>@enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <input class="form-input" id="password" type="password" name="password"
                           required autocomplete="current-password" placeholder="••••••••">
                    @error('password')<div class="error-msg">{{ $message }}</div>@enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="form-actions">
                    <label class="remember-label">
                        <input class="remember-cb" type="checkbox" name="remember">
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" class="login-btn">Masuk ke Sistem</button>
            </form>

            <div class="login-footer">
                🕊️ Dikelola dengan penuh tanggung jawab untuk kemuliaan Tuhan
            </div>
        </div>
    </div>

</x-guest-layout>
