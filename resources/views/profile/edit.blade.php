<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-slate-950">Profil</h2>
        <p class="mt-1 text-sm text-slate-500">Kelola informasi akun mahasiswa.</p>
    </x-slot>

    <div class="actify-page space-y-5">
        <section class="actify-panel p-5 sm:p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 items-center justify-center rounded-lg bg-emerald-100 text-xl font-bold text-emerald-700">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                    <div>
                        <h1 class="text-xl font-bold text-slate-950">{{ $user->name }}</h1>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="actify-chip border-emerald-200 bg-emerald-50 text-emerald-700">Mahasiswa</span>
            </div>
        </section>

        <div class="grid gap-5 lg:grid-cols-2">
            <section class="actify-panel p-5 sm:p-6">
                @include('profile.partials.update-profile-information-form')
            </section>

            <section class="actify-panel p-5 sm:p-6">
                @include('profile.partials.update-password-form')
            </section>
        </div>

        <section class="actify-panel p-5 sm:p-6">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
</x-app-layout>
