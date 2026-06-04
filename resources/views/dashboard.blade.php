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
                        Pantau rencana dan aktivitas harianmu dalam satu tempat untuk melihat progres dan tingkat kepatuhan terhadap target yang telah dibuat.
                    </p>
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
                        <p class="text-sm font-medium text-slate-500">Aktivitas hari ini</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $todayActivities->count() }}</p>
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
                        <p class="text-sm font-medium text-slate-500">Rencana hari ini</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $todayPlans->count() }}</p>
                        <p class="text-xs text-slate-500">rencana</p>
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
                        <p class="text-sm font-medium text-slate-500">Rencana terpenuhi</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $completedPlans }}</p>
                        <p class="text-xs text-slate-500">rencana</p>
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
                        <p class="text-sm font-medium text-slate-500">Tingkat Kepatuhan</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $complianceRate }}%</p>
                        <p class="text-xs text-slate-500">%</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Tracking Section -->
        <section style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px;">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-950">Rencana hari ini</h3>
                    </div>

                    <a href="{{ route('plans.index') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                        Lihat semua →
                    </a>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Nama Rencana</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($todayPlans as $plan)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $plan->nama_rencana }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $plan->kategori }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $plan->jam_mulai }} - {{ $plan->jam_selesai }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-slate-500">Tidak ada rencana untuk hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-950">Aktivitas hari ini</h3>
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
                                <th class="px-4 py-3">Durasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($todayActivities as $activity)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $activity->nama_aktivitas }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $activity->kategori }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $activity->durasi }} jam</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-slate-500">Belum ada aktivitas yang dicatat untuk hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section
            class="mt-6"
            style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h3 class="text-lg font-bold text-slate-950">
                    Kalender
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->translatedFormat('F Y') }}
                </p>

                <div class="mt-4">

                    <div class="grid grid-cols-7 gap-2 text-center text-xs font-semibold text-slate-500 mb-2">
                        <div>Min</div>
                        <div>Sen</div>
                        <div>Sel</div>
                        <div>Rab</div>
                        <div>Kam</div>
                        <div>Jum</div>
                        <div>Sab</div>
                    </div>

                    <div class="grid grid-cols-7 gap-2">

                        @for($i = 0; $i < $firstDayOfWeek; $i++)
                            <div></div>
                        @endfor

                        @for($day = 1; $day <= $daysInMonth; $day++)

                            @php
                                $date = sprintf(
                                    '%04d-%02d-%02d',
                                    $currentYear,
                                    $currentMonth,
                                    $day
                                );
                            @endphp

                            <a
                                href="{{ route('dashboard', ['date' => $date]) }}"
                                class="rounded-lg border px-2 py-2 text-center text-sm hover:bg-emerald-50
                                {{ $selectedDate == $date
                                    ? 'border-emerald-500 bg-emerald-100 font-bold'
                                    : 'border-slate-200'
                                }}">

                                {{ $day }}

                            </a>

                        @endfor

                    </div>

                </div>

            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h3 class="text-lg font-bold text-slate-950">
                    Detail Tanggal
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
                </p>

                <h4 class="mt-6 font-semibold">
                    Rencana
                </h4>

                <ul class="mt-2 space-y-2">

                    @forelse($datePlans as $plan)

                        <li>
                            • {{ $plan->nama_rencana }}

                            <span class="text-slate-500">
                                ({{ $plan->jam_mulai }} - {{ $plan->jam_selesai }})
                            </span>
                        </li>

                    @empty

                        <li class="text-slate-500">
                            Tidak ada rencana
                        </li>

                    @endforelse

                </ul>

                <h4 class="mt-6 font-semibold">
                    Aktivitas
                </h4>

                <ul class="mt-2 space-y-2">

                    @forelse($dateActivities as $activity)

                    <li>
                        • {{ $activity->nama_aktivitas }}

                        <span class="text-slate-500">
                            ({{ $activity->durasi }} jam)
                        </span>
                    </li>

                    @empty

                        <li class="text-slate-500">
                            Tidak ada aktivitas
                        </li>

                    @endforelse

                </ul>

            </div>

        </section>
    </div>
</x-app-layout>