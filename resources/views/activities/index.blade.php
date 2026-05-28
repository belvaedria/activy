<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Aktivitas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="padding: 20px;">
                
                @if(session('success'))
                    <div style="color: green; font-weight: bold; margin-bottom: 15px;">
                        {{ session('success') }}
                    </div>
                @endif

                <table border="1" style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="padding: 10px; border: 1px solid #ddd;">Aktivitas</th>
                            <th style="padding: 10px; border: 1px solid #ddd;">Kategori</th>
                            <th style="padding: 10px; border: 1px solid #ddd;">Tanggal</th>
                            <th style="padding: 10px; border: 1px solid #ddd;">Durasi</th>
                            <th style="padding: 10px; border: 1px solid #ddd;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $act)
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $act->nama_aktivitas }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $act->kategori }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $act->tanggal }}</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">{{ $act->durasi }} Jam</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">
                                
                                <a href="{{ route('activities.edit', $act->id) }}" style="color: blue; margin-right: 10px;">Edit</a>
                                
                                <form action="{{ route('activities.destroy', $act->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: red; border: none; background: none; cursor: pointer;">Hapus</button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>