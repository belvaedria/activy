<x-guest-layout>
    <div class="text-center">
        <a href="/" class="mx-auto flex w-fit flex-col items-center">
            <span class="actify-brand-mark h-16 w-16 text-emerald-700">
                <x-application-logo class="h-11 w-11" />
            </span>
            <span class="mt-3 text-3xl font-bold leading-tight text-emerald-700">Actify</span>
            <span class="text-sm font-medium text-slate-500">Daily Activity Tracker</span>
        </a>

        <h1 class="mt-7 text-xl font-bold text-slate-950">Login</h1>
        <p class="mt-1 text-sm text-slate-500">Masuk untuk mencatat aktivitas harianmu.</p>
    </div>

    <x-auth-session-status class="mt-6 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="email" class="actify-label">Email</label>
            <input id="email" class="actify-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Email Anda">
            @if ($errors->get('email'))
                <div class="actify-error">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div>
            <label for="password" class="actify-label">Password</label>
            <input id="password" class="actify-input" type="password" name="password" required autocomplete="current-password" placeholder="Password Anda">
            @if ($errors->get('password'))
                <div class="actify-error">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-700 shadow-sm focus:ring-emerald-500" name="remember">
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-emerald-700 hover:text-emerald-800" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit" class="actify-btn actify-btn-primary w-full">
            Masuk
        </button>
    </form>

    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-emerald-700 hover:text-emerald-800">Daftar di sini</a>
        </p>
    @endif
</x-guest-layout>
