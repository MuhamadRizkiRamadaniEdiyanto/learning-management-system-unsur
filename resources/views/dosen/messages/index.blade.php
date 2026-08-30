<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesan Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Course Selection -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Mata Kuliah</label>
                <select id="course-select" class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded"
                    onchange="loadCourse()">
                    <option value="">-- Pilih Mata Kuliah --</option>
                </select>
            </div>

            <!-- Messages Container -->
            <div id="messages-container" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Courses Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h3 class="text-lg font-semibold mb-4">Kelas Saya</h3>
                        <div id="courses-list" class="space-y-2">
                            <p class="text-gray-500 text-sm">Memuat kelas...</p>
                        </div>
                    </div>
                </div>

                <!-- Messages Main Area -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden flex flex-col h-96">
                        <!-- Messages History -->
                        <div id="messages-history" class="flex-1 p-4 overflow-y-auto bg-gray-50">
                            <p class="text-center text-gray-500 text-sm py-8">Pilih kelas untuk melihat pesan</p>
                        </div>

                        <!-- Message Input -->
                        <div id="message-input-section" class="hidden border-t p-4 bg-white">
                            <div class="space-y-3">
                                <textarea id="message-input" class="w-full px-3 py-2 border border-gray-300 rounded" placeholder="Tulis pesan..."
                                    rows="3"></textarea>
                                <button onclick="sendMessage()"
                                    class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCourse = null;
        let allCourses = [];
        let allMessages = [];

        function loadCourses() {
            fetch("{{ route('admin.courses.index') }}", {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allCourses = data.data.data || [];

                    const select = document.getElementById('course-select');
                    select.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';

                    const coursesList = document.getElementById('courses-list');
                    coursesList.innerHTML = '';

                    allCourses.forEach(course => {
                        const opt = document.createElement('option');
                        opt.value = course.id;
                        opt.textContent = course.nama;
                        select.appendChild(opt);

                        const courseBtn = document.createElement('button');
                        courseBtn.className = 'w-full text-left px-3 py-2 rounded hover:bg-blue-50 transition';
                        courseBtn.textContent = course.nama;
                        courseBtn.onclick = () => {
                            currentCourse = course;
                            document.querySelectorAll('#courses-list button').forEach(b => b.classList
                                .remove('bg-blue-100', 'font-semibold'));
                            courseBtn.classList.add('bg-blue-100', 'font-semibold');
                            loadMessages();
                        };
                        coursesList.appendChild(courseBtn);
                    });
                });
        }

        function loadCourse() {
            const courseId = document.getElementById('course-select').value;
            if (courseId) {
                currentCourse = allCourses.find(c => c.id == courseId);
                loadMessages();
            }
        }

        function loadMessages() {
            if (!currentCourse) return;

            const endpoint = `{{ url('courses') }}/${currentCourse.id}/messages`;

            fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    allMessages = (data.data && data.data.data) ? data.data.data : (data.data || []);
                    renderMessages();
                    document.getElementById('message-input-section').classList.remove('hidden');
                })
                .catch(e => console.error(e));
        }

        function renderMessages() {
            const history = document.getElementById('messages-history');

            if (allMessages.length === 0) {
                history.innerHTML = `
                    <div class="text-center text-gray-500 py-8">
                        <p>Tidak ada pesan di kelas ini</p>
                        <p class="text-xs mt-2">Mulai percakapan dengan mengirim pesan pertama</p>
                    </div>
                `;
                return;
            }

            history.innerHTML = allMessages.map((message, idx) => {
                const isOwn = message.sender_id == {{ auth()->id() }};
                const time = new Date(message.created_at).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                return `
                    <div class="mb-4 flex ${isOwn ? 'justify-end' : 'justify-start'}">
                        <div class="max-w-xs ${isOwn ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-900'} px-4 py-2 rounded-lg">
                            ${!isOwn ? `<p class="font-semibold text-xs mb-1">${message.sender?.name || 'Pengirim'}</p>` : ''}
                            <p class="text-sm">${message.isi}</p>
                            <p class="text-xs mt-1 opacity-70">${time}</p>
                        </div>
                    </div>
                `;
            }).join('');

            // Scroll to bottom
            history.scrollTop = history.scrollHeight;
        }

        function sendMessage() {
            const isi = document.getElementById('message-input').value.trim();
            if (!isi || !currentCourse) {
                alert('Tulis pesan terlebih dahulu');
                return;
            }

            fetch(`{{ url('courses') }}/${currentCourse.id}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        isi: isi
                    })
                })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('message-input').value = '';
                    loadMessages();
                })
                .catch(e => alert('Error: ' + e));
        }

        document.addEventListener('DOMContentLoaded', loadCourses);

        // Auto-refresh messages every 3 seconds
        setInterval(() => {
            if (currentCourse) loadMessages();
        }, 3000);
    </script>
</x-app-layout>
