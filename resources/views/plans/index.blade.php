<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4">

            <h2 class="text-2xl font-bold mb-6">
                Rencana
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Kalender -->
                <div class="bg-white rounded-lg shadow p-6">

                    <h3 class="text-lg font-semibold mb-4">
                        {{ $monthName }} {{ $currentYear }}
                    </h3>

                    <div class="grid grid-cols-7 gap-2 text-center">

                        <div class="font-semibold">Min</div>
                        <div class="font-semibold">Sen</div>
                        <div class="font-semibold">Sel</div>
                        <div class="font-semibold">Rab</div>
                        <div class="font-semibold">Kam</div>
                        <div class="font-semibold">Jum</div>
                        <div class="font-semibold">Sab</div>

                        @for ($i = 0; $i < $firstDayOfMonth; $i++)
                            <div></div>
                        @endfor

                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $date = sprintf(
                                    '%04d-%02d-%02d',
                                    $currentYear,
                                    $currentMonth,
                                    $day
                                );
                            @endphp

                            <a href="{{ route('plans.index', ['tanggal' => $date]) }}"
                               class="p-2 rounded hover:bg-blue-100
                               {{ $selectedDate == $date ? 'bg-blue-500 text-white' : '' }}">
                                {{ $day }}
                            </a>
                        @endfor

                    </div>
                </div>

                <!-- Daftar Rencana -->
                <div class="bg-white rounded-lg shadow p-6">

                    <div class="flex justify-between items-center mb-4">

                        <h3 class="text-lg font-semibold">
                            {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}
                        </h3>

                        <button
                            type="button"
                            onclick="toggleForm()"
                            class="bg-blue-500 text-white px-4 py-2 rounded">

                            + Tambah

                        </button>

                    </div>

                    @forelse ($plans as $plan)

                    <div class="border rounded p-4 mb-3">

                        <div class="flex justify-between items-start">

                            <div>

                                <h4 class="font-semibold">
                                    {{ $plan->nama_rencana }}
                                </h4>

                                <p class="text-sm text-gray-500">
                                    {{ $plan->jam_mulai }}
                                    -
                                    {{ $plan->jam_selesai }}
                                </p>

                                <p class="text-sm">
                                    {{ $plan->kategori }}
                                </p>

                            </div>

                            <div class="flex gap-3">

                                <button
                                    type="button"
                                    onclick="
                                        document.getElementById('planForm').classList.remove('hidden');

                                        document.getElementById('formTitle').innerText='Edit Rencana';

                                        document.getElementById('nama_rencana').value='{{ $plan->nama_rencana }}';

                                        document.getElementById('kategori').value='{{ $plan->kategori }}';

                                        document.getElementById('jam_mulai').value='{{ $plan->jam_mulai }}';

                                        document.getElementById('jam_selesai').value='{{ $plan->jam_selesai }}';

                                        document.getElementById('planFormElement').action='/plans/{{ $plan->_id }}';

                                        document.getElementById('methodField').innerHTML='@method("PUT")';

                                        document.getElementById('submitButton').innerText ='Update Rencana';
                                    ">
                                    ✏️
                                </button>

                                <form
                                    action="{{ route('plans.destroy', $plan->_id) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        onclick="return confirm('Hapus rencana ini?')">

                                        🗑️

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                    @empty

                        <div class="text-gray-500">
                            Belum ada rencana pada tanggal ini.
                        </div>

                    @endforelse
                </div>
            </div>

            <div
                id="planForm"
                class="hidden mt-6 bg-white rounded-lg shadow p-6">

                <h3 
                    id="formTitle"
                    class="text-lg font-semibold mb-4">
                    Tambah Rencana
                </h3>

                <form
                    id="planFormElement"
                    action="{{ route('plans.store') }}"
                    method="POST">

                    @csrf

                    <div id="methodField"></div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium">
                            Nama Rencana
                        </label>

                        <input
                            id="nama_rencana"
                            type="text"
                            name="nama_rencana"
                            class="w-full border rounded p-2"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium">
                            Kategori
                        </label>

                        <select
                            id="kategori"
                            name="kategori"
                            class="w-full border rounded p-2"
                            required>

                            <option value="Produktif">
                                Produktif
                            </option>

                            <option value="Olahraga">
                                Olahraga
                            </option>

                            <option value="Personal">
                                Personal
                            </option>

                        </select>
                    </div>

                    <input
                        type="hidden"
                        name="tanggal"
                        value="{{ $selectedDate }}">

                    <input
                        type="hidden"
                        id="plan_id">

                    <div class="grid grid-cols-2 gap-3 mb-3">

                        <div>
                            <label class="block text-sm font-medium">
                                Jam Mulai
                            </label>

                            <input
                                id="jam_mulai"
                                type="time"
                                name="jam_mulai"
                                class="w-full border rounded p-2"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Jam Selesai
                            </label>

                            <input
                                id="jam_selesai"
                                type="time"
                                name="jam_selesai"
                                class="w-full border rounded p-2"
                                required>
                        </div>

                    </div>

                    <button
                        id="submitButton"
                        type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded">

                        Simpan Rencana

                    </button>
                </form>

            </div>
        </div>

    
    </div>
</x-app-layout>

{{-- kalo scriptnya ga work pindahin ke sebelum x-app-layout --}}
<script>
    function toggleForm() {

        document
            .getElementById('planForm')
            .classList
            .toggle('hidden');

        document.getElementById('formTitle').innerText =
            'Tambah Rencana';

        document.getElementById('planFormElement').action =
            '/plans';

        document.getElementById('methodField').innerHTML = '';

        document.getElementById('nama_rencana').value = '';

        document.getElementById('kategori').value = 'Produktif';

        document.getElementById('jam_mulai').value = '';

        document.getElementById('jam_selesai').value = '';

        document.getElementById('submitButton').innerText = 'Simpan Rencana';
    }
</script>