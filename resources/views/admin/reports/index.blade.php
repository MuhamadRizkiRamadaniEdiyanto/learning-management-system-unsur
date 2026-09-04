<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section class="mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-cyan-700">Ikhtisar sistem</p>
                    <h3 class="mt-1 text-lg font-semibold text-slate-900">Aktivitas pembelajaran</h3>
                    <p class="mt-1 text-sm text-slate-500">Pantau materi yang tersedia dan tingkat partisipasi
                        pengumpulan tugas.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th
                                    class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Indikator</th>
                                <th
                                    class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Nilai</th>
                                <th
                                    class="whitespace-nowrap px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="activity-summary-body" class="divide-y divide-slate-100">
                            <tr>
                                <td colspan="3" class="px-6 py-6 text-sm text-slate-500">Memuat ringkasan
                                    aktivitas...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Report Options Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Mahasiswa per Matkul -->
                <a href="#" onclick="loadReport('mahasiswa-per-matkul'); return false;"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Jumlah Mahasiswa per Mata Kuliah</h3>
                    <p class="text-gray-600 text-sm mb-4">Laporan jumlah mahasiswa yang terdaftar di setiap mata kuliah
                    </p>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Lihat
                        Laporan</button>
                </a>

                <!-- Nilai per Matkul -->
                <a href="#" onclick="openCourseSelector('nilai'); return false;"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Rekap Nilai per Mata Kuliah</h3>
                    <p class="text-gray-600 text-sm mb-4">Daftar nilai mahasiswa per mata kuliah</p>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Lihat
                        Laporan</button>
                </a>

                <!-- Pengumpulan Tugas -->
                <a href="#" onclick="openAssignmentSelector(); return false;"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Rekap Pengumpulan Tugas</h3>
                    <p class="text-gray-600 text-sm mb-4">Status pengumpulan tugas (tepat waktu, telat, belum)</p>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Lihat
                        Laporan</button>
                </a>

                <!-- Beban Mengajar -->
                <a href="#" onclick="loadReport('beban-mengajar'); return false;"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Beban Mengajar Dosen</h3>
                    <p class="text-gray-600 text-sm mb-4">Jumlah SKS dan kelas per dosen pengampu</p>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">Lihat
                        Laporan</button>
                </a>
            </div>

            <!-- Report Content -->
            <div id="report-content" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="report-title" class="text-lg font-semibold text-gray-900"></h3>
                    <button onclick="closeReport()" class="text-gray-500 hover:text-gray-700">✕</button>
                </div>

                <div id="report-data" class="overflow-x-auto">
                    <table class="w-full">
                        <thead id="report-thead" class="bg-gray-100 border-b">
                        </thead>
                        <tbody id="report-tbody" class="divide-y">
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex gap-2">
                    <button onclick="exportReport()"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Export Excel
                    </button>
                    <button onclick="printReport()" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Selector Modal -->
    <div id="course-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Pilih Mata Kuliah</h3>

            <select id="course-select" class="w-full px-3 py-2 border border-gray-300 rounded mb-4">
                <option value="">-- Pilih Mata Kuliah --</option>
            </select>

            <div class="flex gap-3 justify-end">
                <button onclick="closeCourseModal()"
                    class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                <button onclick="loadSelectedReport()"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Lihat Laporan</button>
            </div>
        </div>
    </div>

    <!-- Assignment Selector Modal -->
    <div id="assignment-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Pilih Tugas/Kuis</h3>

            <select id="assignment-select" class="w-full px-3 py-2 border border-gray-300 rounded mb-4">
                <option value="">-- Pilih Tugas --</option>
            </select>

            <div class="flex gap-3 justify-end">
                <button onclick="closeAssignmentModal()"
                    class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                <button onclick="loadAssignmentReport()"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Lihat Laporan</button>
            </div>
        </div>
    </div>

    <script>
        let currentReportType = null;
        let currentReportData = [];
        let allCourses = [];
        let allAssignments = [];

        function loadCourses() {
            fetch("{{ route('admin.courses.index') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allCourses = data.data.data || [];

                    // Populate course select
                    const select = document.getElementById('course-select');
                    select.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';
                    allCourses.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.nama;
                        select.appendChild(option);
                    });
                });
        }

        function loadActivitySummary() {
            fetch("{{ route('admin.reports.activity-summary') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(payload => {
                    const summary = payload.data || {};
                    const rows = [
                        ['Total materi diunggah', summary.total_materi ?? 0, 'Materi pembelajaran tersimpan'],
                        ['Total tugas', summary.total_tugas ?? 0, 'Tugas yang tersedia di semua mata kuliah'],
                        ['Total pengumpulan', summary.total_submission ?? 0, 'Dokumen jawaban yang telah dikirim'],
                        ['Rasio pengumpulan', `${summary.rasio_pengumpulan ?? 0}%`,
                            `${summary.total_submission ?? 0} dari ${summary.total_peluang_submission ?? 0} peluang pengumpulan`
                        ],
                    ];
                    document.getElementById('activity-summary-body').innerHTML = rows.map(row => `
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-900">${row[0]}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-cyan-700">${row[1]}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">${row[2]}</td>
                        </tr>`).join('');
                })
                .catch(() => {
                    document.getElementById('activity-summary-body').innerHTML =
                        '<tr><td colspan="3" class="px-6 py-6 text-sm text-rose-600">Ringkasan aktivitas gagal dimuat.</td></tr>';
                });
        }

        function loadAssignments() {
            // Fetch all assignments from all courses
            Promise.all(allCourses.map(course =>
                    fetch(`{{ url('courses') }}/${course.id}/assignments`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json())
                ))
                .then(responses => {
                    allAssignments = [];
                    responses.forEach(data => {
                        if (data.data) allAssignments.push(...data.data);
                    });

                    // Populate assignment select
                    const select = document.getElementById('assignment-select');
                    select.innerHTML = '<option value="">-- Pilih Tugas --</option>';
                    allAssignments.forEach(assignment => {
                        const option = document.createElement('option');
                        option.value = assignment.id;
                        option.textContent = assignment.judul;
                        select.appendChild(option);
                    });
                });
        }

        function openCourseSelector(type) {
            document.getElementById('course-modal').classList.remove('hidden');
            document.getElementById('course-modal').classList.add('flex');
            window.courseReportType = type;
            loadCourses();
        }

        function openAssignmentSelector() {
            loadCourses();
            setTimeout(() => {
                document.getElementById('assignment-modal').classList.remove('hidden');
                document.getElementById('assignment-modal').classList.add('flex');
                loadAssignments();
            }, 100);
        }

        function closeCourseModal() {
            document.getElementById('course-modal').classList.add('hidden');
            document.getElementById('course-modal').classList.remove('flex');
        }

        function closeAssignmentModal() {
            document.getElementById('assignment-modal').classList.add('hidden');
            document.getElementById('assignment-modal').classList.remove('flex');
        }

        function loadReport(reportType) {
            currentReportType = reportType;
            document.getElementById('report-content').classList.remove('hidden');

            if (reportType === 'mahasiswa-per-matkul') {
                loadMahasiswaPerMatkulReport();
            } else if (reportType === 'beban-mengajar') {
                loadBebanMengajarReport();
            }
        }

        function loadSelectedReport() {
            const courseId = document.getElementById('course-select').value;
            if (!courseId) {
                alert('Pilih mata kuliah terlebih dahulu');
                return;
            }

            closeCourseModal();
            document.getElementById('report-content').classList.remove('hidden');

            if (window.courseReportType === 'nilai') {
                loadNilaiReport(courseId);
            }
        }

        function loadAssignmentReport() {
            const assignmentId = document.getElementById('assignment-select').value;
            if (!assignmentId) {
                alert('Pilih tugas terlebih dahulu');
                return;
            }

            closeAssignmentModal();
            document.getElementById('report-content').classList.remove('hidden');

            loadPengumpulanTugasReport(assignmentId);
        }

        function loadMahasiswaPerMatkulReport() {
            fetch("{{ route('admin.reports.mahasiswa-per-matkul') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('report-title').textContent = 'Jumlah Mahasiswa per Mata Kuliah';

                    const thead = document.getElementById('report-thead');
                    thead.innerHTML = `
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mata Kuliah</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jumlah Mahasiswa</th>
                    </tr>
                `;

                    const tbody = document.getElementById('report-tbody');
                    currentReportData = data.data || [];
                    tbody.innerHTML = currentReportData.map(row => `
                    <tr>
                        <td class="px-6 py-4">${row.course_name || row.nama}</td>
                        <td class="px-6 py-4">${row.total_mahasiswa || row.count}</td>
                    </tr>
                `).join('');
                });
        }

        function loadNilaiReport(courseId) {
            fetch(`{{ route('admin.reports.nilai-per-matkul', '') }}/${courseId}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('report-title').textContent = 'Rekap Nilai per Mata Kuliah';

                    const thead = document.getElementById('report-thead');
                    thead.innerHTML = `
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nilai Tugas</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Rata-rata</th>
                    </tr>
                `;

                    const tbody = document.getElementById('report-tbody');
                    currentReportData = data.data || [];
                    tbody.innerHTML = currentReportData.map(row => `
                    <tr>
                        <td class="px-6 py-4">${row.mahasiswa_name || row.name}</td>
                        <td class="px-6 py-4">${row.nilai || '-'}</td>
                        <td class="px-6 py-4">${row.rata_rata || '-'}</td>
                    </tr>
                `).join('');
                });
        }

        function loadPengumpulanTugasReport(assignmentId) {
            fetch(`{{ route('admin.reports.pengumpulan-tugas', '') }}/${assignmentId}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('report-title').textContent = 'Rekap Pengumpulan Tugas';

                    const thead = document.getElementById('report-thead');
                    thead.innerHTML = `
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Waktu Pengumpulan</th>
                    </tr>
                `;

                    const tbody = document.getElementById('report-tbody');
                    currentReportData = data.data || [];
                    tbody.innerHTML = currentReportData.map(row => {
                        const statusClass = row.status === 'tepat_waktu' ? 'bg-green-100 text-green-800' :
                            row.status === 'terlambat' ? 'bg-red-100 text-red-800' :
                            'bg-gray-100 text-gray-800';
                        return `
                        <tr>
                            <td class="px-6 py-4">${row.mahasiswa_name || row.name}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full font-medium ${statusClass}">
                                    ${row.status || 'Belum dikumpulkan'}
                                </span>
                            </td>
                            <td class="px-6 py-4">${row.submitted_at || '-'}</td>
                        </tr>
                    `;
                    }).join('');
                });
        }

        function loadBebanMengajarReport() {
            fetch("{{ route('admin.reports.beban-mengajar') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('report-title').textContent = 'Beban Mengajar Dosen';

                    const thead = document.getElementById('report-thead');
                    thead.innerHTML = `
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Dosen</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jumlah SKS</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jumlah Kelas</th>
                    </tr>
                `;

                    const tbody = document.getElementById('report-tbody');
                    currentReportData = data.data || [];
                    tbody.innerHTML = currentReportData.map(row => `
                    <tr>
                        <td class="px-6 py-4">${row.dosen_name || row.name}</td>
                        <td class="px-6 py-4">${row.total_sks || 0}</td>
                        <td class="px-6 py-4">${row.total_courses || row.count}</td>
                    </tr>
                `).join('');
                });
        }

        function closeReport() {
            document.getElementById('report-content').classList.add('hidden');
        }

        function exportReport() {
            if (currentReportData.length === 0) {
                alert('Tidak ada data untuk diexport');
                return;
            }

            // Simple CSV export
            const headers = Object.keys(currentReportData[0]);
            const csv = [
                headers.join(','),
                ...currentReportData.map(row =>
                    headers.map(h => `"${row[h] || ''}"`).join(',')
                )
            ].join('\n');

            const blob = new Blob([csv], {
                type: 'text/csv'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `laporan-${currentReportType}-${new Date().toISOString().slice(0,10)}.csv`;
            a.click();
        }

        function printReport() {
            window.print();
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadCourses();
            loadActivitySummary();
        });
    </script>
</x-app-layout>
