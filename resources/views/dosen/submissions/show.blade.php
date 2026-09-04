<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Detail dan Penilaian Submission</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <section class="rounded-lg bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ $assignment->course->nama }}</p>
                        <h1 class="mt-1 text-xl font-semibold text-gray-900">{{ $assignment->judul }}</h1>
                        <p class="mt-2 text-sm text-gray-600">Mahasiswa:
                            {{ $submission->user?->name ?? $submission->mahasiswa?->name }}</p>
                    </div>@php($isLate = $submission->created_at->greaterThan($assignment->tenggat_waktu))<span
                        class="rounded-full px-3 py-1 text-xs font-semibold {{ $isLate ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ $isLate ? 'Terlambat' : 'Tepat Waktu' }}</span>
                </div>
                <div class="mt-6 flex flex-wrap items-center gap-3"><span class="text-sm text-gray-500">Dikirim
                        {{ $submission->created_at->format('d M Y, H:i') }}</span>
                    @can('view', $submission)
                        <a href="{{ route('assignments.submissions.download', [$assignment, $submission]) }}"
                            class="rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">Unduh
                            Jawaban</a>
                    @endcan
                </div>
            </section>
            @can('grade', $submission)
                <form method="POST" action="{{ route('assignments.submissions.grade', [$assignment, $submission]) }}"
                    class="space-y-6 rounded-lg bg-white p-6 shadow-sm">
                    @csrf
                    <div><x-input-label for="nilai" value="Nilai (0-100)" /><x-text-input id="nilai" name="nilai"
                            type="number" min="0" max="100" step="0.01" class="mt-1 block w-full"
                            value="{{ old('nilai', $submission->nilai) }}" required /><x-input-error :messages="$errors->get('nilai')"
                            class="mt-2" /></div>
                    <div><x-input-label for="feedback" value="Feedback untuk Mahasiswa" />
                        <textarea id="feedback" name="feedback" rows="6" class="mt-1 block w-full rounded-md border-gray-300">{{ old('feedback', $submission->feedback) }}</textarea><x-input-error :messages="$errors->get('feedback')" class="mt-2" />
                    </div>
                    <div class="flex justify-end gap-3"><a href="{{ route('dosen.submissions.index') }}"
                            class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700">Batal</a><x-primary-button>Simpan
                            Penilaian</x-primary-button></div>
                </form>
            @endcan
        </div>
    </div>
</x-app-layout>
