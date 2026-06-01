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

                <div>
                    <label for="nama_aktivitas" class="actify-label">Nama Aktivitas</label>
                    <input id="nama_aktivitas" type="text" name="nama_aktivitas" value="{{ old('nama_aktivitas', $activity->nama_aktivitas) }}" required class="actify-input">
                    @error('nama_aktivitas')
                        <p class="actify-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="kategori" class="actify-label">Kategori</label>
                        <select id="kategori" name="kategori" required class="actify-input">
                            <option value="Produktif" {{ old('kategori', $activity->kategori) === 'Produktif' ? 'selected' : '' }}>Produktif</option>
                            <option value="Sehat" {{ old('kategori', $activity->kategori) === 'Sehat' ? 'selected' : '' }}>Sehat</option>
                            <option value="Personal" {{ old('kategori', $activity->kategori) === 'Personal' ? 'selected' : '' }}>Personal</option>
                        </select>
                        @error('kategori')
                            <p class="actify-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal" class="actify-label">Tanggal</label>
                        <input id="tanggal" type="date" name="tanggal" value="{{ old('tanggal', $activity->tanggal) }}" required class="actify-input">
                        @error('tanggal')
                            <p class="actify-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="durasi" class="actify-label">Durasi (Jam)</label>
                    <input id="durasi" type="number" step="0.1" min="0" name="durasi" value="{{ old('durasi', $activity->durasi) }}" required class="actify-input">
                    @error('durasi')
                        <p class="actify-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="actify-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="actify-input">{{ old('deskripsi', $activity->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="actify-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('activities.index') }}" class="actify-btn actify-btn-secondary">Batal</a>
                    <button type="submit" class="actify-btn actify-btn-primary">Update Aktivitas</button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>
