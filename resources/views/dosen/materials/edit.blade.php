<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Edit Materi</h2><span
                class="text-sm text-gray-500">{{ $course->nama }}</span>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @can('update', $material)
                <form method="POST" action="{{ route('courses.materials.update', [$course, $material]) }}"
                    enctype="multipart/form-data" class="space-y-6 bg-white p-6 shadow-sm sm:rounded-lg"
                    x-data="{ type: '{{ old('tipe_materi', $material->tipe_materi) }}' }">
                    @csrf @method('PUT')
                    <div><x-input-label for="judul" value="Judul Materi" /><x-text-input id="judul" name="judul"
                            class="mt-1 block w-full" value="{{ old('judul', $material->judul) }}" required
                            autofocus /><x-input-error :messages="$errors->get('judul')" class="mt-2" /></div>
                    <div><x-input-label for="deskripsi" value="Deskripsi" />
                        <textarea id="deskripsi" name="deskripsi" rows="5" class="mt-1 block w-full rounded-md border-gray-300">{{ old('deskripsi', $material->deskripsi) }}</textarea><x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>
                    <fieldset>
                        <legend class="text-sm font-medium text-gray-700">Tipe Materi</legend>
                        <div class="mt-3 flex flex-wrap gap-4"><label class="flex items-center gap-2"><input type="radio"
                                    name="tipe_materi" value="pdf" x-model="type" required> PDF</label><label
                                class="flex items-center gap-2"><input type="radio" name="tipe_materi" value="png"
                                    x-model="type"> PNG</label><label class="flex items-center gap-2"><input type="radio"
                                    name="tipe_materi" value="youtube" x-model="type"> YouTube</label></div><x-input-error
                            :messages="$errors->get('tipe_materi')" class="mt-2" />
                    </fieldset>
                    <div x-show="type === 'pdf' || type === 'png'" x-cloak><x-input-label for="file"
                            value="Ganti File (opsional)" /><input id="file" name="file" type="file"
                            accept=".pdf,.png,application/pdf,image/png"
                            class="mt-1 block w-full rounded-md border border-gray-300 text-sm" />
                        <p class="mt-1 text-xs text-gray-500">Kosongkan untuk mempertahankan file lama. Maksimal 10 MB.</p>
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>
                    <div x-show="type === 'youtube'" x-cloak><x-input-label for="link_youtube"
                            value="Link YouTube" /><x-text-input id="link_youtube" name="link_youtube" type="url"
                            class="mt-1 block w-full"
                            value="{{ old('link_youtube', $material->link_youtube) }}" /><x-input-error :messages="$errors->get('link_youtube')"
                            class="mt-2" /></div>
                    <div class="flex justify-end gap-3"><a href="{{ route('dosen.materials.index') }}"
                            class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700">Batal</a><x-primary-button>Perbarui
                            Materi</x-primary-button></div>
                </form>
            @endcan
        </div>
    </div>
</x-app-layout>
