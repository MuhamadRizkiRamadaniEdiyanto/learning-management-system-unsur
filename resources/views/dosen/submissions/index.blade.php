<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Periksa dan Nilai Submission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah</label>
                        <select id="course-filter" class="w-full px-4 py-2 border border-gray-300 rounded"
                            onchange="loadSubmissions()">
                            <option value="">Semua Mata Kuliah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tugas</label>
                        <select id="assignment-filter" class="w-full px-4 py-2 border border-gray-300 rounded"
                            onchange="loadSubmissions()">
                            <option value="">Semua Tugas</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submissions Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tugas</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Waktu Submit</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nilai</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="submissions-tbody" class="divide-y">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grade Modal -->
    <div id="grade-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Nilai Submission</h3>

            <form onsubmit="submitGrade(event)" class="space-y-4">
                <input type="hidden" id="submission-id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mahasiswa</label>
                    <p id="mahasiswa-name" class="text-gray-900 font-medium"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File Jawaban</label>
                    <a id="file-link" href="#" class="text-blue-500 hover:text-blue-700"
                        target="_blank">Download/Lihat File</a>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai (0-100)</label>
                    <input type="number" id="grade-value" min="0" max="100"
                        class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Feedback</label>
                    <textarea id="grade-feedback" class="w-full px-3 py-2 border border-gray-300 rounded" rows="4"
                        placeholder="Berikan feedback untuk mahasiswa..."></textarea>
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeGradeModal()"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Simpan
                        Nilai</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let allSubmissions = [];
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

                    const filter = document.getElementById('course-filter');
                    filter.innerHTML = '<option value="">Semua Mata Kuliah</option>';

                    allCourses.forEach(course => {
                        const opt = document.createElement('option');
                        opt.value = course.id;
                        opt.textContent = course.nama;
                        filter.appendChild(opt);
                    });
                });
        }

        function loadAssignments() {
            const courseId = document.getElementById('course-filter').value;
            if (!courseId) {
                document.getElementById('assignment-filter').innerHTML = '<option value="">Semua Tugas</option>';
                return;
            }

            fetch(`{{ url('courses') }}/${courseId}/assignments`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allAssignments = data.data || [];

                    const filter = document.getElementById('assignment-filter');
                    filter.innerHTML = '<option value="">Semua Tugas</option>';

                    allAssignments.forEach(assignment => {
                        const opt = document.createElement('option');
                        opt.value = assignment.id;
                        opt.textContent = assignment.judul;
                        filter.appendChild(opt);
                    });
                });
        }

        function loadSubmissions() {
            const courseId = document.getElementById('course-filter').value;
            const assignmentId = document.getElementById('assignment-filter').value;

            if (assignmentId) {
                fetch(`{{ url('assignments') }}/${assignmentId}/submissions`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        allSubmissions = data.data || [];
                        renderSubmissions(allSubmissions);
                    });
            } else {
                renderSubmissions([]);
            }
        }

        function renderSubmissions(submissions) {
            const tbody = document.getElementById('submissions-tbody');
            if (submissions.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Pilih tugas untuk melihat submission</td></tr>';
                return;
            }

            tbody.innerHTML = submissions.map(submission => {
                const statusClass = submission.nilai !== null ?
                    'bg-green-100 text-green-800' :
                    'bg-yellow-100 text-yellow-800';

                const isLate = new Date(submission.created_at) > new Date(submission.assignment.tenggat_waktu);

                return `
                    <tr>
                        <td class="px-6 py-4">${submission.user?.name || '-'}</td>
                        <td class="px-6 py-4">${submission.assignment?.judul || '-'}</td>
                        <td class="px-6 py-4">${new Date(submission.created_at).toLocaleDateString('id-ID')}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full font-medium ${isLate ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                                ${isLate ? 'Terlambat' : 'Tepat Waktu'}
                            </span>
                        </td>
                        <td class="px-6 py-4">${submission.nilai !== null ? submission.nilai : '-'}</td>
                        <td class="px-6 py-4">
                            <button onclick="openGradeModal(${submission.id})" class="text-blue-500 hover:text-blue-700 text-sm">
                                ${submission.nilai !== null ? 'Edit' : 'Nilai'}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openGradeModal(submissionId) {
            const submission = allSubmissions.find(s => s.id === submissionId);
            if (submission) {
                document.getElementById('submission-id').value = submissionId;
                document.getElementById('mahasiswa-name').textContent = submission.user?.name || '-';
                document.getElementById('grade-value').value = submission.nilai || '';
                document.getElementById('grade-feedback').value = '';
                document.getElementById('file-link').href = `/storage/${submission.file_jawaban}`;
                document.getElementById('grade-modal').classList.remove('hidden');
            }
        }

        function closeGradeModal() {
            document.getElementById('grade-modal').classList.add('hidden');
        }

        function submitGrade(e) {
            e.preventDefault();
            const submissionId = document.getElementById('submission-id').value;
            const nilai = document.getElementById('grade-value').value;
            const submission = allSubmissions.find(s => s.id == submissionId);

            fetch(`{{ url('assignments') }}/${submission.assignment_id}/submissions/${submissionId}/grade`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nilai: nilai
                    })
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message || 'Nilai berhasil disimpan');
                    closeGradeModal();
                    loadSubmissions();
                })
                .catch(e => alert('Error: ' + e));
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadCourses();
        });

        document.getElementById('course-filter').addEventListener('change', loadAssignments);
    </script>
</x-app-layout>
