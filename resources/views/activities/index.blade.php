@php
    $totalActivities = $activities->count();
    $totalDuration = (float) $activities->sum(fn ($activity) => (float) $activity->durasi);
    $averageDuration = $totalActivities > 0 ? $totalDuration / $totalActivities : 0;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-slate-950">Data Tracking</h2>
        <p class="mt-1 text-sm text-slate-500">Kelola dan filter data aktivitas yang sudah tersimpan.</p>
    </x-slot>

    <div class="actify-page space-y-5" x-data="{ category: '', minDuration: '', search: '', matches(rowCategory, rowDuration, rowName) { return (!this.category || rowCategory === this.category) && (!this.minDuration || rowDuration > Number(this.minDuration)) && (!this.search || rowName.includes(this.search.toLowerCase())); } }">
        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-4 md:grid-cols-3">
            <div class="actify-stat flex items-center gap-4">
                <span class="actify-stat-icon bg-emerald-50 text-emerald-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 6h13M8 12h13M8 18h13"/>
                        <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                    </svg>
                </span>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Total Aktivitas</p>
                    <p class="text-2xl font-bold text-slate-950">{{ $totalActivities }}</p>
                    <p class="text-xs text-slate-500">aktivitas</p>
                </div>
            </div>

            <div class="actify-stat flex items-center gap-4">
                <span class="actify-stat-icon bg-blue-50 text-blue-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </span>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Total Durasi</p>
                    <p class="text-2xl font-bold text-slate-950">{{ number_format($totalDuration, 1) }} jam</p>
                    <p class="text-xs text-slate-500">total waktu</p>
                </div>
            </div>

            <div class="actify-stat flex items-center gap-4">
                <span class="actify-stat-icon bg-violet-50 text-violet-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m4 16 5-5 4 4 7-7"/>
                        <path d="M14 8h6v6"/>
                    </svg>
                </span>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Rata-rata Durasi</p>
                    <p class="text-2xl font-bold text-slate-950">{{ number_format($averageDuration, 2) }} jam</p>
                    <p class="text-xs text-slate-500">per aktivitas</p>
                </div>
            </div>
        </section>

        <section class="actify-panel p-4 sm:p-5">
            <div class="mb-4 flex flex-col gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-bold text-slate-950">Aktivitas Tersimpan</h1>
                    <p class="mt-1 text-sm text-slate-500">Gunakan filter ringan untuk melihat data tertentu.</p>
                </div>

                <a href="{{ route('activities.create') }}" class="actify-btn actify-btn-primary">
                    Tambah Aktivitas
                </a>
            </div>

            <form class="mb-4 grid gap-3 md:grid-cols-[1fr_180px_170px_auto]" @submit.prevent>
                <input type="search" x-model="search" class="actify-input" placeholder="Cari nama aktivitas">

                <select x-model="category" class="actify-input">
                    <option value="">Semua Kategori</option>
                    <option value="produktif">Produktif</option>
                    <option value="sehat">Sehat</option>
                    <option value="personal">Personal</option>
                </select>

                <select x-model="minDuration" class="actify-input">
                    <option value="">Semua Durasi</option>
                    <option value="1">Durasi > 1 jam</option>
                    <option value="2">Durasi > 2 jam</option>
                </select>

                <button type="button" class="actify-btn actify-btn-secondary" @click="category = ''; minDuration = ''; search = ''">
                    Reset
                </button>
            </form>

            <div class="overflow-x-auto rounded-lg border border-slate-200">
                <table class="actify-table">
                    <thead>
                        <tr>
                            <th>Nama Aktivitas</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Durasi (Jam)</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $act)
                            @php
                                $chipClass = match ((string) $act->kategori) {
                                    'Produktif' => 'actify-chip-productif',
                                    'Sehat' => 'actify-chip-sehat',
                                    'Personal' => 'actify-chip-personal',
                                    default => 'border-slate-200 bg-slate-50 text-slate-700',
                                };
                            @endphp
                            <tr x-show="matches(@js(strtolower((string) $act->kategori)), @js((float) $act->durasi), @js(strtolower((string) $act->nama_aktivitas)))">
                                <td class="font-semibold text-slate-800">{{ $act->nama_aktivitas }}</td>
                                <td>
                                    <span class="actify-chip {{ $chipClass }}">{{ $act->kategori }}</span>
                                </td>
                                <td>{{ \Illuminate\Support\Carbon::parse($act->tanggal)->format('d M Y') }}</td>
                                <td>{{ number_format((float) $act->durasi, 1) }} jam</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($act->jam_mulai)->format('H:i') }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($act->jam_selesai)->format('H:i') }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('activities.edit', $act->id) }}" class="actify-btn actify-btn-blue px-3 py-1.5">Edit</a>

                                        <form action="{{ route('activities.destroy', $act->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="actify-btn actify-btn-danger px-3 py-1.5">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                    Belum ada aktivitas. Tambahkan aktivitas pertama kamu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-sm font-semibold text-slate-600">
                Total {{ $totalActivities }} data
            </div>
        </section>

        <div class="actify-soft-panel p-4 text-sm text-slate-600">
            Butuh ringkasan kategori dan aktivitas berdurasi panjang? Buka halaman
            <a href="{{ route('activities.recap') }}" class="font-semibold text-emerald-700 hover:text-emerald-800">Rekapitulasi</a>.
        </div>
    </div>
</x-app-layout>
