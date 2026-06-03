<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-slate-950">Tambah Aktivitas</h2>
        <p class="mt-1 text-sm text-slate-500">Isi aktivitas baru yang ingin dicatat.</p>
    </x-slot>

    <div class="actify-page">
        <section class="actify-panel p-5 sm:p-6">
            <div class="mb-6 border-b border-slate-200 pb-4">
                <h1 class="text-xl font-bold text-slate-950">Tambah Aktivitas Baru</h1>
                <p class="mt-1 text-sm text-slate-500">Data akan tersimpan ke backend yang sudah berjalan.</p>
            </div>

            <form action="{{ route('activities.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <div>
                        <label for="plan_id" class="actify-label">
                            Rencana Terkait
                        </label>

                        <select
                            id="plan_id"
                            name="plan_id"
                            class="actify-input">

                            <option value="">
                                Aktivitas Spontan (Tanpa Rencana)
                            </option>

                            @foreach($plans as $plan)

                                <option
                                    value="{{ $plan->_id }}"
                                    data-nama="{{ $plan->nama_rencana }}"
                                    data-kategori="{{ $plan->kategori }}"
                                    data-tanggal="{{ $plan->tanggal }}">

                                    {{ $plan->nama_rencana }}
                                    ({{ $plan->tanggal }})

                                </option>

                            @endforeach

                        </select>
                    </div>
                    <label for="nama_aktivitas" class="actify-label">Nama Aktivitas</label>
                    <input id="nama_aktivitas" type="text" name="nama_aktivitas" value="{{ old('nama_aktivitas') }}" required class="actify-input" placeholder="Contoh: Belajar Basis Data">
                    @error('nama_aktivitas')
                        <p class="actify-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="kategori" class="actify-label">Kategori</label>
                        <select id="kategori" name="kategori" required class="actify-input">
                            <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih kategori</option>
                            <option value="Produktif" {{ old('kategori') === 'Produktif' ? 'selected' : '' }}>Produktif</option>
                            <option value="Sehat" {{ old('kategori') === 'Sehat' ? 'selected' : '' }}>Sehat</option>
                            <option value="Personal" {{ old('kategori') === 'Personal' ? 'selected' : '' }}>Personal</option>
                        </select>
                        @error('kategori')
                            <p class="actify-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal" class="actify-label">Tanggal</label>
                        <input id="tanggal" type="date" name="tanggal" value="{{ old('tanggal') }}" required class="actify-input">
                        @error('tanggal')
                            <p class="actify-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="durasi" class="actify-label">Durasi (Jam)</label>
                    <input id="durasi" type="number" step="0.1" min="0" name="durasi" value="{{ old('durasi') }}" required class="actify-input" placeholder="Contoh: 1.5">
                    @error('durasi')
                        <p class="actify-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="actify-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="actify-input" placeholder="Deskripsi singkat aktivitas (opsional)">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="actify-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                    <button type="reset" class="actify-btn actify-btn-secondary">Reset</button>
                    <button type="submit" class="actify-btn actify-btn-primary">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                            <path d="M17 21v-8H7v8M7 3v5h8"/>
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </section>
    </div>

    <script>

        document
            .getElementById('plan_id')
            .addEventListener('change', function () {

                const selected =
                    this.options[this.selectedIndex];

                if(this.value){

                    document
                        .getElementById('nama_aktivitas')
                        .value =
                        selected.dataset.nama;

                    document
                        .getElementById('kategori')
                        .value =
                        selected.dataset.kategori;

                    document
                        .getElementById('tanggal')
                        .value =
                        selected.dataset.tanggal;
                } else {

                    document
                        .getElementById('nama_aktivitas')
                        .value = '';

                    document
                        .getElementById('kategori')
                        .value = '';

                    document
                        .getElementById('tanggal')
                        .value = '';

                }

            });

    </script>
</x-app-layout>
