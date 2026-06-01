<x-guest-layout>
    <div class="text-center">
        <a href="/" class="mx-auto flex w-fit flex-col items-center">
            <span class="actify-brand-mark h-16 w-16 text-emerald-700">
                <x-application-logo class="h-11 w-11" />
            </span>
            <span class="mt-3 text-3xl font-bold leading-tight text-emerald-700">Actify</span>
            <span class="text-sm font-medium text-slate-500">Daily Activity Tracker</span>
        </a>

        <h1 class="mt-7 text-xl font-bold text-slate-950">Register</h1>
        <p class="mt-1 text-sm text-slate-500">Buat akun mahasiswa untuk mulai tracking aktivitas.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="name" class="actify-label">Nama</label>
            <input id="name" class="actify-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama lengkap">
            @if ($errors->get('name'))
                <div class="actify-error">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div>
            <label for="email" class="actify-label">Email</label>
            <input id="email" class="actify-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Email aktif">
            @if ($errors->get('email'))
                <div class="actify-error">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div>
            <label for="password" class="actify-label">Password</label>
            <input id="password" class="actify-input" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
            @if ($errors->get('password'))
                <div class="actify-error">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div>
            <label for="password_confirmation" class="actify-label">Konfirmasi Password</label>
            <input id="password_confirmation" class="actify-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
            @if ($errors->get('password_confirmation'))
                <div class="actify-error">{{ $errors->first('password_confirmation') }}</div>
            @endif
        </div>

        <button type="submit" class="actify-btn actify-btn-primary w-full">
            Register
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-emerald-700 hover:text-emerald-800">Login</a>
    </p>
</x-guest-layout>
