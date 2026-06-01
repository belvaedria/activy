<section>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-950">Hapus Akun</h2>
            <p class="mt-1 max-w-2xl text-sm text-slate-500">
                Jika akun dihapus, data akun tidak bisa dikembalikan. Gunakan hanya jika benar-benar diperlukan.
            </p>
        </div>

        <button
            type="button"
            class="actify-btn actify-btn-danger"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >
            Hapus Akun
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-950">Yakin ingin menghapus akun?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">
                Masukkan password untuk mengonfirmasi penghapusan akun.
            </p>

            <div class="mt-5">
                <label for="password" class="sr-only">Password</label>
                <input id="password" name="password" type="password" class="actify-input" placeholder="Password">
                @if ($errors->userDeletion->get('password'))
                    <div class="actify-error">{{ $errors->userDeletion->first('password') }}</div>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="actify-btn actify-btn-secondary" x-on:click="$dispatch('close')">
                    Batal
                </button>
                <button type="submit" class="actify-btn actify-btn-danger">
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
