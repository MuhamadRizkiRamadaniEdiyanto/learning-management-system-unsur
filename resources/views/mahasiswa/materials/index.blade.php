<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Materi Pembelajaran</h2>
    </x-slot>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-slate-950 p-6 text-white">
                <p class="text-sm font-semibold text-cyan-300">Ruang belajar</p>
                <h1 class="mt-2 text-2xl font-bold">Materi dari mata kuliah Anda</h1>
            </div>
            @forelse ($courses as $course)
                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="font-semibold text-slate-900">{{ $course->nama }}</h2>
                        <p class="mt-1 text-xs text-slate-500">{{ $course->kode_matkul }}</p>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($course->materials as $material)
                            <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $material->judul }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $material->deskripsi }}</p>
                                </div>
                                @can('view', $material)
                                    <a href="{{ route('courses.materials.download', [$course, $material]) }}"
                                        class="shrink-0 rounded-lg bg-cyan-700 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-cyan-800">{{ $material->tipe_materi === 'youtube' ? 'Buka Video' : 'Download Materi' }}</a>
                                @endcan
                            </div>
                        @empty
                            <p class="px-5 py-8 text-sm text-slate-500">Belum ada materi.</p>
                        @endforelse
                    </div>
                </section>
            @empty
                <div class="rounded-xl border border-slate-200 bg-white p-8 text-sm text-slate-500">Anda belum mengikuti
                    mata kuliah.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
</x-app-layout>
