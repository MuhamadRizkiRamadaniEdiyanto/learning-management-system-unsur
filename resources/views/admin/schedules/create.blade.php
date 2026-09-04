<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Jadwal</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.schedules.store') }}"
                class="space-y-6 bg-white p-6 shadow-sm sm:rounded-lg">
                @csrf
                <div><x-input-label for="course_id" value="Mata Kuliah" /><select id="course_id" name="course_id"
                        class="mt-1 block w-full rounded-md border-gray-300" required>
                        <option value="">Pilih mata kuliah</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->kode_matkul }}
                                - {{ $course->nama }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                </div>
                <div><x-input-label for="hari" value="Hari" /><select id="hari" name="hari"
                        class="mt-1 block w-full rounded-md border-gray-300" required>
                        @foreach (['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day)
                            <option value="{{ $day }}" @selected(old('hari') === $day)>{{ ucfirst($day) }}
                            </option>
                        @endforeach
                    </select><x-input-error :messages="$errors->get('hari')" class="mt-2" /></div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div><x-input-label for="jam_mulai" value="Jam Mulai" /><x-text-input id="jam_mulai"
                            name="jam_mulai" type="time" class="mt-1 block w-full" value="{{ old('jam_mulai') }}"
                            required /><x-input-error :messages="$errors->get('jam_mulai')" class="mt-2" /></div>
                    <div><x-input-label for="jam_selesai" value="Jam Selesai" /><x-text-input id="jam_selesai"
                            name="jam_selesai" type="time" class="mt-1 block w-full" value="{{ old('jam_selesai') }}"
                            required /><x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" /></div>
                </div>
                <div><x-input-label for="ruangan" value="Ruangan" /><x-text-input id="ruangan" name="ruangan"
                        class="mt-1 block w-full" value="{{ old('ruangan') }}" required /><x-input-error
                        :messages="$errors->get('ruangan')" class="mt-2" /></div>
                <div class="flex justify-end gap-3"><a href="{{ route('admin.schedules.index') }}"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700">Batal</a><x-primary-button>Simpan
                        Jadwal</x-primary-button></div>
            </form>
        </div>
    </div>
</x-app-layout>
