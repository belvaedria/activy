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
            <!-- Rencana Hari Ini -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-950">Rencana hari ini</h3>

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
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $plan->nama_rencana }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            {{ $plan->kategori }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $plan->jam_mulai }} - {{ $plan->jam_selesai }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                                        Tidak ada rencana untuk hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Aktivitas Hari Ini -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-950">Aktivitas hari ini</h3>

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
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $activity->nama_aktivitas }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                            {{ $activity->kategori }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $activity->durasi }} jam
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                                        Belum ada aktivitas yang dicatat untuk hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Calendar, Detail, Weekly Plan Section -->
        <section class="mt-6" style="display: grid; grid-template-columns: 1.3fr 0.8fr 0.8fr; gap: 24px; align-items: start;">
            <!-- Kalender -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-start justify-between">
                    <div>
                        <h3 class="flex items-center gap-2 text-lg font-bold text-slate-950">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700">
                                📅
                            </span>
                            Kalender
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->locale('id')->translatedFormat('F Y') }}
                        </p>
                    </div>

                    <div class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        {{ \Carbon\Carbon::parse($selectedDate)->locale('id')->translatedFormat('d M Y') }}
                    </div>
                </div>


@php
    $currentCalendar = \Carbon\Carbon::create($currentYear, $currentMonth, 1);

    $prevMonth = $currentCalendar->copy()->subMonth()->format('Y-m-d');
    $nextMonth = $currentCalendar->copy()->addMonth()->format('Y-m-d');
@endphp

<div class="mb-4 flex items-center justify-center gap-5">
    <a href="{{ route('dashboard', ['date' => $prevMonth]) }}"
       class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-emerald-50 hover:text-emerald-700">
        ‹
    </a>

    <p class="text-sm font-bold text-slate-900">
        {{ $currentCalendar->locale('id')->translatedFormat('F Y') }}
    </p>

    <a href="{{ route('dashboard', ['date' => $nextMonth]) }}"
       class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-emerald-50 hover:text-emerald-700">
        ›
    </a>
</div>

                <!-- Header Hari -->
                <div style="display: grid; grid-template-columns: repeat(7, 1fr);"
                     class="overflow-hidden rounded-t-xl border border-slate-200 border-b-0 bg-slate-50 text-center text-xs font-semibold text-slate-500">
                    <div class="py-3">Min</div>
                    <div class="py-3">Sen</div>
                    <div class="py-3">Sel</div>
                    <div class="py-3">Rab</div>
                    <div class="py-3">Kam</div>
                    <div class="py-3">Jum</div>
                    <div class="py-3">Sab</div>
                </div>

                <!-- Isi Kalender -->
                <div style="display: grid; grid-template-columns: repeat(7, 1fr);"
                     class="overflow-hidden rounded-b-xl border border-slate-200 bg-white">
                    @for($i = 0; $i < $firstDayOfWeek; $i++)
                        <div style="min-height: 64px;" class="border border-slate-100 bg-slate-50/60 p-2 text-center text-sm text-slate-300"></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                            $isSelected = $selectedDate == $date;
                            $isToday = $date == now('Asia/Jakarta')->format('Y-m-d');
                        @endphp

                        <a href="{{ route('dashboard', ['date' => $date]) }}"
                           style="min-height: 64px;"
                           class="relative flex flex-col items-center justify-center border border-slate-100 p-2 text-sm transition
                           {{ $isSelected
                                ? 'bg-emerald-100 text-emerald-800 ring-2 ring-inset ring-emerald-500 font-bold'
                                : 'bg-white text-slate-700 hover:bg-emerald-50'
                           }}">
                            <span>{{ $day }}</span>

                            <div class="mt-2 flex items-center justify-center gap-1">
                                @if($isToday)
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                @endif

                                @if($isSelected)
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                @endif
                            </div>
                        </a>
                    @endfor
                </div>

                <div class="mt-5 flex flex-wrap gap-5 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        Hari ini
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                        Tanggal dipilih
                    </div>
                </div>
            </div>

            <!-- Detail Tanggal -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h3 class="flex items-center gap-2 text-lg font-bold text-slate-950">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                            📌
                        </span>
                        Detail Tanggal
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ \Carbon\Carbon::parse($selectedDate)->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>

                <!-- Rencana -->
                <div class="mb-6">
                    <h4 class="mb-3 text-sm font-bold text-slate-900">Rencana</h4>

                    <div class="space-y-3">
                        @forelse($datePlans as $plan)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $plan->nama_rencana }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $plan->jam_mulai }} - {{ $plan->jam_selesai }}
                                        </p>
                                    </div>

                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        {{ $plan->kategori }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                                Tidak ada rencana pada tanggal ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Aktivitas -->
                <div>
                    <h4 class="mb-3 text-sm font-bold text-slate-900">Aktivitas</h4>

                    <div class="space-y-3">
                        @forelse($dateActivities as $activity)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $activity->nama_aktivitas }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Durasi {{ $activity->durasi }} jam
                                        </p>
                                    </div>

                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                        {{ $activity->kategori }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                                Tidak ada aktivitas pada tanggal ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
<!-- Rencana Minggu Ini -->
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="mb-5">
        <h3 class="flex items-center gap-2 text-lg font-bold text-slate-950">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-600">
                🗓️
            </span>
            Rencana Minggu Ini
        </h3>

        <p class="mt-1 text-sm text-slate-500">
            Ringkasan rencana mingguan.
        </p>
    </div>

    @php
        $selectedDayName = \Carbon\Carbon::parse($selectedDate)
            ->locale('id')
            ->translatedFormat('l');

        $weekDays = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ];
    @endphp

    <div class="space-y-3 text-sm">
        @foreach($weekDays as $dayName)
            @php
                $isSelectedDay = $selectedDayName === $dayName;

                /*
                    Sementara ambil data dari tanggal yang sedang dipilih.
                    Jadi kalau tanggal 08 Juni 2026 ada 1 rencana,
                    maka Senin akan tampil 1 rencana.
                */
                $planCount = $isSelectedDay ? $datePlans->count() : 0;
            @endphp

            <div class="flex items-center justify-between rounded-xl border px-4 py-3
                {{ $isSelectedDay
                    ? 'border-emerald-200 bg-emerald-50'
                    : 'border-slate-200 bg-white'
                }}">

                <span class="{{ $isSelectedDay ? 'font-semibold text-emerald-800' : 'text-slate-600' }}">
                    {{ $dayName }}
                </span>

                <span class="font-semibold text-emerald-700">
                    {{ $planCount }} rencana
                </span>
            </div>
        @endforeach
    </div>

    <a href="{{ route('plans.index', ['tanggal' => $selectedDate]) }}"
       class="mt-5 block rounded-xl border border-emerald-200 px-4 py-3 text-center text-sm font-semibold text-emerald-700 hover:bg-emerald-50">
        Lihat semua rencana →
    </a>
</div>
        </section>
    </div>
</x-app-layout>