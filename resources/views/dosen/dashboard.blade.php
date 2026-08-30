<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Dosen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
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

            <!-- Courses and Ungraded Submissions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Courses List -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Mata Kuliah Saya</h3>
                    </div>
                    <div id="courses-list" class="divide-y">
                        <p class="p-6 text-gray-500">Memuat data...</p>
                    </div>
                </div>

                <!-- Ungraded Submissions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Submission Menunggu</h3>
                    </div>
                    <div id="ungraded-list" class="divide-y max-h-96 overflow-y-auto">
                        <p class="p-6 text-gray-500">Memuat data...</p>
                    </div>
                </div>
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

                        // Render courses
                        const coursesList = document.getElementById('courses-list');
                        if (data.data.courses && data.data.courses.length > 0) {
                            coursesList.innerHTML = data.data.courses.map(course => `
                            <div class="p-4 hover:bg-gray-50">
                                <a href="/courses/${course.id}/materials" class="block">
                                    <p class="font-semibold text-gray-900">${course.nama}</p>
                                    <p class="text-sm text-gray-500">${course.kode_matkul} (${course.sks} SKS)</p>
                                </a>
                            </div>
                        `).join('');
                        } else {
                            coursesList.innerHTML = '<p class="p-6 text-gray-500">Belum ada mata kuliah</p>';
                        }

                        // Render ungraded submissions
                        const ungradedList = document.getElementById('ungraded-list');
                        if (data.data.ungraded_submissions && data.data.ungraded_submissions.length > 0) {
                            ungradedList.innerHTML = data.data.ungraded_submissions.map(sub => `
                            <div class="p-4 hover:bg-gray-50 border-b last:border-b-0">
                                <p class="text-sm font-semibold text-gray-900">${sub.assignment_title}</p>
                                <p class="text-xs text-gray-500">${sub.mahasiswa_name}</p>
                                <p class="text-xs text-gray-400 mt-1">${sub.course_name}</p>
                            </div>
                        `).join('');
                        } else {
                            ungradedList.innerHTML = '<p class="p-6 text-gray-500">Semua submission sudah dinilai</p>';
                        }
                    }
                })
                .catch(e => console.error(e));
        }

        document.addEventListener('DOMContentLoaded', loadDashboard);
    </script>
</x-app-layout>
