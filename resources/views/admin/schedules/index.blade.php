<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Jadwal Mengajar') }}
            </h2>
            <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                + Tambah Jadwal
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                        <select id="course-filter" class="w-full px-4 py-2 border border-gray-300 rounded"
                            onchange="filterSchedules()">
                            <option value="">Semua Mata Kuliah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                        <select id="day-filter" class="w-full px-4 py-2 border border-gray-300 rounded"
                            onchange="filterSchedules()">
                            <option value="">Semua Hari</option>
                            <option value="Monday">Senin</option>
                            <option value="Tuesday">Selasa</option>
                            <option value="Wednesday">Rabu</option>
                            <option value="Thursday">Kamis</option>
                            <option value="Friday">Jumat</option>
                            <option value="Saturday">Sabtu</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Schedules Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mata Kuliah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Hari</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Jam</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Ruangan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="schedules-tbody" class="divide-y">
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
            <h3 class="text-lg font-semibold mb-4" id="modal-title">Tambah Jadwal</h3>

            <form onsubmit="saveSchedule(event)" class="space-y-4">
                <input type="hidden" id="schedule-id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                    <select id="schedule-course" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                        <option value="">-- Pilih Mata Kuliah --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select id="schedule-day" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                        <option value="">-- Pilih Hari --</option>
                        <option value="Monday">Senin</option>
                        <option value="Tuesday">Selasa</option>
                        <option value="Wednesday">Rabu</option>
                        <option value="Thursday">Kamis</option>
                        <option value="Friday">Jumat</option>
                        <option value="Saturday">Sabtu</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                        <input type="time" id="schedule-start"
                            class="w-full px-3 py-2 border border-gray-300 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                        <input type="time" id="schedule-end" class="w-full px-3 py-2 border border-gray-300 rounded"
                            required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                    <input type="text" id="schedule-room" class="w-full px-3 py-2 border border-gray-300 rounded"
                        placeholder="Contoh: Ruang 101" required>
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
        let allSchedulesData = [];
        let allCoursesData = [];

        const dayNames = {
            'Monday': 'Senin',
            'Tuesday': 'Selasa',
            'Wednesday': 'Rabu',
            'Thursday': 'Kamis',
            'Friday': 'Jumat',
            'Saturday': 'Sabtu'
        };

        function loadSchedules() {
            fetch("{{ route('admin.schedules.index') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allSchedulesData = data.data.data || [];
                    renderSchedules(allSchedulesData);
                })
                .catch(e => console.error(e));
        }

        function loadCourses() {
            fetch("{{ route('admin.courses.index') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allCoursesData = data.data.data || [];

                    // Populate course select
                    const courseSelect = document.getElementById('schedule-course');
                    courseSelect.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';
                    allCoursesData.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.nama;
                        courseSelect.appendChild(option);
                    });

                    // Populate course filter
                    const courseFilter = document.getElementById('course-filter');
                    courseFilter.innerHTML = '<option value="">Semua Mata Kuliah</option>';
                    allCoursesData.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.nama;
                        courseFilter.appendChild(option);
                    });
                })
                .catch(e => console.error(e));
        }

        function renderSchedules(schedules) {
            const tbody = document.getElementById('schedules-tbody');
            if (schedules.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada jadwal</td></tr>';
                return;
            }

            tbody.innerHTML = schedules.map(schedule => {
                const course = allCoursesData.find(c => c.id === schedule.course_id);
                return `
                    <tr>
                        <td class="px-6 py-4">${course?.nama || '-'}</td>
                        <td class="px-6 py-4">${dayNames[schedule.hari] || schedule.hari}</td>
                        <td class="px-6 py-4">${schedule.jam_mulai} - ${schedule.jam_selesai}</td>
                        <td class="px-6 py-4">${schedule.ruangan}</td>
                        <td class="px-6 py-4 space-x-2">
                            <button onclick="editSchedule(${schedule.id})" class="text-blue-500 hover:text-blue-700 text-sm">Edit</button>
                            <button onclick="deleteSchedule(${schedule.id})" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterSchedules() {
            const courseId = document.getElementById('course-filter').value;
            const day = document.getElementById('day-filter').value;

            let filtered = allSchedulesData;
            if (courseId) filtered = filtered.filter(s => s.course_id == courseId);
            if (day) filtered = filtered.filter(s => s.hari === day);

            renderSchedules(filtered);
        }

        function openCreateModal() {
            document.getElementById('modal-title').textContent = 'Tambah Jadwal';
            document.getElementById('schedule-id').value = '';
            document.getElementById('schedule-course').value = '';
            document.getElementById('schedule-day').value = '';
            document.getElementById('schedule-start').value = '';
            document.getElementById('schedule-end').value = '';
            document.getElementById('schedule-room').value = '';
            document.getElementById('modal').classList.remove('hidden');
        }

        function editSchedule(id) {
            const schedule = allSchedulesData.find(s => s.id === id);
            if (schedule) {
                document.getElementById('modal-title').textContent = 'Edit Jadwal';
                document.getElementById('schedule-id').value = id;
                document.getElementById('schedule-course').value = schedule.course_id;
                document.getElementById('schedule-day').value = schedule.hari;
                document.getElementById('schedule-start').value = schedule.jam_mulai;
                document.getElementById('schedule-end').value = schedule.jam_selesai;
                document.getElementById('schedule-room').value = schedule.ruangan;
                document.getElementById('modal').classList.remove('hidden');
            }
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function saveSchedule(e) {
            e.preventDefault();
            const id = document.getElementById('schedule-id').value;
            const data = {
                course_id: document.getElementById('schedule-course').value,
                hari: document.getElementById('schedule-day').value,
                jam_mulai: document.getElementById('schedule-start').value,
                jam_selesai: document.getElementById('schedule-end').value,
                ruangan: document.getElementById('schedule-room').value,
            };

            const method = id ? 'PATCH' : 'POST';
            const url = id ? `{{ url('admin/schedules') }}/${id}` : '{{ route('admin.schedules.store') }}';

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
                    loadSchedules();
                })
                .catch(e => alert('Error: ' + e));
        }

        function deleteSchedule(id) {
            if (confirm('Yakin ingin menghapus jadwal ini?')) {
                fetch(`{{ url('admin/schedules') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(() => loadSchedules())
                    .catch(e => alert('Error: ' + e));
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadCourses();
            loadSchedules();
        });
    </script>
</x-app-layout>
