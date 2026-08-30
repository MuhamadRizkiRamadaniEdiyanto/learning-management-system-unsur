<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Materi') }}
            </h2>
            <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                + Tambah Materi
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter by Course -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Mata Kuliah</label>
                <select id="course-filter" class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded"
                    onchange="loadMaterials()">
                    <option value="">Semua Mata Kuliah</option>
                </select>
            </div>

            <!-- Materials Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Judul Materi</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tipe</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Mata Kuliah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tanggal Upload</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="materials-tbody" class="divide-y">
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
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-semibold mb-4" id="modal-title">Tambah Materi</h3>

            <form onsubmit="saveMaterial(event)" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" id="material-id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                    <select id="material-course" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                        <option value="">-- Pilih Mata Kuliah --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
                    <input type="text" id="material-title" class="w-full px-3 py-2 border border-gray-300 rounded"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Materi</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="tipe_materi" value="pdf" onchange="toggleFileInput()"
                                class="mr-2" required>
                            <span class="text-sm">PDF</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="tipe_materi" value="png" onchange="toggleFileInput()"
                                class="mr-2">
                            <span class="text-sm">Gambar (PNG/JPG)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="tipe_materi" value="youtube" onchange="toggleFileInput()"
                                class="mr-2">
                            <span class="text-sm">Link YouTube</span>
                        </label>
                    </div>
                </div>

                <!-- File Upload Section -->
                <div id="file-section" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
                    <div
                        class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 12M9 20h30"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="material-file"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload file</span>
                                    <input id="material-file" type="file" class="sr-only"
                                        accept=".pdf,.png,.jpg,.jpeg">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">Ukuran maksimal 10MB</p>
                        </div>
                    </div>
                    <div id="file-info" class="mt-2 text-sm text-gray-600"></div>
                </div>

                <!-- YouTube Link Section -->
                <div id="youtube-section" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link YouTube</label>
                    <input type="url" id="material-youtube" class="w-full px-3 py-2 border border-gray-300 rounded"
                        placeholder="https://www.youtube.com/watch?v=...">
                    <p class="text-xs text-gray-500 mt-1">Masukkan URL YouTube lengkap</p>
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
        let allMaterials = [];
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

                    // Populate course filter
                    const filter = document.getElementById('course-filter');
                    const courseSelect = document.getElementById('material-course');

                    filter.innerHTML = '<option value="">Semua Mata Kuliah</option>';
                    courseSelect.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';

                    allCourses.forEach(course => {
                        const opt1 = document.createElement('option');
                        opt1.value = course.id;
                        opt1.textContent = course.nama;
                        filter.appendChild(opt1);

                        const opt2 = document.createElement('option');
                        opt2.value = course.id;
                        opt2.textContent = course.nama;
                        courseSelect.appendChild(opt2);
                    });
                });
        }

        function loadMaterials() {
            const courseId = document.getElementById('course-filter').value;
            const endpoint = courseId ?
                `{{ url('courses') }}/${courseId}/materials` :
                '{{ url('courses') }}/materials';

            fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allMaterials = data.data || [];
                    renderMaterials(allMaterials);
                })
                .catch(e => console.error(e));
        }

        function renderMaterials(materials) {
            const tbody = document.getElementById('materials-tbody');
            if (materials.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada materi</td></tr>';
                return;
            }

            tbody.innerHTML = materials.map(material => `
                <tr>
                    <td class="px-6 py-4">${material.judul}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full font-medium ${
                            material.tipe_materi === 'pdf' ? 'bg-red-100 text-red-800' :
                            material.tipe_materi === 'png' ? 'bg-blue-100 text-blue-800' :
                            'bg-purple-100 text-purple-800'
                        }">
                            ${material.tipe_materi?.toUpperCase() || 'PDF'}
                        </span>
                    </td>
                    <td class="px-6 py-4">${material.course?.nama || '-'}</td>
                    <td class="px-6 py-4">${new Date(material.created_at).toLocaleDateString('id-ID')}</td>
                    <td class="px-6 py-4 space-x-2">
                        <button onclick="editMaterial(${material.id})" class="text-blue-500 hover:text-blue-700 text-sm">Edit</button>
                        <button onclick="deleteMaterial(${material.id})" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                    </td>
                </tr>
            `).join('');
        }

        function openCreateModal() {
            document.getElementById('modal-title').textContent = 'Tambah Materi';
            document.getElementById('material-id').value = '';
            document.getElementById('material-course').value = '';
            document.getElementById('material-title').value = '';
            document.getElementById('material-file').value = '';
            document.getElementById('material-youtube').value = '';
            document.querySelectorAll('input[name="tipe_materi"]').forEach(r => r.checked = false);
            document.getElementById('file-section').classList.add('hidden');
            document.getElementById('youtube-section').classList.add('hidden');
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function toggleFileInput() {
            const type = document.querySelector('input[name="tipe_materi"]:checked')?.value;
            document.getElementById('file-section').classList.toggle('hidden', type !== 'pdf' && type !== 'png');
            document.getElementById('youtube-section').classList.toggle('hidden', type !== 'youtube');
        }

        function saveMaterial(e) {
            e.preventDefault();
            const id = document.getElementById('material-id').value;
            const type = document.querySelector('input[name="tipe_materi"]:checked').value;
            const formData = new FormData();
            formData.append('course_id', document.getElementById('material-course').value);
            formData.append('judul', document.getElementById('material-title').value);
            formData.append('tipe_materi', type);

            if (type === 'pdf' || type === 'png') {
                const file = document.getElementById('material-file').files[0];
                if (!file) {
                    alert('Pilih file terlebih dahulu');
                    return;
                }
                formData.append('file', file);
            } else {
                formData.append('link_youtube', document.getElementById('material-youtube').value);
            }

            const method = id ? 'POST' : 'POST';
            const url = id ?
                `{{ url('courses') }}/${document.getElementById('material-course').value}/materials/${id}` :
                `{{ url('courses') }}/${document.getElementById('material-course').value}/materials`;

            fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message || 'Berhasil');
                    closeModal();
                    loadMaterials();
                })
                .catch(e => alert('Error: ' + e));
        }

        function deleteMaterial(id) {
            if (confirm('Yakin ingin menghapus materi ini?')) {
                const courseId = allMaterials.find(m => m.id === id)?.course_id;
                fetch(`{{ url('courses') }}/${courseId}/materials/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(() => loadMaterials())
                    .catch(e => alert('Error: ' + e));
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadCourses();
            loadMaterials();
        });
    </script>
</x-app-layout>
