<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-slate-950">Dashboard</h2>
        <p class="mt-1 text-sm text-slate-500">Ringkasan aktivitas harian yang sudah dicatat.</p>
    </x-slot>

    <div class="actify-page">
        @if(session('success'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <!-- Welcome Section -->
        <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-semibold text-emerald-700">
                        Selamat datang, {{ Auth::user()->name }} 👋
                    </p>
                    <h1 class="mt-2 text-2xl font-bold leading-tight text-slate-950 sm:text-3xl">
                        Pantau aktivitas harianmu dengan lebih mudah.
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Dashboard ini menampilkan ringkasan aktivitas, total durasi, aktivitas terbaru, dan distribusi kategori.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('activities.create') }}" class="actify-btn actify-btn-primary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Tambah Aktivitas
                    </a>

                    <a href="{{ route('activities.index') }}" class="actify-btn actify-btn-secondary">
                        Data Tracking
                    </a>
                </div>
            </div>
        </section>

        <!-- Statistic Cards -->
        <section class="mb-6" style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px;">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 6h13M8 12h13M8 18h13"/>
                            <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Aktivitas</p>
                        <p class="text-2xl font-bold text-slate-950">4</p>
                        <p class="text-xs text-slate-500">aktivitas</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7v5l3 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Durasi</p>
                        <p class="text-2xl font-bold text-slate-950">7.5 jam</p>
                        <p class="text-xs text-slate-500">total waktu</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 17l6-6 4 4 6-8"/>
                            <path d="M14 7h6v6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Rata-rata Durasi</p>
                        <p class="text-2xl font-bold text-slate-950">1.88 jam</p>
                        <p class="text-xs text-slate-500">per aktivitas</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3 6 6 .9-4.5 4.4 1.1 6.2L12 16.6 6.4 19.5l1.1-6.2L3 8.9 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-500">Kategori Terbanyak</p>
                        <p class="text-2xl font-bold text-slate-950">Produktif</p>
                        <p class="text-xs text-slate-500">kategori</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Tracking Section -->
        <section style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px;">
            <!-- Aktivitas Terbaru -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-950">Aktivitas Terbaru</h3>
                        <p class="mt-1 text-sm text-slate-500">Data contoh sementara untuk tampilan dashboard.</p>
                    </div>

                    <a href="{{ route('activities.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                        Lihat semua →
                    </a>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Nama Aktivitas</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Durasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-900">Belajar MongoDB</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Produktif</span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">01 Jun 2026</td>
                                <td class="px-4 py-3 text-slate-600">2 jam</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-900">Olahraga Ringan</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Sehat</span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">01 Jun 2026</td>
                                <td class="px-4 py-3 text-slate-600">1 jam</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-900">Mengerjakan UI Activy</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Produktif</span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">02 Jun 2026</td>
                                <td class="px-4 py-3 text-slate-600">2.5 jam</td>
                            </tr>

                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-900">Istirahat</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Personal</span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">02 Jun 2026</td>
                                <td class="px-4 py-3 text-slate-600">2 jam</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Distribusi Kategori -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-slate-950">Distribusi Kategori</h3>
                <p class="mt-1 text-sm text-slate-500">Ringkasan kategori dari aktivitas harian.</p>

                <div class="mt-6 space-y-5">
                    <div>
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="font-semibold text-emerald-700">Produktif</span>
                            <span class="text-slate-600">50% / 4.5 jam</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-emerald-500" style="width: 50%;"></div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="font-semibold text-blue-700">Sehat</span>
                            <span class="text-slate-600">25% / 1 jam</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-blue-500" style="width: 25%;"></div>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="font-semibold text-amber-700">Personal</span>
                            <span class="text-slate-600">25% / 2 jam</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-amber-500" style="width: 25%;"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                    <p class="text-sm font-semibold text-emerald-800">
                        Aktivitas produktif menjadi kategori paling dominan minggu ini.
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>