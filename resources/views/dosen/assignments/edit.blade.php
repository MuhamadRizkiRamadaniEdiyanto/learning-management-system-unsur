<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Edit Tugas</h2><span
                class="text-sm text-gray-500">{{ $course->nama }}</span>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @can('update', $assignment)
                <form method="POST" action="{{ route('courses.assignments.update', [$course, $assignment]) }}"
                    class="space-y-6 bg-white p-6 shadow-sm sm:rounded-lg">
                    @csrf @method('PUT')
                    <div><x-input-label for="judul" value="Judul Tugas" /><x-text-input id="judul" name="judul"
                            class="mt-1 block w-full" value="{{ old('judul', $assignment->judul) }}" required
                            autofocus /><x-input-error :messages="$errors->get('judul')" class="mt-2" /></div>
                    <div><x-input-label for="deskripsi" value="Deskripsi" />
                        <textarea id="deskripsi" name="deskripsi" rows="5" class="mt-1 block w-full rounded-md border-gray-300">{{ old('deskripsi', $assignment->deskripsi) }}</textarea><x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>
                    <div><x-input-label for="tenggat_waktu" value="Tenggat Waktu" /><x-text-input id="tenggat_waktu"
                            name="tenggat_waktu" type="datetime-local" class="mt-1 block w-full"
                            value="{{ old('tenggat_waktu', \Carbon\Carbon::parse($assignment->tenggat_waktu)->format('Y-m-d\\TH:i')) }}"
                            required /><x-input-error :messages="$errors->get('tenggat_waktu')" class="mt-2" /></div>
                    <div class="flex justify-end gap-3"><a href="{{ route('dosen.assignments.index') }}"
                            class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700">Batal</a><x-primary-button>Perbarui
                            Tugas</x-primary-button></div>
                </form>
            @endcan
        </div>
    </div>
</x-app-layout>
