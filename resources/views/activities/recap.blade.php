@php
    $totalActivities = $activities->count();
    $totalDuration = (float) $activities->sum(fn ($activity) => (float) $activity->durasi);
    $averageDuration = $totalActivities > 0 ? $totalDuration / $totalActivities : 0;
    $longActivities = $activities->filter(fn ($activity) => (float) $activity->durasi > 1);

    $categoryMeta = collect([
        'Produktif' => ['chip' => 'actify-chip-productif', 'bar' => 'bg-emerald-600'],
        'Sehat' => ['chip' => 'actify-chip-sehat', 'bar' => 'bg-blue-600'],
        'Personal' => ['chip' => 'actify-chip-personal', 'bar' => 'bg-violet-600'],
    ]);

    $categoryStats = $categoryMeta->map(function ($meta, $category) use ($activities, $totalDuration) {
        $items = $activities->filter(fn ($activity) => strtolower((string) $activity->kategori) === strtolower($category));
        $duration = (float) $items->sum(fn ($activity) => (float) $activity->durasi);

        return [
            'count' => $items->count(),
            'duration' => $duration,
            'percent' => $totalDuration > 0 ? round(($duration / $totalDuration) * 100) : 0,
            'chip' => $meta['chip'],
            'bar' => $meta['bar'],
        ];
    });
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-slate-950">Rekapitulasi</h2>
        <p class="mt-1 text-sm text-slate-500">Ringkasan durasi dan distribusi aktivitas.</p>
    </x-slot>

    <div class="actify-page space-y-5">
        <section class="grid gap-4 md:grid-cols-3">
            <div class="actify-stat flex items-center gap-4">
                <span class="actify-stat-icon bg-blue-50 text-blue-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500">Total Durasi</p>
                    <p class="break-words text-xl font-bold text-slate-950 sm:text-2xl">{{ number_format($totalDuration, 1) }} jam</p>
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
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500">Rata-rata Durasi</p>
                    <p class="break-words text-xl font-bold text-slate-950 sm:text-2xl">{{ number_format($averageDuration, 2) }} jam</p>
                    <p class="text-xs text-slate-500">per aktivitas</p>
                </div>
            </div>

            <div class="actify-stat flex items-center gap-4">
                <span class="actify-stat-icon bg-emerald-50 text-emerald-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 6v6l4 2"/>
                        <circle cx="12" cy="12" r="9"/>
                    </svg>
                </span>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Aktivitas &gt; 1 jam</p>
                    <p class="text-2xl font-bold text-slate-950">{{ $longActivities->count() }}</p>
                    <p class="text-xs text-slate-500">aktivitas</p>
                </div>
            </div>
        </section>

        <section class="grid gap-5 lg:grid-cols-[0.85fr_1.15fr]">
            <div class="actify-panel p-5">
                <div class="mb-5">
                    <h1 class="text-xl font-bold text-slate-950">Distribusi Durasi per Kategori</h1>
                    <p class="mt-1 text-sm text-slate-500">Progress bar sederhana tanpa library chart tambahan.</p>
                </div>

                <div class="space-y-5">
                    @foreach($categoryStats as $category => $stat)
                        <div>
                            <div class="mb-2 flex flex-col gap-2 text-sm sm:flex-row sm:items-center sm:justify-between">
                                <span class="actify-chip {{ $stat['chip'] }}">{{ $category }}</span>
                                <span class="break-words font-semibold text-slate-600 sm:text-right">{{ $stat['percent'] }}% ({{ number_format($stat['duration'], 1) }} jam)</span>
                            </div>
                            <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $stat['bar'] }}" style="width: {{ $stat['percent'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="actify-panel p-5">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-slate-950">Hasil Filter (Durasi &gt; 1 jam)</h1>
                        <p class="mt-1 text-sm text-slate-500">Aktivitas dengan durasi lebih panjang.</p>
                    </div>
                    <span class="actify-chip border-slate-200 bg-slate-50 text-slate-700">{{ $longActivities->count() }} aktivitas</span>
                </div>

                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="actify-table">
                        <thead>
                            <tr>
                                <th>Nama Aktivitas</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($longActivities as $act)
                                @php
                                    $chipClass = match ((string) $act->kategori) {
                                        'Produktif' => 'actify-chip-productif',
                                        'Sehat' => 'actify-chip-sehat',
                                        'Personal' => 'actify-chip-personal',
                                        default => 'border-slate-200 bg-slate-50 text-slate-700',
                                    };
                                @endphp
                                <tr>
                                    <td class="font-semibold text-slate-800">{{ $act->nama_aktivitas }}</td>
                                    <td><span class="actify-chip {{ $chipClass }}">{{ $act->kategori }}</span></td>
                                    <td>{{ \Illuminate\Support\Carbon::parse($act->tanggal)->format('d M Y') }}</td>
                                    <td>{{ number_format((float) $act->durasi, 1) }} jam</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">
                                        Belum ada aktivitas dengan durasi lebih dari 1 jam.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('activities.index') }}" class="actify-btn actify-btn-secondary">Kembali ke Data Tracking</a>
            <a href="{{ route('activities.create') }}" class="actify-btn actify-btn-primary">Tambah Aktivitas</a>
        </div>
    </div>
</x-app-layout>
