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

        @php
            $currentCalendar = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
            $prevMonth = $currentCalendar->copy()->subMonth()->format('Y-m-d');
            $nextMonth = $currentCalendar->copy()->addMonth()->format('Y-m-d');

            // Penting: ini biar titik warna muncul sesuai tanggal.
            $plansByDate = $monthPlans->groupBy(function ($plan) {
                return \Carbon\Carbon::parse($plan->tanggal)->format('Y-m-d');
            });

            $categoryBadgeClass = function ($category) {
                return match($category) {
                    'Kuliah' => 'bg-blue-50 text-blue-700',
                    'Organisasi' => 'bg-purple-50 text-purple-700',
                    'Personal' => 'bg-orange-50 text-orange-700',
                    'Sehat' => 'bg-green-50 text-green-700',
                    default => 'bg-emerald-50 text-emerald-700',
                };
            };

            $categoryDotClass = function ($category) {
                return match($category) {
                    'Kuliah' => 'bg-blue-500',
                    'Organisasi' => 'bg-purple-500',
                    'Personal' => 'bg-orange-500',
                    'Sehat' => 'bg-green-500',
                    default => 'bg-emerald-500',
                };
            };
        @endphp

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
                        ☰
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
                        🕘
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
                        ↗
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
                        ☆
                    </div>

                    <div>
                        <p class="text-sm font-medium text-slate-500">Tingkat Kepatuhan</p>
                        <p class="text-2xl font-bold text-slate-950">{{ $complianceRate }}%</p>
                        <p class="text-xs text-slate-500">%</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Calendar, Detail Section -->
        <section class="mt-6" style="display: grid; grid-template-columns: 1.4fr 0.9fr; gap: 24px; align-items: start;">
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
                    </div>

                    <div class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        {{ \Carbon\Carbon::parse($selectedDate)->locale('id')->translatedFormat('d M Y') }}
                    </div>
                </div>

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
                        <div style="min-height: 64px;" class="border border-slate-100 bg-slate-50/60 p-2"></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                            $isSelected = $selectedDate == $date;
                            $plansOnDate = $plansByDate->get($date, collect())->take(4);
                        @endphp

                        <a href="{{ route('dashboard', ['date' => $date]) }}"
                           style="min-height: 64px;"
                           class="relative flex flex-col items-center justify-center border border-slate-100 p-2 text-sm transition
                           {{ $isSelected
                                ? 'bg-emerald-100 text-emerald-800 ring-2 ring-inset ring-emerald-500 font-bold'
                                : 'bg-white text-slate-700 hover:bg-emerald-50'
                           }}">

                            <span>{{ $day }}</span>

                            <div class="mt-2 flex min-h-[8px] items-center justify-center gap-1">
                                @foreach($plansOnDate as $planDot)
                                    <span class="block h-2 w-2 rounded-full {{ $categoryDotClass($planDot->kategori) }}"></span>
                                @endforeach
                            </div>
                        </a>
                    @endfor
                </div>

                <!-- Legend -->
                <div class="mt-5 flex flex-wrap gap-5 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="block h-2 w-2 rounded-full bg-emerald-500"></span>
                        Produktif
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="block h-2 w-2 rounded-full bg-blue-500"></span>
                        Kuliah
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="block h-2 w-2 rounded-full bg-purple-500"></span>
                        Organisasi
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="block h-2 w-2 rounded-full bg-orange-500"></span>
                        Personal
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="block h-2 w-2 rounded-full bg-green-500"></span>
                        Sehat
                    </div>
                </div>
            </div>

            <!-- Checklist Hari Dipilih -->
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="mb-5">
                    <h3 class="text-lg font-bold text-slate-950">
                        Rencana Hari Ini
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ \Carbon\Carbon::parse($selectedDate)->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>

                <div class="space-y-3">

                    @forelse($datePlans as $plan)

                        @php
                            $isCompleted = in_array(
                                (string)$plan->_id,
                                array_map('strval', $completedPlanIds)
                            );
                        @endphp

                        <label
                            class="
                                flex items-center gap-3 rounded-xl border px-4 py-3 cursor-pointer
                                {{ $isCompleted
                                    ? 'border-slate-200 bg-slate-100 opacity-60'
                                    : 'border-slate-200 bg-slate-50 hover:bg-slate-100'
                                }}
                            "
                        >

                        <input
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-emerald-600"
                            {{ $isCompleted ? 'checked disabled' : '' }}

                            @unless($isCompleted)
                                onclick="openActivityModal(
                                    '{{ $plan->_id }}',
                                    '{{ $plan->nama_rencana }}',
                                    '{{ $plan->kategori }}',
                                    '{{ $plan->tanggal }}',
                                    '{{ $plan->jam_mulai }}',
                                    '{{ $plan->jam_selesai }}'
                                )"
                            @endunless
                        >

                            <div class="flex-1">

                                <p
                                    class="
                                        font-medium
                                        {{ $isCompleted
                                            ? 'text-slate-500 line-through'
                                            : 'text-slate-900'
                                        }}
                                    "
                                >
                                    {{ $plan->nama_rencana }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    {{ $plan->jam_mulai }} - {{ $plan->jam_selesai }}
                                </p>

                            </div>

                        </label>

                    @empty

                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                            Tidak ada rencana pada tanggal ini.
                        </div>

                    @endforelse

                </div>

            </div>
        </section>
    </div>

    <!-- Modal Aktivitas -->
    <div
        id="activityModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40"
    >
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">

            <h3
                id="modalPlanName"
                class="mb-5 text-lg font-bold text-slate-900"
            >
            </h3>

            <form
                action="{{ route('activities.store') }}"
                method="POST"
                id="activityForm"
            >
                @csrf

                <input type="hidden" name="plan_id" id="modalPlanId">
                <input type="hidden" name="nama_aktivitas" id="modalNama">
                <input type="hidden" name="kategori" id="modalKategori">
                <input type="hidden" name="tanggal" id="modalTanggal">
                <input type="hidden" name="durasi" id="modalDurasi">

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">
                        Jam Mulai
                    </label>

                    <input
                        type="time"
                        name="jam_mulai"
                        id="modalJamMulai"
                        class="actify-input"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">
                        Jam Selesai
                    </label>

                    <input
                        type="time"
                        name="jam_selesai"
                        id="modalJamSelesai"
                        class="actify-input"
                        required
                    >
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2">
                        Catatan
                    </label>

                    <textarea
                        name="deskripsi"
                        rows="3"
                        class="actify-input"
                    ></textarea>
                </div>

                <div class="flex justify-end gap-3">

                    <button
                        type="button"
                        onclick="closeActivityModal()"
                        class="actify-btn actify-btn-secondary"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        onclick="prepareActivityData()"
                        class="actify-btn actify-btn-primary"
                    >
                        Simpan
                    </button>

                </div>
            </form>

        </div>
    </div>

    <script>
        let planStartTime = '';
        let planEndTime = '';

        function openActivityModal(
            id,
            nama,
            kategori,
            tanggal,
            jamMulai,
            jamSelesai
        ) {
            document
                .getElementById('activityModal')
                .classList
                .remove('hidden');

            document
                .getElementById('activityModal')
                .classList
                .add('flex');

            document.getElementById('modalPlanName').innerText = nama;

            document.getElementById('modalPlanId').value = id;
            document.getElementById('modalNama').value = nama;
            document.getElementById('modalKategori').value = kategori;
            document.getElementById('modalTanggal').value = tanggal;

            planStartTime = jamMulai;
            planEndTime = jamSelesai;
        }

        function closeActivityModal() {

            document
                .getElementById('activityModal')
                .classList
                .add('hidden');

            document
                .getElementById('activityModal')
                .classList
                .remove('flex');
        }

        function prepareActivityData() {

            const start =
                document.getElementById('modalJamMulai').value;

            const end =
                document.getElementById('modalJamSelesai').value;

            const startMinutes =
                parseTime(start);

            const endMinutes =
                parseTime(end);

            const duration =
                (endMinutes - startMinutes) / 60;

            document
                .getElementById('modalDurasi')
                .value = duration.toFixed(1);
        }

        function parseTime(time) {

            const parts = time.split(':');

            return (
                parseInt(parts[0]) * 60 +
                parseInt(parts[1])
            );
        }

    </script>
</x-app-layout>