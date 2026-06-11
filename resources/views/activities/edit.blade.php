<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-slate-950">Edit Aktivitas</h2>
        <p class="mt-1 text-sm text-slate-500">Perbarui data aktivitas yang sudah tersimpan.</p>
    </x-slot>

    <div class="actify-page">
        <section class="actify-panel p-5 sm:p-6">
            <div class="mb-6 border-b border-slate-200 pb-4">
                <h1 class="text-xl font-bold text-slate-950">Edit Aktivitas</h1>
                <p class="mt-1 text-sm text-slate-500">Pastikan data aktivitas sudah sesuai sebelum disimpan.</p>
            </div>

            <form action="{{ route('activities.update', $activity->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
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
                                            {{ old('plan_id', $activity->plan_id) == $plan->_id ? 'selected' : '' }}
                                        >
                                            {{ $plan->nama_rencana }}
                                            ({{ $plan->tanggal }})
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
                                        value="{{ old('nama_aktivitas', $activity->nama_aktivitas) }}"
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
                                        <option value="" disabled {{ old('kategori', $activity->kategori) ? '' : 'selected' }}>
                                            Pilih kategori
                                        </option>
                                        <option value="Produktif" {{ old('kategori', $activity->kategori) === 'Produktif' ? 'selected' : '' }}>
                                            Produktif
                                        </option>
                                        <option value="Sehat" {{ old('kategori', $activity->kategori) === 'Sehat' ? 'selected' : '' }}>
                                            Sehat
                                        </option>
                                        <option value="Personal" {{ old('kategori', $activity->kategori) === 'Personal' ? 'selected' : '' }}>
                                            Personal
                                        </option>
                                        <option value="Kuliah" {{ old('kategori', $activity->kategori) === 'Kuliah' ? 'selected' : '' }}>
                                            Kuliah
                                        </option>
                                        <option value="Organisasi" {{ old('kategori', $activity->kategori) === 'Organisasi' ? 'selected' : '' }}>
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
                                >{{ old('deskripsi', $activity->deskripsi) }}</textarea>

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
                                    value="{{ old('tanggal', $activity->tanggal) }}"
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
                                    value="{{ old('jam_mulai', $activity->jam_mulai) }}"
                                    class="actify-input"
                                >

                                @error('jam_mulai')
                                    <p class="actify-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jam Selesai -->
                            <div>
                                <label for="jam_selesai" class="actify-label">
                                    Jam Selesai
                                </label>

                                <input
                                    id="jam_selesai"
                                    name="jam_selesai"
                                    type="time"
                                    value="{{ old('jam_selesai', $activity->jam_selesai) }}"
                                    class="actify-input"
                                >

                                <p class="mt-1 text-xs text-slate-500">
                                    Bisa diketik manual.
                                </p>

                                @error('jam_selesai')
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
                                    value="{{ old('durasi', $activity->durasi) }}"
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
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('activities.index') }}" class="actify-btn actify-btn-secondary">Batal</a>
                    <button type="submit" class="actify-btn actify-btn-primary">Update Aktivitas</button>
                </div>
            </form>
        </section>
    </div>

    <script>
        const jamMulaiInput = document.getElementById('jam_mulai');
        const jamSelesaiInput = document.getElementById('jam_selesai');
        const durasiInput = document.getElementById('durasi');

        function calculateDuration() {

            const startTime = jamMulaiInput.value;
            const endTime = jamSelesaiInput.value;

            if (!startTime || !endTime) return;

            const [startHours, startMinutes] =
                startTime.split(':').map(Number);

            const [endHours, endMinutes] =
                endTime.split(':').map(Number);

            let startTotal =
                startHours * 60 + startMinutes;

            let endTotal =
                endHours * 60 + endMinutes;

            if (endTotal < startTotal) {
                endTotal += 24 * 60;
            }

            const duration =
                (endTotal - startTotal) / 60;

            durasiInput.value =
                duration.toFixed(1);
        }

        function calculateEndTime() {

            const startTime = jamMulaiInput.value;
            const duration =
                parseFloat(durasiInput.value);

            if (!startTime || isNaN(duration)) return;

            const [hours, minutes] =
                startTime.split(':').map(Number);

            const totalMinutes =
                (hours * 60) +
                minutes +
                Math.round(duration * 60);

            const endHours =
                Math.floor(totalMinutes / 60) % 24;

            const endMinutes =
                totalMinutes % 60;

            jamSelesaiInput.value =
                String(endHours).padStart(2, '0')
                + ':'
                + String(endMinutes).padStart(2, '0');
        }

        jamMulaiInput.addEventListener(
            'input',
            calculateDuration
        );

        jamSelesaiInput.addEventListener(
            'input',
            calculateDuration
        );

        durasiInput.addEventListener(
            'input',
            calculateEndTime
        );
    </script>
</x-app-layout>
