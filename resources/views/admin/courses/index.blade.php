<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Mata Kuliah') }}
            </h2>
            <button @click="showCreateForm = true" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                + Tambah Mata Kuliah
            </button>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        courses: [],
        search: '',
        showCreateForm: false,
        showEditForm: false,
        formData: { kode_matkul: '', nama: '', deskripsi: '', sks: 0, dosen_id: '' },
        editingId: null,
        dosenList: [],
        loading: true,
        message: '',
        showAssignForm: false,
        assigningCourse: null,
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('components.alert-message')

            <!-- Search Bar -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <input type="text" x-model="search" @input="filterCourses" placeholder="Cari mata kuliah..."
                    class="w-full px-4 py-2 border border-gray-300 rounded">
            </div>

            <!-- Courses Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Kode</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama Mata Kuliah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">SKS</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Dosen Pengampu</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="courses-tbody" class="divide-y">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showCreateForm || showEditForm"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
        @click="showCreateForm = false; showEditForm = false;">
        <div @click.stop class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full mx-4">
            <h3 class="text-lg font-semibold mb-4" x-text="editingId ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah'"></h3>

            <form @submit.prevent="editingId ? updateCourse() : createCourse()" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Mata Kuliah</label>
                    <input type="text" x-model="formData.kode_matkul"
                        class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Kuliah</label>
                    <input type="text" x-model="formData.nama"
                        class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKS</label>
                    <input type="number" x-model="formData.sks" min="1" max="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea x-model="formData.deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded"></textarea>
                </div>

                <div class="flex gap-3 justify-end">
                    <button type="button" @click="showCreateForm = false; showEditForm = false"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">{{ editingId ? 'Update' : 'Tambah' }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function loadCourses() {
            fetch("{{ route('admin.courses.index') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    window.allCourses = data.data.data || [];
                    renderCourses(window.allCourses);
                })
                .catch(e => console.error(e));
        }

        function renderCourses(courses) {
            const tbody = document.getElementById('courses-tbody');
            if (courses.length === 0) {
                tbody.innerHTML =
                '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td></tr>';
                return;
            }

            tbody.innerHTML = courses.map(course => `
                <tr>
                    <td class="px-6 py-4">${course.kode_matkul}</td>
                    <td class="px-6 py-4">${course.nama}</td>
                    <td class="px-6 py-4">${course.sks || '-'}</td>
                    <td class="px-6 py-4">${course.dosen?.name || '-'}</td>
                    <td class="px-6 py-4 space-x-2">
                        <button onclick="editCourse(${course.id})" class="text-blue-500 hover:text-blue-700">Edit</button>
                        <button onclick="deleteCourse(${course.id})" class="text-red-500 hover:text-red-700">Hapus</button>
                    </td>
                </tr>
            `).join('');
        }

        function filterCourses() {
            const search = document.querySelector('input[placeholder="Cari mata kuliah..."]').value.toLowerCase();
            const filtered = window.allCourses.filter(c =>
                c.nama.toLowerCase().includes(search) ||
                c.kode_matkul.toLowerCase().includes(search)
            );
            renderCourses(filtered);
        }

        function editCourse(id) {
            const course = window.allCourses.find(c => c.id === id);
            if (course) {
                document.querySelector('[x-data*="editingId"]).__x.$data.formData = { ...course };
                        document.querySelector('[x-data*="editingId"]).__x.$data.editingId = id;
                            document.querySelector('[x-data*="editingId"]).__x.$data.showEditForm = true;
                            }
                        }

                        function deleteCourse(id) {
                            if (confirm('Yakin ingin menghapus mata kuliah ini?')) {
                                fetch(`{{ url('admin/courses') }}/${id}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    })
                                    .then(() => loadCourses())
                                    .catch(e => alert('Error: ' + e));
                            }
                        }

                        document.addEventListener('DOMContentLoaded', loadCourses);
    </script>
</x-app-layout>
