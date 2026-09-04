<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Tambah Mahasiswa</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.mahasiswa.store') }}"
                class="space-y-6 bg-white p-6 shadow-sm sm:rounded-lg">
                @csrf
                <div><x-input-label for="name" value="Nama Mahasiswa" /><x-text-input id="name" name="name"
                        class="mt-1 block w-full" value="{{ old('name') }}" required autofocus /><x-input-error
                        :messages="$errors->get('name')" class="mt-2" /></div>
                <div><x-input-label for="email" value="Email" /><x-text-input id="email" name="email"
                        type="email" class="mt-1 block w-full" value="{{ old('email') }}" required /><x-input-error
                        :messages="$errors->get('email')" class="mt-2" /></div>
                <div><x-input-label for="nomor_induk" value="NIM" /><x-text-input id="nomor_induk" name="nomor_induk"
                        class="mt-1 block w-full" value="{{ old('nomor_induk') }}" required /><x-input-error
                        :messages="$errors->get('nomor_induk')" class="mt-2" /></div>
                <div><x-input-label for="password" value="Password" /><x-text-input id="password" name="password"
                        type="password" class="mt-1 block w-full" required /><x-input-error :messages="$errors->get('password')"
                        class="mt-2" /></div>
                <div class="flex justify-end gap-3"><a href="{{ route('admin.mahasiswa.index') }}"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700">Batal</a><x-primary-button>Simpan
                        Mahasiswa</x-primary-button></div>
            </form>
        </div>
    </div>
</x-app-layout>
