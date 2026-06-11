<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-slate-950">Rekapitulasi</h2>
        <p class="mt-1 text-sm text-slate-500">
            Analisis produktivitas berdasarkan rencana dan realisasi aktivitas.
        </p>
    </x-slot>

    @php
        $startDate = $startDate ?? request('start_date') ?? now('Asia/Jakarta')->startOfMonth()->format('Y-m-d');
        $endDate = $endDate ?? request('end_date') ?? now('Asia/Jakarta')->format('Y-m-d');

        $completedPlans = $completedPlans ?? 0;
        $complianceRate = $complianceRate ?? 0;
        $averageDuration = $averageDuration ?? 0;
        $topCategory = $topCategory ?? '-';

        $unfulfilledPlans = $unfulfilledPlans ?? collect();
        $unplannedActivities = $unplannedActivities ?? collect();

        $categoryDistribution = $categoryDistribution ?? [];
        $planComparison = $planComparison ?? [
            'rencana' => 0,
            'terlaksana' => 0,
        ];

        $totalPlans = $planComparison['rencana'] ?? 0;
        $donePlans = $planComparison['terlaksana'] ?? $completedPlans;
        $unfinishedCount = $unfulfilledPlans->count();
        $lateCount = $unfulfilledPlans->where('status', 'Terlambat')->count();

        if ($lateCount === 0) {
            $lateCount = $unfulfilledPlans->filter(function ($plan) {
                return ($plan->status ?? '') === 'Terlambat';
            })->count();
        }

        $totalActualDuration = $averageDuration > 0
            ? round($averageDuration * max($donePlans, 1), 1)
            : 0;

        $statusDistribution = [
            'Selesai' => $donePlans,
            'Belum Selesai' => max($unfinishedCount - $lateCount, 0),
            'Terlambat' => $lateCount,
            'Sedang Dikerjakan' => 0,
        ];

        $categoryColors = [
            'Produktif' => 'bg-emerald-500',
            'Kuliah' => 'bg-blue-500',
            'Tugas' => 'bg-purple-500',
            'Organisasi' => 'bg-orange-500',
            'Personal' => 'bg-orange-500',
            'Sehat' => 'bg-green-500',
            'Istirahat' => 'bg-yellow-500',
        ];

        $categoryIconBg = [
            'Produktif' => 'bg-emerald-50 text-emerald-700',
            'Kuliah' => 'bg-blue-50 text-blue-700',
            'Tugas' => 'bg-purple-50 text-purple-700',
            'Organisasi' => 'bg-orange-50 text-orange-700',
            'Personal' => 'bg-orange-50 text-orange-700',
            'Sehat' => 'bg-green-50 text-green-700',
            'Istirahat' => 'bg-yellow-50 text-yellow-700',
        ];
    @endphp

    <div class="actify-page space-y-5">

        <!-- Filter -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('activities.recap') }}">
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: end;">

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-500">
                            Rentang Tanggal
                        </label>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                            <input
                                type="date"
                                name="start_date"
                                value="{{ $startDate }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 focus:border-emerald-500 focus:ring-emerald-500">

                            <input
                                type="date"
                                name="end_date"
                                value="{{ $endDate }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 focus:border-emerald-500 focus:ring-emerald-500">
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white hover:bg-emerald-700">
                            <span>▽</span>
                            Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <!-- Statistic Cards -->
        <section style="display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 16px;">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                        📅
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Total Rencana</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $totalPlans }}</p>
                        <p class="text-xs text-slate-500">rencana</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        ✅
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Rencana Terpenuhi</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $completedPlans }}</p>
                        <p class="text-xs text-slate-500">rencana</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-yellow-50 text-yellow-700">
                        🕘
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Tingkat Kepatuhan</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $complianceRate }}%</p>
                        <p class="text-xs text-slate-500">dari rencana</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-700">
                        ⏰
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Total Keterlambatan</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $totalLateMinutes }} menit</p>
                        <p class="text-xs text-slate-500">dari seluruh durasi rencana</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Charts Row -->
        <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-950">
                    Rencana vs Realisasi
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Perbandingan rencana dan realisasi aktivitas.
                </p>

                <div class="mt-4 h-80">
                    <canvas id="planChart"></canvas>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-950">
                    Tren Kepatuhan
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Perkembangan tingkat kepatuhan selama periode yang dipilih.
                </p>

                <div class="mt-4 h-80">
                    <canvas id="complianceChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Category and Attention -->
        <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-950">
                    Distribusi Waktu per Kategori
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Distribusi durasi aktual berdasarkan kategori aktivitas.
                </p>

                <div class="mt-5 space-y-4">
                    @forelse($categoryDistribution as $category => $count)
                        @php
                            $categoryTotal = array_sum($categoryDistribution);
                            $percentage = $categoryTotal > 0 ? round(($count / $categoryTotal) * 100) : 0;

                            $barColor = $categoryColors[$category] ?? 'bg-emerald-500';
                            $iconClass = $categoryIconBg[$category] ?? 'bg-emerald-50 text-emerald-700';
                        @endphp

                        <div style="display: grid; grid-template-columns: 160px 1fr 50px; gap: 16px; align-items: center;">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $iconClass }}">
                                    ●
                                </span>
                                <span class="text-sm font-semibold text-slate-700">{{ $category }}</span>
                            </div>

                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $percentage }}%;"></div>
                            </div>

                            <p class="text-right text-sm font-semibold text-slate-600">{{ $percentage }}%</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data kategori.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-950">
                    Perlu Perhatian
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Rencana yang belum direalisasikan atau memiliki keterlambatan tertinggi.
                </p>

                <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Nama Rencana</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Keterlambatan (menit)</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @forelse($attentionPlans as $plan)

                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $plan->nama_rencana }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $plan->kategori ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $plan->tanggal }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $plan->keterlambatan_menit ?? 0 }} menit
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-500">
                                        Tidak ada aktivitas yang perlu perhatian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Weekly Summary -->
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-bold text-slate-950">
                Insight
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Insight utama dari periode yang dipilih.
            </p>

            <div class="mt-5" style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 20px;">
                <div class="flex items-center gap-4 border-r border-slate-200 pr-5">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        ☆
                    </span>
                    <div>
                        <p class="text-sm text-slate-500">Hari Terbaik</p>
                        <p class="text-xl font-bold text-slate-950">{{ $bestDay }}</p>
                        <p class="text-xs text-slate-500">
                            kepatuhan tertinggi
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 border-r border-slate-200 pr-5">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        ◷
                    </span>
                    <div>
                        <p class="text-sm text-slate-500">Kategori dominan</p>
                        <p class="text-xl font-bold text-slate-950">{{ $topCategory }}</p>
                        <p class="text-xs text-slate-500">
                            alokasi waktu terbesar
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 border-r border-slate-200 pr-5">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        ↗
                    </span>
                    <div>
                        <p class="text-sm text-slate-500">
                            Hari Terburuk
                        </p>

                        <p class="text-xl font-bold text-slate-950">
                            {{ $worstDay }}
                        </p>

                        <p class="text-xs text-slate-500">
                            kepatuhan terendah
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        🛡️
                    </span>
                    <div>
                        <p class="text-sm text-slate-500">Tingkat kepatuhan rencana</p>
                        <p class="text-xl font-bold text-slate-950">{{ $complianceRate }}%</p>
                        <p class="text-xs text-slate-500">dari total rencana</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const planData = @json($planRealizationTrend);
        const complianceTrend = @json($adaptiveComplianceTrend);

        new Chart(document.getElementById('planChart'), {
            type: 'bar',
            data: {
                labels: planData.labels,
                datasets: [
                    {
                        label: 'Rencana',
                        data: planData.planned,
                        borderWidth: 1
                    },
                    {
                        label: 'Realisasi',
                        data: planData.realized,
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(
            document.getElementById('complianceChart'),
            {
                type: 'line',
                data: {
                    labels: complianceTrend.labels,
                    datasets: [{
                        label: 'Kepatuhan (%)',
                        data: complianceTrend.values,
                        borderWidth: 3,
                        tension: 0.3,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            }
        );
    </script>
</x-app-layout>