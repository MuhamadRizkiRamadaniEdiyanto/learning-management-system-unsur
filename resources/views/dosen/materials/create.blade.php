<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-cyan-700">{{ $course->kode_matkul }}</p>
                <h2 class="font-semibold text-xl text-slate-800 leading-tight">Tambah Materi</h2>
            </div>
            <a href="{{ route('dosen.materials.index') }}"
                class="text-sm font-semibold text-slate-600 hover:text-slate-900">Kembali ke materi</a>
        </div>
    </x-slot>

    <div class="bg-slate-50 py-8 sm:py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-900 px-6 py-5 text-white sm:px-8">
                    <h1 class="text-lg font-semibold">Materi {{ $course->nama }}</h1>
                    <p class="mt-1 text-sm text-slate-300">Lengkapi informasi materi untuk mahasiswa.</p>
                </div>

                <form method="POST" action="{{ route('courses.materials.store', $course) }}"
                    enctype="multipart/form-data" class="space-y-6 p-6 sm:p-8" x-data="{ type: '{{ old('tipe_materi', 'pdf') }}' }">
                    @csrf

                    <div>
                        <label for="judul" class="block text-sm font-medium text-slate-700">Judul Materi</label>
                        <input id="judul" name="judul" type="text" value="{{ old('judul') }}" required
                            autofocus
                            class="mt-2 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                            placeholder="Contoh: Pengantar Basis Data">
                        <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="5"
                            class="mt-2 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                            placeholder="Tuliskan ringkasan atau petunjuk materi">{{ old('deskripsi') }}</textarea>
                        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                    </div>

                    <fieldset>
                        <legend class="block text-sm font-medium text-slate-700">Tipe Materi</legend>
                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 p-4 hover:border-cyan-400"
                                :class="type === 'pdf' ? 'border-cyan-500 bg-cyan-50' : 'bg-white'">
                                <input type="radio" name="tipe_materi" value="pdf" x-model="type"
                                    class="border-slate-300 text-cyan-600 focus:ring-cyan-500">
                                <span><span class="block text-sm font-semibold text-slate-800">PDF</span><span
                                        class="block text-xs text-slate-500">Upload dokumen, maksimal 10
                                        MB</span></span>
                            </label>
                            <label
                                class="flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 p-4 hover:border-cyan-400"
                                :class="type === 'youtube' ? 'border-cyan-500 bg-cyan-50' : 'bg-white'">
                                <input type="radio" name="tipe_materi" value="youtube" x-model="type"
                                    class="border-slate-300 text-cyan-600 focus:ring-cyan-500">
                                <span><span class="block text-sm font-semibold text-slate-800">Video</span><span
                                        class="block text-xs text-slate-500">Gunakan link video YouTube</span></span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('tipe_materi')" class="mt-2" />
                    </fieldset>

                    <div x-show="type === 'pdf'" x-cloak>
                        <label for="file" class="block text-sm font-medium text-slate-700">Unggah File PDF</label>
                        <input id="file" name="file" type="file" accept=".pdf,application/pdf"
                            :required="type === 'pdf'"
                            class="mt-2 block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-600 file:mr-4 file:border-0 file:bg-cyan-50 file:px-4 file:py-2.5 file:font-semibold file:text-cyan-700 hover:file:bg-cyan-100">
                        <p class="mt-2 text-xs text-slate-500">Format PDF, ukuran maksimal 10 MB.</p>
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>

                    <div x-show="type === 'youtube'" x-cloak>
                        <label for="link_youtube" class="block text-sm font-medium text-slate-700">Link Video
                            YouTube</label>
                        <input id="link_youtube" name="link_youtube" type="url" value="{{ old('link_youtube') }}"
                            :required="type === 'youtube'"
                            class="mt-2 block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500"
                            placeholder="https://www.youtube.com/watch?v=..."><span
                            class="mt-2 block text-xs text-slate-500">Masukkan URL YouTube yang lengkap.</span>
                        <x-input-error :messages="$errors->get('link_youtube')" class="mt-2" />
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">
                        <a href="{{ route('dosen.materials.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-cyan-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">Simpan
                            Materi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
