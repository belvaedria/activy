<section>
    <header>
        <h2 class="text-lg font-bold text-slate-950">Informasi Profil</h2>
        <p class="mt-1 text-sm text-slate-500">Perbarui nama dan email akun.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-5 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="actify-label">Nama</label>
            <input id="name" name="name" type="text" class="actify-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @if ($errors->get('name'))
                <div class="actify-error">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div>
            <label for="email" class="actify-label">Email</label>
            <input id="email" name="email" type="email" class="actify-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @if ($errors->get('email'))
                <div class="actify-error">{{ $errors->first('email') }}</div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
                    Email belum terverifikasi.
                    <button form="send-verification" class="font-semibold underline">
                        Kirim ulang verifikasi.
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-semibold text-emerald-700">Link verifikasi baru sudah dikirim.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="actify-btn actify-btn-primary">Simpan Profil</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-semibold text-emerald-700">
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>
