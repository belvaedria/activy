<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Activy</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <main class="flex min-h-screen items-center justify-center bg-[#f4faf7] px-4 py-10">
            <section class="w-full max-w-4xl rounded-lg border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
                    <div class="max-w-lg">
                        <a href="/" class="flex w-fit items-center gap-3">
                            <span class="actify-brand-mark h-14 w-14 text-emerald-700">
                                <x-application-logo class="h-10 w-10" />
                            </span>
                            <span>
                                <span class="block text-3xl font-bold leading-8 text-emerald-700">Activy</span>
                                <span class="text-sm font-medium text-slate-500">Daily Activity Tracker</span>
                            </span>
                        </a>

                        <h1 class="mt-8 text-3xl font-bold leading-tight text-slate-950 sm:text-4xl">
                            Catat aktivitas harian dengan tampilan sederhana.
                        </h1>
                        <p class="mt-4 text-base leading-7 text-slate-600">
                            Kelola aktivitas, kategori, durasi, dan rekap singkat dalam satu aplikasi web yang ringan.
                        </p>

                        <div class="mt-7 flex flex-wrap gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="actify-btn actify-btn-primary">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="actify-btn actify-btn-primary">Login</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="actify-btn actify-btn-secondary">Daftar</a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="actify-soft-panel w-full max-w-sm p-4">
                        <div class="rounded-lg border border-white bg-white p-4 shadow-sm">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-950">Aktivitas Hari Ini</span>
                                <span class="actify-chip actify-chip-productif">Produktif</span>
                            </div>
                            <div class="space-y-3">
                                <div class="rounded-md border border-slate-100 p-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-semibold text-slate-800">Belajar Basis Data</span>
                                        <span class="text-slate-500">2.5 jam</span>
                                    </div>
                                </div>
                                <div class="rounded-md border border-slate-100 p-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-semibold text-slate-800">Olahraga</span>
                                        <span class="text-slate-500">1 jam</span>
                                    </div>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100">
                                    <div class="h-2 w-2/3 rounded-full bg-emerald-600"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>
