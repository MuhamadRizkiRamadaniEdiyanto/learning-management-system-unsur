<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tugas</h2>
    </x-slot>
    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-slate-950 p-6 text-white">
                <p class="text-sm font-semibold text-cyan-300">Ruang tugas</p>
                <h1 class="mt-2 text-2xl font-bold">Daftar tugas mata kuliah</h1>
                <p class="mt-2 text-sm text-slate-300">Pilih tugas untuk membaca instruksi dan mengirim jawaban.</p>
            </div>
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Tugas</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Mata Kuliah</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Tenggat</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Status</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($assignments as $assignment)
                                @php($submission = $assignment->submissions->first())
                                @php($late = now()->greaterThan($assignment->tenggat_waktu))
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-slate-900">{{ $assignment->judul }}</p>
                                        <p class="mt-1 max-w-md truncate text-xs text-slate-500">
                                            {{ $assignment->deskripsi }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-slate-700">{{ $assignment->course->nama }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($assignment->tenggat_waktu)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <span
                                            class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $submission ? 'bg-emerald-50 text-emerald-700' : ($late ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">
                                            {{ $submission ? 'Sudah dikumpulkan' : ($late ? 'Terlambat' : 'Belum dikumpulkan') }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('courses.assignments.show', [$assignment->course, $assignment]) }}"
                                            class="text-sm font-semibold text-cyan-700 hover:text-cyan-900">Lihat
                                            detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada
                                        tugas pada mata kuliah yang diikuti.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
