<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Riwayat Pengumpulan</h2>
    </x-slot>
    <div class="min-h-screen bg-slate-50 py-8 sm:py-12" x-data="submissionPage()" x-init="load()">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-slate-950 p-6 text-white shadow-sm sm:p-8">
                <p class="text-sm font-semibold text-cyan-300">Ruang pengumpulan</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">Kirim jawaban tugas</h1>
                <p class="mt-2 text-sm text-slate-300">Pilih tugas, periksa tenggat, lalu unggah dokumen jawaban Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[minmax(0,0.85fr)_minmax(0,1.15fr)]">
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-5">
                        <h2 class="font-semibold text-slate-900">Unggah jawaban</h2>
                        <p class="mt-1 text-xs text-slate-500">Format PDF atau PNG, maksimal 10 MB.</p>
                    </div>
                    <form @submit.prevent="submit()" class="space-y-5">
                        <div>
                            <label for="assignment" class="mb-2 block text-sm font-medium text-slate-700">Tugas</label>
                            <select id="assignment" x-model="form.assignmentId" @change="selectAssignment()" required
                                class="w-full rounded-lg border-slate-300 text-sm focus:border-cyan-500 focus:ring-cyan-500">
                                <option value="">Pilih tugas</option>
                                <template x-for="assignment in assignments" :key="assignment.id">
                                    <option :value="assignment.id"
                                        x-text="`${assignment.judul} · ${assignment.course_name}`"></option>
                                </template>
                            </select>
                        </div>
                        <template x-if="selected">
                            <div class="rounded-lg bg-amber-50 p-4 text-sm text-amber-800">
                                Tenggat: <strong x-text="formatDate(selected.tenggat_waktu)"></strong>
                            </div>
                        </template>
                        <div>
                            <label for="file_jawaban" class="mb-2 block text-sm font-medium text-slate-700">Dokumen
                                jawaban</label>
                            <input id="file_jawaban" type="file" accept=".pdf,.png,application/pdf,image/png"
                                @change="form.file = $event.target.files[0]" required
                                class="block w-full rounded-lg border border-slate-300 text-sm text-slate-600 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                        </div>
                        <button type="submit" :disabled="loading"
                            class="inline-flex w-full items-center justify-center rounded-lg bg-cyan-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-cyan-800 disabled:cursor-wait disabled:opacity-60">
                            <span
                                x-text="loading ? 'Mengunggah...' : (selectedSubmission ? 'Ganti jawaban' : 'Unggah jawaban')"></span>
                        </button>
                    </form>
                </section>

                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="font-semibold text-slate-900">Riwayat pengumpulan</h2>
                        <p class="mt-1 text-xs text-slate-500">Status jawaban yang sudah dikirim</p>
                    </div>
                    <div x-show="loadingData" class="px-5 py-10 text-sm text-slate-500">Memuat data pengumpulan...</div>
                    <div x-show="!loadingData && submissions.length === 0" class="px-5 py-10 text-sm text-slate-500">
                        Belum ada pengumpulan tugas.</div>
                    <div x-show="!loadingData && submissions.length > 0" class="divide-y divide-slate-100">
                        <template x-for="submission in submissions" :key="submission.id">
                            <article
                                class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <p class="truncate font-medium text-slate-900"
                                        x-text="submission.assignment?.judul || 'Tugas'"></p>
                                    <p class="mt-1 text-xs text-slate-500"
                                        x-text="submission.assignment?.course?.nama || 'Mata kuliah' "></p>
                                </div>
                                <div class="flex shrink-0 items-center gap-3">
                                    <span
                                        class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Terkirim</span>
                                    <span class="text-sm font-semibold text-slate-700"
                                        x-text="submission.nilai !== null ? `Nilai: ${submission.nilai}` : 'Belum dinilai'"></span>
                                    <time class="text-xs text-slate-400"
                                        x-text="formatDate(submission.created_at)"></time>
                                </div>
                                <p x-show="submission.feedback" class="text-xs text-slate-500 sm:col-span-2"
                                    x-text="`Feedback: ${submission.feedback}`"></p>
                            </article>
                        </template>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        function submissionPage() {
            return {
                assignments: [],
                submissions: [],
                selected: null,
                selectedSubmission: null,
                loading: false,
                loadingData: true,
                form: {
                    assignmentId: '',
                    file: null
                },
                async load() {
                    try {
                        const coursesResponse = await fetch('{{ route('courses.index') }}', {
                            headers: {
                                Accept: 'application/json'
                            }
                        });
                        const coursesPayload = await coursesResponse.json();
                        const courses = coursesPayload.data || [];
                        const responses = await Promise.all(courses.map(course => fetch(
                            `/courses/${course.id}/assignments`, {
                                headers: {
                                    Accept: 'application/json'
                                }
                            }).then(response => response.json())));
                        this.assignments = responses.flatMap(payload => payload.data || []).map(assignment => {
                            const course = courses.find(item => item.id === assignment.course_id);
                            return {
                                ...assignment,
                                course_name: course?.nama || 'Mata kuliah'
                            };
                        });
                        const submissionsResponse = await fetch('{{ route('mahasiswa.submissions') }}', {
                            headers: {
                                Accept: 'application/json'
                            }
                        });
                        const submissionsPayload = await submissionsResponse.json();
                        this.submissions = submissionsPayload.data || [];
                    } catch (error) {
                        window.showToast('Data pengumpulan gagal dimuat.', 'error');
                    } finally {
                        this.loadingData = false;
                    }
                },
                selectAssignment() {
                    this.selected = this.assignments.find(item => item.id == this.form.assignmentId) || null;
                    this.selectedSubmission = this.submissions.find(item => item.assignment_id == this.form.assignmentId) ||
                        null;
                },
                formatDate(value) {
                    return value ? new Date(value).toLocaleString('id-ID', {
                        dateStyle: 'medium',
                        timeStyle: 'short'
                    }) : '-';
                },
                async submit() {
                    if (!this.form.assignmentId || !this.form.file) return window.showToast(
                        'Pilih tugas dan file jawaban.', 'error');
                    this.loading = true;
                    const body = new FormData();
                    body.append('file_jawaban', this.form.file);
                    const endpoint = this.selectedSubmission ?
                        `/assignments/${this.form.assignmentId}/submissions/${this.selectedSubmission.id}` :
                        `/assignments/${this.form.assignmentId}/submissions`;
                    if (this.selectedSubmission) body.append('_method', 'PUT');
                    try {
                        const response = await fetch(endpoint, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                Accept: 'application/json'
                            },
                            body
                        });
                        const payload = await response.json();
                        if (!response.ok) throw new Error(payload.message || Object.values(payload.errors || {}).flat()[
                            0] || 'File gagal diunggah.');
                        window.showToast(payload.message || 'Jawaban berhasil diunggah.');
                        this.form.file = null;
                        document.getElementById('file_jawaban').value = '';
                        await this.load();
                        this.selectAssignment();
                    } catch (error) {
                        window.showToast(error.message, 'error');
                    } finally {
                        this.loading = false;
                    }
                }
            };
        }
    </script>
</x-app-layout>
