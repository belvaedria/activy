<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Aktivitas Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="padding: 20px;">
                
                <form action="{{ route('activities.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 15px; max-width: 400px;">
                    @csrf
                    
                    <div>
                        <label>Nama Aktivitas</label><br>
                        <input type="text" name="nama_aktivitas" required style="width: 100%;">
                    </div>

                    <div>
                        <label>Kategori</label><br>
                        <select name="kategori" required style="width: 100%;">
                            <option value="Produktif">Produktif</option>
                            <option value="Sehat">Sehat</option>
                            <option value="Personal">Personal</option>
                        </select>
                    </div>

                    <div>
                        <label>Tanggal</label><br>
                        <input type="date" name="tanggal" required style="width: 100%;">
                    </div>

                    <div>
                        <label>Durasi (Jam)</label><br>
                        <input type="number" step="0.1" name="durasi" required style="width: 100%;">
                    </div>

                    <div>
                        <label>Deskripsi (Opsional)</label><br>
                        <textarea name="deskripsi" style="width: 100%;"></textarea>
                    </div>

                    <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px; border: none; cursor: pointer;">
                        Simpan Aktivitas
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>