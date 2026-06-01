@php
    $dashboardActive = request()->routeIs('dashboard');
    $createActive = request()->routeIs('activities.create');
    $listActive = request()->routeIs('activities.index') || request()->routeIs('activities.edit');
    $recapActive = request()->routeIs('activities.recap');
    $profileActive = request()->routeIs('profile.edit');
@endphp

<nav x-data="{ open: false }">
    <aside class="actify-sidebar">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="actify-brand-mark h-11 w-11 border-emerald-400/30 bg-emerald-500/10 text-emerald-300">
                <x-application-logo class="h-8 w-8" />
            </span>
            <span>
                <span class="block text-lg font-bold leading-5 text-white">Activy</span>
                <span class="text-xs font-medium text-slate-300">Daily Activity Tracker</span>
            </span>
        </a>

        <div class="mt-8 space-y-2">
            <a href="{{ route('dashboard') }}" class="{{ $dashboardActive ? 'actify-nav-link actify-nav-link-active' : 'actify-nav-link' }}">
                <svg class="actify-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 11.5 12 4l9 7.5"/>
                    <path d="M5 10.5V20h5v-5h4v5h5v-9.5"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('activities.create') }}" class="{{ $createActive ? 'actify-nav-link actify-nav-link-active' : 'actify-nav-link' }}">
                <svg class="actify-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 8v8M8 12h8"/>
                </svg>
                Tambah Aktivitas
            </a>

            <a href="{{ route('activities.index') }}" class="{{ $listActive ? 'actify-nav-link actify-nav-link-active' : 'actify-nav-link' }}">
                <svg class="actify-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M8 6h13M8 12h13M8 18h13"/>
                    <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                </svg>
                Data Tracking
            </a>

            <a href="{{ route('activities.recap') }}" class="{{ $recapActive ? 'actify-nav-link actify-nav-link-active' : 'actify-nav-link' }}">
                <svg class="actify-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19V5"/>
                    <path d="M8 19v-6M13 19V9M18 19V7"/>
                    <path d="M3 19h18"/>
                </svg>
                Rekapitulasi
            </a>

            <a href="{{ route('profile.edit') }}" class="{{ $profileActive ? 'actify-nav-link actify-nav-link-active' : 'actify-nav-link' }}">
                <svg class="actify-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 21a8 8 0 0 1 16 0"/>
                </svg>
                Profil
            </a>
        </div>

        <div class="mt-auto border-t border-white/10 pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="actify-nav-link w-full">
                    <svg class="actify-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10 17 15 12 10 7"/>
                        <path d="M15 12H3"/>
                        <path d="M21 4v16h-7"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="actify-mobile-bar">
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <span class="actify-brand-mark h-10 w-10 border-emerald-400/30 bg-emerald-500/10 text-emerald-300">
                    <x-application-logo class="h-7 w-7" />
                </span>
                <span>
                    <span class="block text-base font-bold leading-5 text-white">Activy</span>
                    <span class="text-xs text-slate-300">Daily Activity Tracker</span>
                </span>
            </a>

            <button type="button" class="rounded-md p-2 text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2 focus:ring-offset-slate-900" @click="open = ! open" aria-label="Buka menu">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path x-show="!open" d="M4 7h16M4 12h16M4 17h16"/>
                    <path x-show="open" d="M6 6l12 12M18 6 6 18"/>
                </svg>
            </button>
        </div>

        <div x-cloak x-show="open" x-transition class="mt-4 space-y-2 border-t border-white/10 pt-4">
            <a href="{{ route('dashboard') }}" class="{{ $dashboardActive ? 'actify-nav-link actify-nav-link-active' : 'actify-nav-link' }}">Dashboard</a>
            <a href="{{ route('activities.create') }}" class="{{ $createActive ? 'actify-nav-link actify-nav-link-active' : 'actify-nav-link' }}">Tambah Aktivitas</a>
            <a href="{{ route('activities.index') }}" class="{{ $listActive ? 'actify-nav-link actify-nav-link-active' : 'actify-nav-link' }}">Data Tracking</a>
            <a href="{{ route('activities.recap') }}" class="{{ $recapActive ? 'actify-nav-link actify-nav-link-active' : 'actify-nav-link' }}">Rekapitulasi</a>
            <a href="{{ route('profile.edit') }}" class="{{ $profileActive ? 'actify-nav-link actify-nav-link-active' : 'actify-nav-link' }}">Profil</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="actify-nav-link w-full">Keluar</button>
            </form>
        </div>
    </div>
</nav>
