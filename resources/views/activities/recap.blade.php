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
                <div style="display: grid; grid-template-columns: 1.6fr 1fr 1fr 0.9fr 0.75fr; gap: 16px; align-items: end;">

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
                        <label class="mb-2 block text-sm font-semibold text-slate-500">
                            Kategori
                        </label>

                        <select
                            name="category"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Kategori</option>
                            <option value="Produktif">Produktif</option>
                            <option value="Kuliah">Kuliah</option>
                            <option value="Organisasi">Organisasi</option>
                            <option value="Personal">Personal</option>
                            <option value="Sehat">Sehat</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-500">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Status</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Belum Selesai">Belum Selesai</option>
                            <option value="Terlambat">Terlambat</option>
                            <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-500">
                            Periode
                        </label>

                        <div style="display: grid; grid-template-columns: 1fr 1fr;"
                             class="rounded-xl border border-slate-200 bg-slate-50 p-1">
                            <label class="cursor-pointer rounded-lg px-3 py-2 text-center text-sm font-semibold text-slate-600">
                                <input type="radio" name="period" value="harian" class="hidden">
                                Harian
                            </label>

                            <label class="cursor-pointer rounded-lg bg-emerald-50 px-3 py-2 text-center text-sm font-semibold text-emerald-700">
                                <input type="radio" name="period" value="mingguan" class="hidden" checked>
                                Mingguan
                            </label>
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
        <section style="display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 16px;">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                        📅
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Total Rencana</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $totalPlans }}</p>
                        <p class="text-xs text-slate-500">aktivitas</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        ✅
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Aktivitas Selesai</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $donePlans }}</p>
                        <p class="text-xs text-slate-500">aktivitas</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-yellow-50 text-yellow-700">
                        🕘
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Belum Selesai</p>
                        <p class="text-2xl font-bold text-slate-950">{{ max($unfinishedCount - $lateCount, 0) }}</p>
                        <p class="text-xs text-slate-500">aktivitas</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-700">
                        ⏰
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Terlambat</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $lateCount }}</p>
                        <p class="text-xs text-slate-500">aktivitas</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                        🕒
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Total Durasi Aktual</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $totalActualDuration }} jam</p>
                        <p class="text-xs text-slate-500">total waktu</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        📈
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Produktivitas</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $complianceRate }}%</p>
                        <p class="text-xs text-slate-500">dari target rencana</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Charts Row -->
        <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-950">
                    Perbandingan Rencana vs Aktual
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Perbandingan jumlah rencana dan realisasi aktivitas.
                </p>

                <div class="mt-4 h-80">
                    <canvas id="planChart"></canvas>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-950">
                    Distribusi Status Aktivitas
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Proporsi aktivitas berdasarkan status penyelesaian.
                </p>

                <div class="mt-4" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="h-72">
                        <canvas id="statusChart"></canvas>
                    </div>

                    <div class="flex flex-col justify-center space-y-4">
                        @php
                            $statusTotal = array_sum($statusDistribution);
                        @endphp

                        @foreach($statusDistribution as $statusName => $statusCount)
                            @php
                                $percent = $statusTotal > 0 ? round(($statusCount / $statusTotal) * 100) : 0;

                                $dotColor = match($statusName) {
                                    'Selesai' => 'bg-emerald-500',
                                    'Belum Selesai' => 'bg-yellow-500',
                                    'Terlambat' => 'bg-red-500',
                                    default => 'bg-blue-500',
                                };
                            @endphp

                            <div class="flex items-center justify-between gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="h-3 w-3 rounded-full {{ $dotColor }}"></span>
                                    <span class="font-medium text-slate-600">{{ $statusName }}</span>
                                </div>

                                <div class="text-right text-slate-600">
                                    <span class="font-semibold">{{ $statusCount }}</span>
                                    <span class="ml-2">({{ $percent }}%)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Category and Attention -->
        <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-950">
                    Produktivitas per Kategori
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Persentase penyelesaian aktivitas per kategori.
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
                    Aktivitas Terlambat / Perlu Perhatian
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Aktivitas yang melewati target atau belum diselesaikan.
                </p>

                <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Nama Aktivitas</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Target Selesai</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @forelse($unfulfilledPlans as $plan)
                                @php
                                    $status = $plan->status ?? 'Belum Selesai';

                                    $statusClass = match($status) {
                                        'Terlambat' => 'bg-red-50 text-red-700',
                                        'Selesai' => 'bg-emerald-50 text-emerald-700',
                                        'Sedang Dikerjakan' => 'bg-blue-50 text-blue-700',
                                        default => 'bg-yellow-50 text-yellow-700',
                                    };
                                @endphp

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
                                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                            {{ $status }}
                                        </span>
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
                Ringkasan Mingguan
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Insight utama dari rencana dan realisasi aktivitas minggu ini.
            </p>

            <div class="mt-5" style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 20px;">
                <div class="flex items-center gap-4 border-r border-slate-200 pr-5">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        ☆
                    </span>
                    <div>
                        <p class="text-sm text-slate-500">Hari paling produktif</p>
                        <p class="text-xl font-bold text-slate-950">-</p>
                        <p class="text-xs text-slate-500">{{ $totalActualDuration }} jam aktual</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 border-r border-slate-200 pr-5">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        ◷
                    </span>
                    <div>
                        <p class="text-sm text-slate-500">Kategori dominan</p>
                        <p class="text-xl font-bold text-slate-950">{{ $topCategory }}</p>
                        <p class="text-xs text-slate-500">kategori paling banyak</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 border-r border-slate-200 pr-5">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        ↗
                    </span>
                    <div>
                        <p class="text-xl font-bold text-slate-950">{{ $donePlans }} aktivitas</p>
                        <p class="text-xs text-slate-500">berhasil direalisasikan</p>
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
        const planData = @json($planComparison);
        const statusData = @json($statusDistribution);

        new Chart(document.getElementById('planChart'), {
            type: 'bar',
            data: {
                labels: ['Rencana', 'Terlaksana'],
                datasets: [{
                    label: 'Jumlah',
                    data: [
                        planData.rencana ?? 0,
                        planData.terlaksana ?? 0
                    ],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.25)',
                        'rgba(5, 150, 105, 0.9)'
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                        'rgba(59, 130, 246, 0.85)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</x-app-layout>