<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <!-- Total Courses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Mata Kuliah</p>
                            <p class="text-3xl font-bold text-gray-900" id="total-courses">-</p>
                        </div>
                        <div class="text-blue-500">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5M10.5 1.5v8m0 0H2m8.5 0h8.5M10.5 9.5l-4-4m4 4l4-4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Dosen -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Dosen</p>
                            <p class="text-3xl font-bold text-gray-900" id="total-dosen">-</p>
                        </div>
                        <div class="text-green-500">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Mahasiswa -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Mahasiswa</p>
                            <p class="text-3xl font-bold text-gray-900" id="total-mahasiswa">-</p>
                        </div>
                        <div class="text-purple-500">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Accounts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Akun Pending</p>
                            <p class="text-3xl font-bold text-yellow-600" id="pending-accounts">-</p>
                        </div>
                        <div class="text-yellow-500">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 3.062v6.218c0 1.3.84 2.407 2.001 2.74.898-.3 1.6-1.239 1.6-2.384V5.517A3.066 3.066 0 0016.744 1.9m-6 2.45a1.066 1.066 0 10-2.132 0 1.066 1.066 0 002.132 0zm0 3.2a1.066 1.066 0 10-2.132 0 1.066 1.066 0 002.132 0zm0 3.2a1.066 1.066 0 10-2.132 0 1.066 1.066 0 002.132 0zM8 7a2 2 0 100-4 2 2 0 000 4zM6 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Assignments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Tugas Aktif</p>
                            <p class="text-3xl font-bold text-red-600" id="active-assignments">-</p>
                        </div>
                        <div class="text-red-500">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 012-2 1 1 0 100 2H3a1 1 0 00-1 1v10a1 1 0 001 1h14a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 100 2h2v10H4V5zm2 5a1 1 0 100 2 1 1 0 000-2zm0 4a1 1 0 100 2 1 1 0 000-2z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <a href="{{ route('admin.courses.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen Mata Kuliah</h3>
                    <p class="text-gray-600 text-sm">Kelola semua mata kuliah dan dosen pengampu</p>
                </a>

                <a href="{{ route('admin.dosen.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen Dosen</h3>
                    <p class="text-gray-600 text-sm">Kelola akun dosen dan verifikasi</p>
                </a>

                <a href="{{ route('admin.mahasiswa.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen Mahasiswa</h3>
                    <p class="text-gray-600 text-sm">Kelola akun mahasiswa dan enrollment</p>
                </a>

                <a href="{{ route('admin.schedules.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 hover:shadow-md transition cursor-pointer">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen Jadwal</h3>
                    <p class="text-gray-600 text-sm">Kelola jadwal mengajar dan ruangan</p>
                </a>
            </div>

            <!-- Recent Accounts Pending -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Akun Menunggu Verifikasi</h3>
                </div>
                <div id="pending-accounts-container" class="p-6">
                    <p class="text-gray-500">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load dashboard data
        function loadDashboardData() {
            // Get summary stats
            fetch("{{ route('admin.dashboard') }}", {
                    headers: {
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.data) {
                        document.getElementById('total-courses').textContent = data.data.total_courses || 0;
                        document.getElementById('total-dosen').textContent = data.data.total_dosen || 0;
                        document.getElementById('total-mahasiswa').textContent = data.data.total_mahasiswa || 0;
                        document.getElementById('pending-accounts').textContent = data.data.pending_accounts || 0;
                        document.getElementById('active-assignments').textContent = data.data.active_assignments || 0;

                        // Load pending accounts
                        if (data.data.pending_users && data.data.pending_users.length > 0) {
                            let html = '<div class="space-y-3">';
                            data.data.pending_users.forEach(user => {
                                html += `
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div>
                                        <p class="font-semibold text-gray-900">${user.name}</p>
                                        <p class="text-sm text-gray-500">${user.email} (${user.role})</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button onclick="approveUser(${user.id}, '${user.role}')" class="px-3 py-1 bg-green-500 text-white text-sm rounded hover:bg-green-600">Setujui</button>
                                        <button onclick="rejectUser(${user.id}, '${user.role}')" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">Tolak</button>
                                    </div>
                                </div>
                            `;
                            });
                            html += '</div>';
                            document.getElementById('pending-accounts-container').innerHTML = html;
                        } else {
                            document.getElementById('pending-accounts-container').innerHTML =
                                '<p class="text-gray-500">Tidak ada akun yang menunggu verifikasi</p>';
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function approveUser(userId, role) {
            const endpoint = role === 'dosen' ?
                `{{ url('admin/dosen') }}/${userId}/approve` :
                `{{ url('admin/mahasiswa') }}/${userId}/approve`;

            fetch(endpoint, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadDashboardData();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }

        function rejectUser(userId, role) {
            const endpoint = role === 'dosen' ?
                `{{ url('admin/dosen') }}/${userId}/reject` :
                `{{ url('admin/mahasiswa') }}/${userId}/reject`;

            fetch(endpoint, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadDashboardData();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', loadDashboardData);
    </script>
</x-app-layout>
