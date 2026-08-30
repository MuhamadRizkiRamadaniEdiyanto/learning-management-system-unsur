<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Dosen') }}
            </h2>
            <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                + Tambah Dosen
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <input type="text" id="search-input" placeholder="Cari dosen..."
                    class="w-full px-4 py-2 border border-gray-300 rounded" onkeyup="filterDosenTable()">
            </div>

            <!-- Dosen Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Foto</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">NIDN</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dosen-tbody" class="divide-y">
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
            <h3 class="text-lg font-semibold mb-4" id="modal-title">Tambah Dosen</h3>

            <form onsubmit="saveDosen(event)" class="space-y-4">
                <input type="hidden" id="dosen-id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dosen</label>
                    <input type="text" id="dosen-name" class="w-full px-3 py-2 border border-gray-300 rounded"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="dosen-email" class="w-full px-3 py-2 border border-gray-300 rounded"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIDN</label>
                    <input type="text" id="dosen-nidn" class="w-full px-3 py-2 border border-gray-300 rounded"
                        placeholder="10 digit" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="dosen-password" class="w-full px-3 py-2 border border-gray-300 rounded"
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
        let allDosenData = [];

        function loadDosenData() {
            fetch("{{ route('admin.dosen.index') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allDosenData = data.data.data || [];
                    renderDosenTable(allDosenData);
                })
                .catch(e => console.error(e));
        }

        function renderDosenTable(dosenList) {
            const tbody = document.getElementById('dosen-tbody');
            if (dosenList.length === 0) {
                tbody.innerHTML =
                '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td></tr>';
                return;
            }

            tbody.innerHTML = dosenList.map(dosen => {
                const statusBadgeClass = dosen.status_akun === 'aktif' ?
                    'bg-green-100 text-green-800' :
                    dosen.status_akun === 'pending' ?
                    'bg-yellow-100 text-yellow-800' :
                    'bg-red-100 text-red-800';

                const photo = dosen.foto_profil ?
                    `<img src="/storage/${dosen.foto_profil}" class="w-10 h-10 rounded-full object-cover">` :
                    `<div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold">${dosen.name.charAt(0).toUpperCase()}</div>`;

                return `
                    <tr>
                        <td class="px-6 py-4">${photo}</td>
                        <td class="px-6 py-4">${dosen.name}</td>
                        <td class="px-6 py-4">${dosen.email}</td>
                        <td class="px-6 py-4">${dosen.nomor_induk || '-'}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full font-medium ${statusBadgeClass}">
                                ${dosen.status_akun}
                            </span>
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            ${dosen.status_akun === 'pending' ? `
                                    <button onclick="approveDosen(${dosen.id})" class="text-green-500 hover:text-green-700 text-sm">Setujui</button>
                                    <button onclick="rejectDosen(${dosen.id})" class="text-red-500 hover:text-red-700 text-sm">Tolak</button>
                                ` : ''}
                            <button onclick="editDosen(${dosen.id})" class="text-blue-500 hover:text-blue-700 text-sm">Edit</button>
                            <button onclick="deleteDosen(${dosen.id})" class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterDosenTable() {
            const search = document.getElementById('search-input').value.toLowerCase();
            const filtered = allDosenData.filter(d =>
                d.name.toLowerCase().includes(search) ||
                d.email.toLowerCase().includes(search) ||
                (d.nomor_induk && d.nomor_induk.includes(search))
            );
            renderDosenTable(filtered);
        }

        function openCreateModal() {
            document.getElementById('modal-title').textContent = 'Tambah Dosen';
            document.getElementById('dosen-id').value = '';
            document.getElementById('dosen-name').value = '';
            document.getElementById('dosen-email').value = '';
            document.getElementById('dosen-nidn').value = '';
            document.getElementById('dosen-password').value = '';
            document.getElementById('modal').classList.remove('hidden');
        }

        function editDosen(id) {
            const dosen = allDosenData.find(d => d.id === id);
            if (dosen) {
                document.getElementById('modal-title').textContent = 'Edit Dosen';
                document.getElementById('dosen-id').value = id;
                document.getElementById('dosen-name').value = dosen.name;
                document.getElementById('dosen-email').value = dosen.email;
                document.getElementById('dosen-nidn').value = dosen.nomor_induk || '';
                document.getElementById('dosen-password').value = '';
                document.getElementById('modal').classList.remove('hidden');
            }
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function saveDosen(e) {
            e.preventDefault();
            const id = document.getElementById('dosen-id').value;
            const data = {
                name: document.getElementById('dosen-name').value,
                email: document.getElementById('dosen-email').value,
                nomor_induk: document.getElementById('dosen-nidn').value,
            };

            const password = document.getElementById('dosen-password').value;
            if (password) data.password = password;

            const method = id ? 'PATCH' : 'POST';
            const url = id ? `{{ url('admin/dosen') }}/${id}` : '{{ route('admin.dosen.store') }}';

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
                    loadDosenData();
                })
                .catch(e => alert('Error: ' + e));
        }

        function approveDosen(id) {
            fetch(`{{ url('admin/dosen') }}/${id}/approve`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message);
                    loadDosenData();
                })
                .catch(e => alert('Error: ' + e));
        }

        function rejectDosen(id) {
            fetch(`{{ url('admin/dosen') }}/${id}/reject`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    alert(data.message);
                    loadDosenData();
                })
                .catch(e => alert('Error: ' + e));
        }

        function deleteDosen(id) {
            if (confirm('Yakin ingin menghapus dosen ini?')) {
                fetch(`{{ url('admin/dosen') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(() => loadDosenData())
                    .catch(e => alert('Error: ' + e));
            }
        }

        document.addEventListener('DOMContentLoaded', loadDosenData);
    </script>
</x-app-layout>
