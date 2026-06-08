<x-app-layout>
    <div class="actify-page">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-950">Rencana</h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola rencana aktivitas harian dan mingguan.
            </p>
        </div>

        @php
            $currentCalendar = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
            $prevMonth = $currentCalendar->copy()->subMonth()->format('Y-m-d');
            $nextMonth = $currentCalendar->copy()->addMonth()->format('Y-m-d');
        @endphp

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;">

            <!-- Kalender -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-start justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-slate-950">
                            Kalender
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $currentCalendar->locale('id')->translatedFormat('F Y') }}
                        </p>
                    </div>

                    <a href="{{ route('plans.index', ['tanggal' => now('Asia/Jakarta')->format('Y-m-d')]) }}"
                       class="rounded-xl border border-emerald-200 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-50">
                        Hari ini
                    </a>
                </div>

                <div class="mb-4 flex items-center justify-center gap-5">
                    <a href="{{ route('plans.index', ['tanggal' => $prevMonth]) }}"
                       class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-emerald-50 hover:text-emerald-700">
                        ‹
                    </a>

                    <p class="text-base font-bold text-slate-950">
                        {{ $currentCalendar->locale('id')->translatedFormat('F Y') }}
                    </p>

                    <a href="{{ route('plans.index', ['tanggal' => $nextMonth]) }}"
                       class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-emerald-50 hover:text-emerald-700">
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

                    @for ($i = 0; $i < $firstDayOfMonth; $i++)
                        <div style="min-height: 72px;" class="border border-slate-100 bg-slate-50/60"></div>
                    @endfor

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $date = sprintf('%04d-%02d-%02d', $currentYear, $currentMonth, $day);
                            $isSelected = $selectedDate == $date;
                            $isToday = $date == now('Asia/Jakarta')->format('Y-m-d');
                        @endphp

                        <a href="{{ route('plans.index', ['tanggal' => $date]) }}"
                           style="min-height: 72px;"
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

                <div class="mt-5 flex gap-5 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                        Hari ini
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                        Tanggal dipilih
                    </div>
                </div>
            </section>

            <!-- Detail Rencana -->
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-start justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-slate-950">
                            Detail Tanggal
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ \Carbon\Carbon::parse($selectedDate)->locale('id')->translatedFormat('l, d F Y') }}
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="toggleForm()"
                        class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                        + Tambah
                    </button>
                </div>

                <div class="space-y-3">
                    @forelse ($plans as $plan)
                        @php
                            /*
                                Status sementara.
                                Nanti kalau backend sudah mengirim status dari aktivitas,
                                bisa otomatis ambil dari $plan->status.
                            */
                            $status = $plan->status ?? 'Belum dimulai';

                            $statusClass = match($status) {
                                'Selesai' => 'bg-emerald-50 text-emerald-700',
                                'Terlambat' => 'bg-orange-50 text-orange-700',
                                'Sedang Dikerjakan' => 'bg-blue-50 text-blue-700',
                                default => 'bg-slate-100 text-slate-600',
                            };

                            $dotClass = match($plan->kategori) {
                                'Kuliah' => 'bg-blue-500',
                                'Organisasi' => 'bg-purple-500',
                                'Personal' => 'bg-orange-500',
                                'Sehat' => 'bg-green-500',
                                default => 'bg-emerald-500',
                            };

                            $categoryClass = match($plan->kategori) {
                                'Kuliah' => 'bg-blue-50 text-blue-700',
                                'Organisasi' => 'bg-purple-50 text-purple-700',
                                'Personal' => 'bg-orange-50 text-orange-700',
                                'Sehat' => 'bg-green-50 text-green-700',
                                default => 'bg-emerald-50 text-emerald-700',
                            };
                        @endphp

                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <span class="mt-2 h-2 w-2 rounded-full {{ $dotClass }}"></span>

                                    <div>
                                        <h4 class="font-semibold text-slate-950">
                                            {{ $plan->nama_rencana }}
                                        </h4>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $plan->jam_mulai }} - {{ $plan->jam_selesai }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $categoryClass }}">
                                        {{ $plan->kategori }}
                                    </span>

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                        {{ $status }}
                                    </span>

                                    <button
                                        type="button"
                                        class="text-sm"
                                        onclick="editPlan(
                                            @js($plan->_id),
                                            @js($plan->nama_rencana),
                                            @js($plan->kategori),
                                            @js($plan->jam_mulai),
                                            @js($plan->jam_selesai)
                                        )">
                                        ✏️
                                    </button>

                                    <form action="{{ route('plans.destroy', $plan->_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Hapus rencana ini?')"
                                            class="text-sm">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                            Belum ada rencana pada tanggal ini.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Form Tambah / Edit -->
        <section id="planForm" class="hidden mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 border-b border-slate-200 pb-4">
                <h3 id="formTitle" class="text-xl font-bold text-slate-950">
                    Tambah Rencana
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Rencana ini akan digunakan sebagai target aktivitas.
                </p>
            </div>

            <form id="planFormElement" action="{{ route('plans.store') }}" method="POST" class="space-y-5">
                @csrf
                <div id="methodField"></div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="nama_rencana" class="actify-label">
                            Nama Rencana
                        </label>

                        <input
                            id="nama_rencana"
                            type="text"
                            name="nama_rencana"
                            class="actify-input"
                            placeholder="Contoh: Belajar MongoDB"
                            required>
                    </div>

                    <div>
                        <label for="kategori" class="actify-label">
                            Kategori
                        </label>

                        <select id="kategori" name="kategori" class="actify-input" required>
                            <option value="Produktif">Produktif</option>
                            <option value="Kuliah">Kuliah</option>
                            <option value="Organisasi">Organisasi</option>
                            <option value="Personal">Personal</option>
                            <option value="Sehat">Sehat</option>
                        </select>
                    </div>
                </div>

                <input type="hidden" name="tanggal" value="{{ $selectedDate }}">

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="jam_mulai" class="actify-label">
                            Jam Mulai
                        </label>

                        <input
                            id="jam_mulai"
                            type="time"
                            name="jam_mulai"
                            class="actify-input"
                            required>
                    </div>

                    <div>
                        <label for="jam_selesai" class="actify-label">
                            Jam Selesai
                        </label>

                        <input
                            id="jam_selesai"
                            type="time"
                            name="jam_selesai"
                            class="actify-input"
                            required>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                    <button type="button" onclick="closeForm()" class="actify-btn actify-btn-secondary">
                        Batal
                    </button>

                    <button id="submitButton" type="submit" class="actify-btn actify-btn-primary">
                        Simpan Rencana
                    </button>
                </div>
            </form>
        </section>
    </div>

    <script>
        function toggleForm() {
            const form = document.getElementById('planForm');

            form.classList.remove('hidden');

            document.getElementById('formTitle').innerText = 'Tambah Rencana';
            document.getElementById('planFormElement').action = '{{ route('plans.store') }}';
            document.getElementById('methodField').innerHTML = '';

            document.getElementById('nama_rencana').value = '';
            document.getElementById('kategori').value = 'Produktif';
            document.getElementById('jam_mulai').value = '';
            document.getElementById('jam_selesai').value = '';
            document.getElementById('submitButton').innerText = 'Simpan Rencana';

            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function editPlan(id, nama, kategori, jamMulai, jamSelesai) {
            const form = document.getElementById('planForm');

            form.classList.remove('hidden');

            document.getElementById('formTitle').innerText = 'Edit Rencana';
            document.getElementById('planFormElement').action = '/plans/' + id;
            document.getElementById('methodField').innerHTML = '@method("PUT")';

            document.getElementById('nama_rencana').value = nama;
            document.getElementById('kategori').value = kategori;
            document.getElementById('jam_mulai').value = jamMulai;
            document.getElementById('jam_selesai').value = jamSelesai;
            document.getElementById('submitButton').innerText = 'Update Rencana';

            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function closeForm() {
            document.getElementById('planForm').classList.add('hidden');
        }
    </script>
</x-app-layout>