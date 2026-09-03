<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Mahasiswa</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-sm text-gray-500">Mata Kuliah Diikuti</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $courses->count() }}</p>
                </div>
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-sm text-gray-500">Tugas Terdekat</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $assignments->count() }}</p>
                </div>
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-sm text-gray-500">Nilai Terakhir</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $nilai_terakhir->count() }}</p>
                </div>
            </div>

            <div class="mt-6 bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900">Mata Kuliah Saya</h3>
                @if ($courses->isEmpty())
                    <p class="mt-4 text-gray-500">Belum ada mata kuliah yang diikuti.</p>
                @else
                    <ul class="mt-4 divide-y divide-gray-200">
                        @foreach ($courses as $course)
                            <li class="py-3 text-gray-700">{{ $course->nama }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
