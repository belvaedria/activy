<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-slate-950">Tambah Aktivitas</h2>
        <p class="mt-1 text-sm text-slate-500">
            Tambah aktivitas baru untuk direncanakan dan dilacak.
        </p>
    </x-slot>

    <div class="actify-page">
        <div style="display: grid; grid-template-columns: 1.4fr 0.6fr; gap: 24px; align-items: start;">

            <!-- Form Tambah Aktivitas -->
            <section class="actify-panel p-5 sm:p-6">
                <div class="mb-6 border-b border-slate-200 pb-4">
                    <h1 class="text-xl font-bold text-slate-950">Tambah Aktivitas Baru</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Data akan tersimpan ke backend yang sudah berjalan.
                    </p>
                </div>

                <form action="{{ route('activities.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Informasi Aktivitas -->
                    <div>
                        <h3 class="mb-4 text-base font-bold text-slate-950">
                            Informasi Aktivitas
                        </h3>

                        <div class="space-y-5">
                            <!-- Rencana Terkait -->
                            <div>
                                <label for="plan_id" class="actify-label">
                                    Rencana Terkait
                                </label>

                                <select id="plan_id" name="plan_id" class="actify-input">
                                    <option value="">
                                        Aktivitas Spontan (Tanpa Rencana)
                                    </option>

                                    @foreach($plans as $plan)
                                        <option
                                            value="{{ $plan->_id }}"
                                            data-nama="{{ $plan->nama_rencana }}"
                                            data-kategori="{{ $plan->kategori }}"
                                            data-tanggal="{{ $plan->tanggal }}"
                                            data-jam-mulai="{{ $plan->jam_mulai ?? '' }}"
                                            data-jam-selesai="{{ $plan->jam_selesai ?? '' }}"
                                        >
                                            {{ $plan->nama_rencana }} ({{ $plan->tanggal }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nama Aktivitas & Kategori -->
                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label for="nama_aktivitas" class="actify-label">
                                        Nama Aktivitas
                                    </label>

                                    <input
                                        id="nama_aktivitas"
                                        type="text"
                                        name="nama_aktivitas"
                                        value="{{ old('nama_aktivitas') }}"
                                        required
                                        class="actify-input"
                                        placeholder="Contoh: Belajar Basis Data"
                                    >

                                    @error('nama_aktivitas')
                                        <p class="actify-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="kategori" class="actify-label">
                                        Kategori
                                    </label>

                                    <select id="kategori" name="kategori" required class="actify-input">
                                        <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>
                                            Pilih kategori
                                        </option>
                                        <option value="Produktif" {{ old('kategori') === 'Produktif' ? 'selected' : '' }}>
                                            Produktif
                                        </option>
                                        <option value="Sehat" {{ old('kategori') === 'Sehat' ? 'selected' : '' }}>
                                            Sehat
                                        </option>
                                        <option value="Personal" {{ old('kategori') === 'Personal' ? 'selected' : '' }}>
                                            Personal
                                        </option>
                                        <option value="Kuliah" {{ old('kategori') === 'Kuliah' ? 'selected' : '' }}>
                                            Kuliah
                                        </option>
                                        <option value="Organisasi" {{ old('kategori') === 'Organisasi' ? 'selected' : '' }}>
                                            Organisasi
                                        </option>
                                    </select>

                                    @error('kategori')
                                        <p class="actify-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label for="deskripsi" class="actify-label">
                                    Deskripsi
                                </label>

                                <textarea
                                    id="deskripsi"
                                    name="deskripsi"
                                    rows="4"
                                    class="actify-input"
                                    placeholder="Tambahkan deskripsi aktivitas (opsional)..."
                                >{{ old('deskripsi') }}</textarea>

                                @error('deskripsi')
                                    <p class="actify-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Waktu Aktivitas -->
                    <div class="border-t border-slate-200 pt-6">
                        <h3 class="mb-4 text-base font-bold text-slate-950">
                            Waktu Aktivitas
                        </h3>

                        <div class="grid gap-5 md:grid-cols-4">
                            <!-- Tanggal -->
                            <div>
                                <label for="tanggal" class="actify-label">
                                    Tanggal
                                </label>

                                <input
                                    id="tanggal"
                                    type="date"
                                    name="tanggal"
                                    value="{{ old('tanggal') }}"
                                    required
                                    class="actify-input"
                                >

                                @error('tanggal')
                                    <p class="actify-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jam Mulai -->
                            <div>
                                <label for="jam_mulai" class="actify-label">
                                    Jam Mulai
                                </label>

                                <input
                                    id="jam_mulai"
                                    type="time"
                                    name="jam_mulai"
                                    value="{{ old('jam_mulai') }}"
                                    class="actify-input"
                                >

                                @error('jam_mulai')
                                    <p class="actify-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jam Selesai -->
                            <div>
                                <label for="jam_selesai_rencana" class="actify-label">
                                    Jam Selesai
                                </label>

                                <input
                                    id="jam_selesai_rencana"
                                    type="time"
                                    name="jam_selesai_rencana"
                                    value="{{ old('jam_selesai_rencana') }}"
                                    class="actify-input"
                                >

                                <p class="mt-1 text-xs text-slate-500">
                                    Bisa diketik manual.
                                </p>

                                @error('jam_selesai_rencana')
                                    <p class="actify-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Durasi -->
                            <div>
                                <label for="durasi" class="actify-label">
                                    Durasi (Jam)
                                </label>

                                <input
                                    id="durasi"
                                    type="number"
                                    step="0.1"
                                    min="0"
                                    name="durasi"
                                    value="{{ old('durasi') }}"
                                    required
                                    class="actify-input"
                                    placeholder="Contoh: 1.5"
                                >

                                <p class="mt-1 text-xs text-slate-500">
                                    Otomatis dari jam mulai - selesai, bisa diedit.
                                </p>

                                @error('durasi')
                                    <p class="actify-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status Aktivitas -->
                    <div class="border-t border-slate-200 pt-6">
                        <h3 class="mb-4 text-base font-bold text-slate-950">
                            Status Aktivitas
                        </h3>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label for="status" class="actify-label">
                                    Status
                                </label>

                                <select id="status" name="status" class="actify-input">
                                    <option value="Belum Dikerjakan" {{ old('status') === 'Belum Dikerjakan' ? 'selected' : '' }}>
                                        Belum Dikerjakan
                                    </option>
                                    <option value="Sedang Dikerjakan" {{ old('status') === 'Sedang Dikerjakan' ? 'selected' : '' }}>
                                        Sedang Dikerjakan
                                    </option>
                                    <option value="Selesai" {{ old('status') === 'Selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                    <option value="Terlambat" {{ old('status') === 'Terlambat' ? 'selected' : '' }}>
                                        Terlambat
                                    </option>
                                </select>

                                <p class="mt-2 rounded-lg bg-blue-50 px-3 py-2 text-xs text-blue-700">
                                    Aktivitas dianggap belum selesai sampai pengguna menandai statusnya.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                        <button type="reset" class="actify-btn actify-btn-secondary">
                            Reset
                        </button>

                        <button type="submit" class="actify-btn actify-btn-primary">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                                <path d="M17 21v-8H7v8M7 3v5h8"/>
                            </svg>
                            Simpan Aktivitas
                        </button>
                    </div>
                </form>
            </section>

            <!-- Ringkasan Aktivitas -->
            <aside class="space-y-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-5 text-lg font-bold text-slate-950">
                        Ringkasan Aktivitas
                    </h3>

                    <div class="space-y-5 text-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                                📅
                            </div>
                            <div>
                                <p class="font-semibold text-slate-600">Tanggal</p>
                                <p id="summary_tanggal" class="mt-1 text-slate-950">Belum dipilih</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                                🕘
                            </div>
                            <div>
                                <p class="font-semibold text-slate-600">Jam Mulai</p>
                                <p id="summary_jam_mulai" class="mt-1 text-slate-950">Belum diisi</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                                🏁
                            </div>
                            <div>
                                <p class="font-semibold text-slate-600">Jam Selesai</p>
                                <p id="summary_jam_selesai" class="mt-1 text-slate-950">Belum diisi</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-700">
                                ⏱️
                            </div>
                            <div>
                                <p class="font-semibold text-slate-600">Durasi</p>
                                <p id="summary_durasi" class="mt-1 text-slate-950">Belum diisi</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                                🏷️
                            </div>
                            <div>
                                <p class="font-semibold text-slate-600">Kategori</p>
                                <p id="summary_kategori" class="mt-1 text-slate-950">Belum dipilih</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-700">
                                ●
                            </div>
                            <div>
                                <p class="font-semibold text-slate-600">Status</p>
                                <p id="summary_status" class="mt-1 text-slate-950">Belum Dikerjakan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-bold text-slate-950">
                        Informasi
                    </h3>

                    <div class="rounded-xl bg-blue-50 p-4 text-sm leading-6 text-slate-700">
                        <ul class="list-disc space-y-2 pl-5">
                            <li>Jam mulai dan jam selesai digunakan untuk melihat kesesuaian rencana.</li>
                            <li>Durasi otomatis dihitung dari selisih jam mulai dan jam selesai.</li>
                            <li>Durasi tetap bisa diedit manual jika diperlukan.</li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        const planSelect = document.getElementById('plan_id');
        const namaInput = document.getElementById('nama_aktivitas');
        const kategoriInput = document.getElementById('kategori');
        const tanggalInput = document.getElementById('tanggal');
        const jamMulaiInput = document.getElementById('jam_mulai');
        const jamSelesaiInput = document.getElementById('jam_selesai_rencana');
        const durasiInput = document.getElementById('durasi');
        const statusInput = document.getElementById('status');

        const summaryTanggal = document.getElementById('summary_tanggal');
        const summaryJamMulai = document.getElementById('summary_jam_mulai');
        const summaryJamSelesai = document.getElementById('summary_jam_selesai');
        const summaryDurasi = document.getElementById('summary_durasi');
        const summaryKategori = document.getElementById('summary_kategori');
        const summaryStatus = document.getElementById('summary_status');

        function calculateDuration() {
            const startTime = jamMulaiInput.value;
            const endTime = jamSelesaiInput.value;

            if (!startTime || !endTime) {
                updateSummary();
                return;
            }

            const [startHours, startMinutes] = startTime.split(':').map(Number);
            const [endHours, endMinutes] = endTime.split(':').map(Number);

            let startTotal = (startHours * 60) + startMinutes;
            let endTotal = (endHours * 60) + endMinutes;

            if (endTotal < startTotal) {
                endTotal += 24 * 60;
            }

            const durationHours = (endTotal - startTotal) / 60;

            if (durationHours > 0) {
                durasiInput.value = durationHours.toFixed(1);
            }

            updateSummary();
        }

        function calculateEndTime() {
            const startTime = jamMulaiInput.value;
            const duration = parseFloat(durasiInput.value);

            if (!startTime || isNaN(duration)) {
                updateSummary();
                return;
            }

            const [hours, minutes] = startTime.split(':').map(Number);
            const totalMinutes = (hours * 60) + minutes + Math.round(duration * 60);

            const endHours = Math.floor(totalMinutes / 60) % 24;
            const endMinutes = totalMinutes % 60;

            jamSelesaiInput.value =
                String(endHours).padStart(2, '0') + ':' +
                String(endMinutes).padStart(2, '0');

            updateSummary();
        }

        function updateSummary() {
            summaryTanggal.textContent = tanggalInput.value || 'Belum dipilih';
            summaryJamMulai.textContent = jamMulaiInput.value || 'Belum diisi';
            summaryJamSelesai.textContent = jamSelesaiInput.value || 'Belum diisi';
            summaryDurasi.textContent = durasiInput.value ? durasiInput.value + ' jam' : 'Belum diisi';
            summaryKategori.textContent = kategoriInput.value || 'Belum dipilih';
            summaryStatus.textContent = statusInput.value || 'Belum Dikerjakan';
        }

        planSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];

            if (this.value) {
                namaInput.value = selected.dataset.nama || '';
                kategoriInput.value = selected.dataset.kategori || '';
                tanggalInput.value = selected.dataset.tanggal || '';
                jamMulaiInput.value = selected.dataset.jamMulai || '';
                jamSelesaiInput.value = selected.dataset.jamSelesai || '';
            } else {
                namaInput.value = '';
                kategoriInput.value = '';
                tanggalInput.value = '';
                jamMulaiInput.value = '';
                jamSelesaiInput.value = '';
            }

            calculateDuration();
            updateSummary();
        });

        tanggalInput.addEventListener('input', updateSummary);
        kategoriInput.addEventListener('change', updateSummary);
        jamMulaiInput.addEventListener('input', calculateDuration);
        jamSelesaiInput.addEventListener('input', calculateDuration);
        durasiInput.addEventListener('input', calculateEndTime);
        statusInput.addEventListener('change', updateSummary);

        updateSummary();
    </script>
</x-app-layout>