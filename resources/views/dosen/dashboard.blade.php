<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Dosen') }}
        </h2>
    </x-slot>

    <div class="bg-slate-50 py-8 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 rounded-2xl bg-slate-900 p-6 text-white shadow-sm sm:p-8">
                <p class="text-sm font-medium text-cyan-300">Fakultas Teknik Universitas Suryakancana</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">Dashboard Pengajaran</h1>
                <p class="mt-2 text-sm text-slate-300">Pantau aktivitas kelas, tugas, dan submission mahasiswa.</p>
            </div>
            <!-- Summary Cards -->
            <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Mata Kuliah Diampu</p>
                            <p class="text-3xl font-bold text-gray-900" id="total-courses">-</p>
                        </div>
                        <div class="text-blue-500">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Tugas</p>
                            <p class="text-3xl font-bold text-gray-900" id="total-assignments">-</p>
                        </div>
                        <div class="text-purple-500">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 012-2 1 1 0 100 2H3a1 1 0 00-1 1v10a1 1 0 001 1h14a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 100 2h2v10H4V5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Submission Belum Dinilai</p>
                            <p class="text-3xl font-bold text-red-600" id="ungraded-count">-</p>
                        </div>
                        <div class="text-red-500">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Jadwal Minggu Depan</p>
                            <p class="text-3xl font-bold text-green-600" id="upcoming-schedules">-</p>
                        </div>
                        <div class="text-green-500">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <a href="{{ route('dosen.materials.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Materi</h3>
                    <p class="text-gray-600 text-sm">Kelola materi pembelajaran</p>
                </a>

                <a href="{{ route('dosen.assignments.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Tugas/Kuis</h3>
                    <p class="text-gray-600 text-sm">Kelola tugas dan kuis</p>
                </a>

                <a href="{{ route('dosen.submissions.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Penilaian</h3>
                    <p class="text-gray-600 text-sm">Periksa dan nilai submission</p>
                </a>

                <a href="{{ route('dosen.messages.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pesan</h3>
                    <p class="text-gray-600 text-sm">Komunikasi dengan mahasiswa</p>
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm xl:col-span-3">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="font-semibold text-slate-900">Submission Menunggu Penilaian</h2>
                        <p class="mt-1 text-xs text-slate-500">Tugas yang perlu segera diperiksa</p>
                    </div>
                    <div id="ungraded-list" class="overflow-x-auto">
                        <p class="p-6 text-sm text-slate-500">Memuat data...</p>
                    </div>
                </section>

                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="font-semibold text-slate-900">Jadwal Mengajar</h2>
                        <p class="mt-1 text-xs text-slate-500">Agenda terdekat tujuh hari</p>
                    </div>
                    @if ($upcoming_schedules->isEmpty())
                        <p class="p-6 text-sm text-slate-500">Belum ada jadwal mengajar terdekat.</p>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach ($upcoming_schedules as $schedule)
                                <div class="flex gap-4 px-5 py-4">
                                    <div
                                        class="min-w-20 rounded-lg bg-cyan-50 px-2 py-2 text-center text-xs font-semibold text-cyan-700">
                                        {{ \Carbon\Carbon::parse($schedule['hari'])->format('d M') }}</div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $schedule['course_name'] }}</p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ substr($schedule['jam_mulai'], 0, 5) }} -
                                            {{ substr($schedule['jam_selesai'], 0, 5) }} · {{ $schedule['ruangan'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>

    <script>
        function loadDashboard() {
            fetch("{{ route('dosen.dashboard') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.data) {
                        document.getElementById('total-courses').textContent = data.data.total_courses || 0;
                        document.getElementById('total-assignments').textContent = data.data.total_assignments || 0;
                        document.getElementById('ungraded-count').textContent = data.data.ungraded_submissions_count ||
                            0;
                        document.getElementById('upcoming-schedules').textContent = data.data.upcoming_schedules
                            ?.length || 0;

                        // Render ungraded submissions
                        const ungradedList = document.getElementById('ungraded-list');
                        if (data.data.ungraded_submissions && data.data.ungraded_submissions.length > 0) {
                            ungradedList.innerHTML = `
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                                        <tr><th class="px-5 py-3 font-semibold">Mahasiswa</th><th class="px-5 py-3 font-semibold">Tugas</th><th class="px-5 py-3 font-semibold">Status</th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        ${data.data.ungraded_submissions.map(sub => `
                                                <tr class="hover:bg-slate-50"><td class="whitespace-nowrap px-5 py-4 font-medium text-slate-900">${sub.mahasiswa_name}</td><td class="px-5 py-4"><p class="font-medium text-slate-800">${sub.assignment_title}</p><p class="mt-1 text-xs text-slate-500">${sub.course_name}</p></td><td class="px-5 py-4"><span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">Belum dinilai</span></td></tr>
                                            `).join('')}
                                    </tbody>
                                </table>`;
                        } else {
                            ungradedList.innerHTML =
                                '<p class="p-6 text-sm text-slate-500">Semua submission sudah dinilai.</p>';
                        }
                    }
                })
                .catch(e => console.error(e));
        }

        document.addEventListener('DOMContentLoaded', loadDashboard);
    </script>
</x-app-layout>
