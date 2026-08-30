<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Mahasiswa') }}
            </h2>
            <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                + Tambah Mahasiswa
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <input type="text" id="search-input" placeholder="Cari mahasiswa..."
                    class="w-full px-4 py-2 border border-gray-300 rounded" onkeyup="filterMahasiswaTable()">
            </div>

            <!-- Mahasiswa Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Foto</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">NIM</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="mahasiswa-tbody" class="divide-y">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full mx-4">
            <h3 class="text-lg font-semibold mb-4" id="modal-title">Tambah Mahasiswa</h3>

            <form onsubmit="saveMahasiswa(event)" class="space-y-4">
                <input type="hidden" id="mahasiswa-id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mahasiswa</label>
                    <input type="text" id="mahasiswa-name" class="w-full px-3 py-2 border border-gray-300 rounded"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="mahasiswa-email" class="w-full px-3 py-2 border border-gray-300 rounded"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                    <input type="text" id="mahasiswa-nim" class="w-full px-3 py-2 border border-gray-300 rounded"
                        placeholder="Nomor Induk Mahasiswa" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="mahasiswa-password"
                        class="w-full px-3 py-2 border border-gray-300 rounded"
                        placeholder="Kosongkan untuk tidak mengubah">
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
        let allMahasiswaData = [];

        function loadMahasiswaData() {
            fetch("{{ route('admin.mahasiswa.index') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allMahasiswaData = data.data.data || [];
                    renderMahasiswaTable(allMahasiswaData);
                })
                .catch(e => console.error(e));
        }

        function renderMahasiswaTable(mahasiswaList) {
            const tbody = document.getElementById('mahasiswa-tbody');
            if (mahasiswaList.length === 0) {
                tbody.innerHTML =
                '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td></tr>';
                return;
            }

            tbody.innerHTML = mahasiswaList.map(mahasiswa => {
                const statusBadgeClass = mahasiswa.status_akun === 'aktif' ?
                    'bg-green-100 text-green-800' :
                    mahasiswa.status_akun === 'pending' ?
                    'bg-yellow-100 text-yellow-800' :
                    'bg-red-100 text-red-800';

                const photo = mahasiswa.foto_profil ?
                    `<img src="/storage/${mahasiswa.foto_profil}" class="w-10 h-10 rounded-full object-cover">` :
                    `<div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold">${mahasiswa.name.charAt(0).toUpperCase()}</div>`;

                return `
                    <tr>
                        <td class="px-6 py-4">${photo}</td>
                        <td class="px-6 py-4">${mahasiswa.name}</td>
                        <td class="px-6 py-4">${mahasiswa.email}</td>
                        <td class="px-6 py-4">${mahasiswa.nomor_induk || '-'}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full font-medium ${statusBadgeClass}">
                                ${mahasiswa.status_akun}
                            </span>
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            ${mahasiswa.status_akun === 'pending' ? `
                                    <button onclick="approveMahasiswa(${mahasiswa.id})" class="text-green-500 hover:text-green-700 text-sm">Setujui</button>
                                    <button onclick="rejectMahasiswa(${mahasiswa.id})" class="text-red-500 hover:text-red-700 text-sm">Tolak</button>
                                ` : ''}
                            <button onclick="editMahasiswa(${mahasiswa.id})" class="text-blue-500 hover:text-blue-700 text-sm">Edit</button>
                            <button onclick="viewCourses(${mahasiswa.id})" class="text-purple-500 hover:text-purple-700 text-sm">Lihat Kelas</button>
                            <button onclick="deleteMahasiswa(${mahasiswa.id})" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterMahasiswaTable() {
            const search = document.getElementById('search-input').value.toLowerCase();
            const filtered = allMahasiswaData.filter(m =>
                m.name.toLowerCase().includes(search) ||
                m.email.toLowerCase().includes(search) ||
                (m.nomor_induk && m.nomor_induk.includes(search))
            );
            renderMahasiswaTable(filtered);
        }

        function openCreateModal() {
            document.getElementById('modal-title').textContent = 'Tambah Mahasiswa';
            document.getElementById('mahasiswa-id').value = '';
            document.getElementById('mahasiswa-name').value = '';
            document.getElementById('mahasiswa-email').value = '';
            document.getElementById('mahasiswa-nim').value = '';
            document.getElementById('mahasiswa-password').value = '';
            document.getElementById('modal').classList.remove('hidden');
        }

        function editMahasiswa(id) {
            const mahasiswa = allMahasiswaData.find(m => m.id === id);
            if (mahasiswa) {
                document.getElementById('modal-title').textContent = 'Edit Mahasiswa';
                document.getElementById('mahasiswa-id').value = id;
                document.getElementById('mahasiswa-name').value = mahasiswa.name;
                document.getElementById('mahasiswa-email').value = mahasiswa.email;
                document.getElementById('mahasiswa-nim').value = mahasiswa.nomor_induk || '';
                document.getElementById('mahasiswa-password').value = '';
                document.getElementById('modal').classList.remove('hidden');
            }
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function saveMahasiswa(e) {
            e.preventDefault();
            const id = document.getElementById('mahasiswa-id').value;
            const data = {
                name: document.getElementById('mahasiswa-name').value,
                email: document.getElementById('mahasiswa-email').value,
                nomor_induk: document.getElementById('mahasiswa-nim').value,
            };

            const password = document.getElementById('mahasiswa-password').value;
            if (password) data.password = password;

            const method = id ? 'PATCH' : 'POST';
            const url = id ? `{{ url('admin/mahasiswa') }}/${id}` : '{{ route('admin.mahasiswa.store') }}';

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
                    loadMahasiswaData();
                })
                .catch(e => alert('Error: ' + e));
        }

        function approveMahasiswa(id) {
            fetch(`{{ url('admin/mahasiswa') }}/${id}/approve`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message);
                    loadMahasiswaData();
                })
                .catch(e => alert('Error: ' + e));
        }

        function rejectMahasiswa(id) {
            fetch(`{{ url('admin/mahasiswa') }}/${id}/reject`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message);
                    loadMahasiswaData();
                })
                .catch(e => alert('Error: ' + e));
        }

        function viewCourses(id) {
            fetch(`{{ url('admin/mahasiswa') }}/${id}/courses`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    const courses = data.data || [];
                    const courseList = courses.length > 0 ?
                        courses.map(c => c.nama).join(', ') :
                        'Belum terdaftar di mata kuliah apapun';
                    alert('Mata Kuliah:\n' + courseList);
                })
                .catch(e => alert('Error: ' + e));
        }

        function deleteMahasiswa(id) {
            if (confirm('Yakin ingin menghapus mahasiswa ini?')) {
                fetch(`{{ url('admin/mahasiswa') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(() => loadMahasiswaData())
                    .catch(e => alert('Error: ' + e));
            }
        }

        document.addEventListener('DOMContentLoaded', loadMahasiswaData);
    </script>
</x-app-layout>
