<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Detail Tugas</h2><a
                href="{{ route('mahasiswa.assignments.index') }}" class="text-sm font-semibold text-cyan-700">Kembali</a>
        </div>
    </x-slot>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-3xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-cyan-700">{{ $course->nama }}</p>
                <h1 class="mt-2 text-2xl font-bold text-slate-900">{{ $assignment->judul }}</h1>
                <p class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-600">
                    {{ $assignment->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}</p>
                <div class="mt-6 rounded-lg bg-amber-50 p-4 text-sm text-amber-900">Tenggat pengumpulan:
                    <strong>{{ \Carbon\Carbon::parse($assignment->tenggat_waktu)->format('d M Y, H:i') }}</strong>
                </div>
            </section>
            @php($submission = $assignment->submissions->first())
            @if ($submission)
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 text-sm text-emerald-800">Jawaban
                    sudah dikumpulkan pada {{ $submission->created_at->format('d M Y, H:i') }}. Nilai:
                    <strong>{{ $submission->nilai ?? 'Belum dinilai' }}</strong>
                    @if ($submission->feedback)
                        <p class="mt-2">Feedback: {{ $submission->feedback }}</p>
                    @endif
                </div>
            @else
                @can('create', [App\Models\Submission::class, $assignment])
                    <form method="POST" action="{{ route('assignments.submissions.store', $assignment) }}"
                        enctype="multipart/form-data"
                        class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="font-semibold text-slate-900">Unggah submission</h2>@csrf<div><x-input-label
                                for="file_jawaban" value="File jawaban (PDF atau PNG)" /><input id="file_jawaban"
                                name="file_jawaban" type="file" accept=".pdf,.png,application/pdf,image/png" required
                                class="mt-2 block w-full rounded-lg border border-slate-300 text-sm" /><x-input-error
                                :messages="$errors->get('file_jawaban')" class="mt-2" /></div><x-primary-button>Submit Jawaban</x-primary-button>
                    </form>
                @else
                    <div class="rounded-xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-800">Pengumpulan sudah
                        ditutup karena tenggat waktu telah berlalu.</div>
                @endcan
            @endif
        </div>
    </div>
</x-app-layout>
