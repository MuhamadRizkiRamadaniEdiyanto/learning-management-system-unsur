<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Course yang Diikuti</h2><span
                class="text-sm text-gray-500">{{ $mahasiswa->name }}</span>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Mata
                                    Kuliah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">Dosen
                                    Pengampu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($courses as $course)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                        {{ $course->kode_matkul }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $course->nama }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $course->dosen?->name ?? '-' }}</td>
                            </tr>@empty<tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">Mahasiswa
                                        belum mengikuti course apa pun.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4"><a href="{{ route('admin.mahasiswa.index') }}"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-800">Kembali ke daftar mahasiswa</a>
            </div>
        </div>
    </div>
</x-app-layout>
