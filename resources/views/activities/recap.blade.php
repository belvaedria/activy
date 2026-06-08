<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-slate-950">Rekapitulasi</h2>
        <p class="mt-1 text-sm text-slate-500">
            Analisis produktivitas berdasarkan rencana dan aktivitas dalam rentang waktu tertentu.
        </p>
    </x-slot>

    <div class="actify-page space-y-5">
        <section class="actify-panel p-5">
            <form method="GET" action="{{ route('activities.recap') }}">

                <div class="grid gap-4 md:grid-cols-3">

                    <div>
                        <label class="block text-sm font-medium">
                            Tanggal Awal
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            value="{{ $startDate }}"
                            class="mt-1 w-full rounded-lg border border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">
                            Tanggal Akhir
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            value="{{ $endDate }}"
                            class="mt-1 w-full rounded-lg border border-slate-300">
                    </div>

                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="actify-btn actify-btn-primary">

                            Tampilkan
                        </button>
                    </div>

                </div>

            </form>
        </section>
        <section class="grid gap-4 md:grid-cols-4">
            <div class="actify-stat flex items-center gap-4">
                <span class="actify-stat-icon bg-blue-50 text-blue-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-500">Rencana terpenuhi</p>
                    <p class="break-words text-xl font-bold text-slate-950 sm:text-2xl">{{ $completedPlans }} rencana</p>
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
                    <p class="text-xs font-semibold text-slate-500">Tingkat kepatuhan</p>
                    <p class="break-words text-xl font-bold text-slate-950 sm:text-2xl">{{ $complianceRate }}%</p>
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
                    <p class="text-xs font-semibold text-slate-500">Rata-rata durasi per aktivitas</p>
                    <p class="text-2xl font-bold text-slate-950">{{ $averageDuration }} jam</p>
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
                    <p class="text-xs font-semibold text-slate-500">Kategori terbanyak</p>
                    <p class="text-2xl font-bold text-slate-950">{{ $topCategory }}</p>
                </div>
            </div>
        </section>

        <section class="grid gap-5 lg:grid-cols-2">

            <div class="actify-panel p-5">
                <h3 class="text-lg font-bold text-slate-950">
                    Distribusi Kategori
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Komposisi aktivitas berdasarkan kategori.
                </p>

                <div class="mt-4 h-80">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <div class="actify-panel p-5">
                <h3 class="text-lg font-bold text-slate-950">
                    Rencana vs Terlaksana
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Perbandingan jumlah rencana dengan yang berhasil direalisasikan.
                </p>

                <div class="mt-4 h-80">
                    <canvas id="planChart"></canvas>
                </div>
            </div>

        </section>

        <section class="grid gap-5 lg:grid-cols-2">

            <div class="actify-panel p-5">

                <h3 class="text-lg font-bold text-slate-950">
                    Rencana Tidak Terpenuhi
                </h3>

                <ul class="mt-4 space-y-2 max-h-72 overflow-y-auto">

                    @forelse($unfulfilledPlans as $plan)

                        <li class="rounded-lg border border-slate-200 px-3 py-2">
                            <p class="font-medium">
                                {{ $plan->nama_rencana }}
                            </p>

                            <p class="text-xs text-slate-500">
                                {{ $plan->tanggal }}
                            </p>
                        </li>

                    @empty

                        <li class="text-slate-500">
                            Semua rencana berhasil terlaksana 🎉
                        </li>

                    @endforelse

                </ul>

            </div>

            <div class="actify-panel p-5">

                <h3 class="text-lg font-bold text-slate-950">
                    Aktivitas Tanpa Rencana
                </h3>

                <ul class="mt-4 space-y-2 max-h-72 overflow-y-auto">

                    @forelse($unplannedActivities as $activity)

                        <li class="rounded-lg border border-slate-200 px-3 py-2">
                            <p class="font-medium">
                                {{ $activity->nama_aktivitas }}
                            </p>

                            <p class="text-xs text-slate-500">
                                {{ $activity->tanggal }}
                            </p>
                        </li>

                    @empty

                        <li class="text-slate-500">
                            Tidak ada aktivitas tanpa rencana
                        </li>

                    @endforelse

                </ul>

            </div>

        </section>
    </div>

    @push('scripts')
    <script>

    const categoryData = @json($categoryDistribution);

    new Chart(
        document.getElementById('categoryChart'),
        {
            type: 'pie',
            data: {
                labels: Object.keys(categoryData),
                datasets: [{
                    data: Object.values(categoryData)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        }
    );

    const planData = @json($planComparison);

    new Chart(
        document.getElementById('planChart'),
        {
            type: 'bar',
            data: {
                labels: [
                    'Rencana',
                    'Terlaksana'
                ],
                datasets: [{
                    label: 'Jumlah'
                    data: [
                        planData.rencana,
                        planData.terlaksana
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        }
    );

    </script>
    @endpush
</x-app-layout>
