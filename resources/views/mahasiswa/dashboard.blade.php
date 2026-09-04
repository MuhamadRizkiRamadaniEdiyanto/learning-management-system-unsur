<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Mahasiswa</h2>
    </x-slot>

    <div class="bg-slate-50 py-8 sm:py-12">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-sm sm:p-8">
                <p class="text-sm font-medium text-cyan-300">Fakultas Teknik Universitas Suryakancana</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">Selamat datang, {{ auth()->user()->name }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-300">Pantau tugas, jadwal kuliah, dan materi terbaru dalam
                    satu tempat.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                @foreach ([['label' => 'Mata Kuliah', 'value' => $courses->count(), 'color' => 'bg-cyan-500'], ['label' => 'Tugas Terdekat', 'value' => $assignments->count(), 'color' => 'bg-amber-500'], ['label' => 'Nilai Tersedia', 'value' => $nilai_terakhir->count(), 'color' => 'bg-emerald-500']] as $stat)
                    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                            <span class="h-2.5 w-2.5 rounded-full {{ $stat['color'] }}"></span>
                        </div>
                        <p class="mt-3 text-3xl font-bold text-slate-900">{{ $stat['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm xl:col-span-3">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <div>
                            <h2 class="font-semibold text-slate-900">Tugas Mendekati Tenggat</h2>
                            <p class="mt-1 text-xs text-slate-500">Prioritas pengumpulan Anda</p>
                        </div>
                        <a href="{{ route('mahasiswa.assignments.index') }}"
                            class="text-sm font-semibold text-cyan-700 hover:text-cyan-900">Lihat semua</a>
                    </div>
                    @if ($assignments->isEmpty())
                        <p class="px-5 py-8 text-sm text-slate-500">Belum ada tugas yang perlu dikerjakan.</p>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach ($assignments as $assignment)
                                @php
                                    $statusClass = match ($assignment['status']) {
                                        'sudah_dinilai' => 'bg-emerald-50 text-emerald-700',
                                        'sudah_dikumpulkan' => 'bg-cyan-50 text-cyan-700',
                                        'terlambat' => 'bg-rose-50 text-rose-700',
                                        default => 'bg-amber-50 text-amber-700',
                                    };
                                    $statusLabel = match ($assignment['status']) {
                                        'sudah_dinilai' => 'Sudah dinilai',
                                        'sudah_dikumpulkan' => 'Sudah dikumpulkan',
                                        'terlambat' => 'Terlambat',
                                        default => 'Belum dikumpulkan',
                                    };
                                @endphp
                                <div
                                    class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $assignment['judul'] }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $assignment['course_name'] }}</p>
                                    </div>
                                    <div class="flex items-center gap-3"><span
                                            class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span><span
                                            class="whitespace-nowrap text-xs text-slate-500">{{ \Carbon\Carbon::parse($assignment['tenggat_waktu'])->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="font-semibold text-slate-900">Jadwal Kuliah Hari Ini</h2>
                        <p class="mt-1 text-xs text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                    @if ($today_schedules->isEmpty())
                        <p class="px-5 py-8 text-sm text-slate-500">Tidak ada jadwal kuliah hari ini.</p>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach ($today_schedules as $schedule)
                                <div class="flex items-center gap-4 px-5 py-4">
                                    <div
                                        class="min-w-16 rounded-lg bg-cyan-50 px-2 py-2 text-center text-xs font-bold text-cyan-700">
                                        {{ substr($schedule->jam_mulai, 0, 5) }}</div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $schedule->course_name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ substr($schedule->jam_selesai, 0, 5) }} · {{ $schedule->ruangan }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-slate-900">Materi Terbaru</h2>
                        <p class="mt-1 text-xs text-slate-500">Materi dari mata kuliah yang Anda ikuti</p>
                    </div><a href="{{ route('mahasiswa.materials.index') }}"
                        class="text-sm font-semibold text-cyan-700 hover:text-cyan-900">Lihat materi</a>
                </div>
                @if ($latest_materials->isEmpty())
                    <p class="px-5 py-8 text-sm text-slate-500">Belum ada materi terbaru.</p>
                @else
                    <div
                        class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0 lg:grid-cols-3">
                        @foreach ($latest_materials as $material)
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $material->judul }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $material->course_name }}</p>
                                    </div><span
                                        class="rounded-full bg-slate-100 px-2 py-1 text-[11px] font-semibold uppercase text-slate-600">{{ $material->tipe_materi }}</span>
                                </div>
                                <p class="mt-4 text-xs text-slate-500">Ditambahkan
                                    {{ $material->created_at->format('d M Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-slate-900">Pengumuman dan pesan terbaru</h2>
                        <p class="mt-1 text-xs text-slate-500">Kabar terbaru dari ruang kelas Anda</p>
                    </div>
                    <a href="{{ route('mahasiswa.messages.index') }}"
                        class="text-sm font-semibold text-cyan-700 hover:text-cyan-900">Buka pesan</a>
                </div>
                @if ($latest_messages->isEmpty())
                    <p class="px-5 py-8 text-sm text-slate-500">Belum ada pesan terbaru.</p>
                @else
                    <div
                        class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0 lg:grid-cols-3">
                        @foreach ($latest_messages as $message)
                            <article class="p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="truncate text-xs font-semibold uppercase tracking-wide text-cyan-700">
                                        {{ $message->course_name }}</p>
                                    <time
                                        class="shrink-0 text-xs text-slate-400">{{ $message->created_at->format('d M, H:i') }}</time>
                                </div>
                                <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-700">{{ $message->isi }}</p>
                                <p class="mt-3 text-xs text-slate-400">{{ $message->sender?->name ?? 'Pengirim' }}</p>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
