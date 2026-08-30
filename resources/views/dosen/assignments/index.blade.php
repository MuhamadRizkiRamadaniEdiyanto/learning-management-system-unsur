<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Tugas/Kuis') }}
            </h2>
            <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                + Tambah Tugas
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah</label>
                <select id="course-filter" class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded"
                    onchange="loadAssignments()">
                    <option value="">Semua Mata Kuliah</option>
                </select>
            </div>

            <!-- Assignments Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Judul</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mata Kuliah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Deadline</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Submission</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="assignments-tbody" class="divide-y">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full mx-4">
            <h3 class="text-lg font-semibold mb-4">Tambah Tugas</h3>

            <form onsubmit="saveAssignment(event)" class="space-y-4">
                <input type="hidden" id="assignment-id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                    <select id="assignment-course" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                        <option value="">-- Pilih Mata Kuliah --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                    <input type="text" id="assignment-title" class="w-full px-3 py-2 border border-gray-300 rounded"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea id="assignment-desc" class="w-full px-3 py-2 border border-gray-300 rounded" rows="4"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                    <input type="datetime-local" id="assignment-deadline"
                        class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let allAssignments = [];
        let allCourses = [];

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
                    const select = document.getElementById('assignment-course');

                    filter.innerHTML = '<option value="">Semua Mata Kuliah</option>';
                    select.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';

                    allCourses.forEach(course => {
                        const opt1 = document.createElement('option');
                        opt1.value = course.id;
                        opt1.textContent = course.nama;
                        filter.appendChild(opt1);

                        const opt2 = document.createElement('option');
                        opt2.value = course.id;
                        opt2.textContent = course.nama;
                        select.appendChild(opt2);
                    });
                });
        }

        function loadAssignments() {
            const courseId = document.getElementById('course-filter').value;
            const endpoint = courseId ?
                `{{ url('courses') }}/${courseId}/assignments` :
                '{{ url('courses') }}/assignments';

            fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allAssignments = data.data || [];
                    renderAssignments(allAssignments);
                })
                .catch(e => console.error(e));
        }

        function renderAssignments(assignments) {
            const tbody = document.getElementById('assignments-tbody');
            if (assignments.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada tugas</td></tr>';
                return;
            }

            tbody.innerHTML = assignments.map(assignment => {
                const course = allCourses.find(c => c.id === assignment.course_id);
                return `
                    <tr>
                        <td class="px-6 py-4">${assignment.judul}</td>
                        <td class="px-6 py-4">${course?.nama || '-'}</td>
                        <td class="px-6 py-4">${new Date(assignment.tenggat_waktu).toLocaleDateString('id-ID')}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="/dosen/submissions?assignment=${assignment.id}" class="text-blue-500 hover:text-blue-700 text-sm">
                                Lihat
                            </a>
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <button onclick="editAssignment(${assignment.id})" class="text-blue-500 hover:text-blue-700 text-sm">Edit</button>
                            <button onclick="deleteAssignment(${assignment.id})" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openCreateModal() {
            document.getElementById('assignment-id').value = '';
            document.getElementById('assignment-course').value = '';
            document.getElementById('assignment-title').value = '';
            document.getElementById('assignment-desc').value = '';
            document.getElementById('assignment-deadline').value = '';
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function saveAssignment(e) {
            e.preventDefault();
            const id = document.getElementById('assignment-id').value;
            const courseId = document.getElementById('assignment-course').value;
            const data = {
                judul: document.getElementById('assignment-title').value,
                deskripsi: document.getElementById('assignment-desc').value,
                tenggat_waktu: document.getElementById('assignment-deadline').value,
            };

            const method = id ? 'PATCH' : 'POST';
            const url = id ?
                `{{ url('courses') }}/${courseId}/assignments/${id}` :
                `{{ url('courses') }}/${courseId}/assignments`;

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message || 'Berhasil');
                    closeModal();
                    loadAssignments();
                })
                .catch(e => alert('Error: ' + e));
        }

        function deleteAssignment(id) {
            if (confirm('Yakin ingin menghapus tugas ini?')) {
                const assignment = allAssignments.find(a => a.id === id);
                fetch(`{{ url('courses') }}/${assignment.course_id}/assignments/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(() => loadAssignments())
                    .catch(e => alert('Error: ' + e));
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadCourses();
            loadAssignments();
        });
    </script>
</x-app-layout>
