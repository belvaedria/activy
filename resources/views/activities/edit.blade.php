<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Aktivitas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="padding: 20px;">
                
                <form action="{{ route('activities.update', $activity->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 15px; max-width: 400px;">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label>Nama Aktivitas</label><br>
                        <input type="text" name="nama_aktivitas" value="{{ $activity->nama_aktivitas }}" required style="width: 100%;">
                    </div>

                    <div>
                        <label>Kategori</label><br>
                        <select name="kategori" required style="width: 100%;">
                            <option value="Produktif" {{ $activity->kategori == 'Produktif' ? 'selected' : '' }}>Produktif</option>
                            <option value="Sehat" {{ $activity->kategori == 'Sehat' ? 'selected' : '' }}>Sehat</option>
                            <option value="Personal" {{ $activity->kategori == 'Personal' ? 'selected' : '' }}>Personal</option>
                        </select>
                    </div>

                    <div>
                        <label>Tanggal</label><br>
                        <input type="date" name="tanggal" value="{{ $activity->tanggal }}" required style="width: 100%;">
                    </div>

                    <div>
                        <label>Durasi (Jam)</label><br>
                        <input type="number" step="0.1" name="durasi" value="{{ $activity->durasi }}" required style="width: 100%;">
                    </div>

                    <div>
                        <label>Deskripsi (Opsional)</label><br>
                        <textarea name="deskripsi" style="width: 100%;">{{ $activity->deskripsi }}</textarea>
                    </div>

                    <button type="submit" style="background-color: #2196F3; color: white; padding: 10px; border: none; cursor: pointer;">
                        Update Aktivitas
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>