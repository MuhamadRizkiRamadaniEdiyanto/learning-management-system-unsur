<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Mata Kuliah</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.courses.store') }}"
                class="space-y-6 bg-white p-6 shadow-sm sm:rounded-lg">
                @csrf
                <div><x-input-label for="kode_matkul" value="Kode Mata Kuliah" /><x-text-input id="kode_matkul"
                        name="kode_matkul" class="mt-1 block w-full" value="{{ old('kode_matkul') }}" required
                        autofocus /><x-input-error :messages="$errors->get('kode_matkul')" class="mt-2" /></div>
                <div><x-input-label for="nama" value="Nama Mata Kuliah" /><x-text-input id="nama"
                        name="nama" class="mt-1 block w-full" value="{{ old('nama') }}" required /><x-input-error
                        :messages="$errors->get('nama')" class="mt-2" /></div>
                <div><x-input-label for="dosen_id" value="Dosen Pengampu" /><select id="dosen_id" name="dosen_id"
                        class="mt-1 block w-full rounded-md border-gray-300" required>
                        <option value="">Pilih dosen</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" @selected(old('dosen_id') == $dosen->id)>{{ $dosen->name }}
                                ({{ $dosen->nomor_induk }})</option>
                        @endforeach
                    </select><x-input-error :messages="$errors->get('dosen_id')" class="mt-2" /></div>
                <div><x-input-label for="deskripsi" value="Deskripsi" />
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300">{{ old('deskripsi') }}</textarea><x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                </div>
                <div class="flex justify-end gap-3"><a href="{{ route('admin.courses.index') }}"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700">Batal</a><x-primary-button>Simpan
                        Mata Kuliah</x-primary-button></div>
            </form>
        </div>
    </div>
</x-app-layout>
