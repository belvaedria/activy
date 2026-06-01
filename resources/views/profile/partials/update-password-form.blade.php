<section>
    <header>
        <h2 class="text-lg font-bold text-slate-950">Password</h2>
        <p class="mt-1 text-sm text-slate-500">Ganti password akun jika diperlukan.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="actify-label">Password Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="actify-input" autocomplete="current-password">
            @if ($errors->updatePassword->get('current_password'))
                <div class="actify-error">{{ $errors->updatePassword->first('current_password') }}</div>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="actify-label">Password Baru</label>
            <input id="update_password_password" name="password" type="password" class="actify-input" autocomplete="new-password">
            @if ($errors->updatePassword->get('password'))
                <div class="actify-error">{{ $errors->updatePassword->first('password') }}</div>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="actify-label">Konfirmasi Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="actify-input" autocomplete="new-password">
            @if ($errors->updatePassword->get('password_confirmation'))
                <div class="actify-error">{{ $errors->updatePassword->first('password_confirmation') }}</div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="actify-btn actify-btn-primary">Update Password</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-semibold text-emerald-700">
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>
