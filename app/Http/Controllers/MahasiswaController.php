<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\User;
use App\Services\MahasiswaService;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function __construct(private MahasiswaService $service) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        return response()->json([
            'data' => $this->service->paginate($request->query('search')),
        ]);
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return response()->json(['data' => null]);
    }

    public function store(StoreMahasiswaRequest $request)
    {
        $this->authorize('create', User::class);

        $mahasiswa = $this->service->create($request->validated());

        return response()->json(['message' => 'Mahasiswa berhasil dibuat.', 'data' => $mahasiswa], 201);
    }

    public function edit(User $mahasiswa)
    {
        $this->authorize('update', $mahasiswa);

        return response()->json(['data' => $mahasiswa]);
    }

    public function update(UpdateMahasiswaRequest $request, User $mahasiswa)
    {
        $this->authorize('update', $mahasiswa);

        $updated = $this->service->update($mahasiswa, $request->validated());

        return response()->json(['message' => 'Mahasiswa berhasil diperbarui.', 'data' => $updated]);
    }

    public function destroy(User $mahasiswa)
    {
        $this->authorize('delete', $mahasiswa);

        $this->service->delete($mahasiswa);

        return response()->json(['message' => 'Mahasiswa berhasil dihapus.']);
    }

    public function courses(User $mahasiswa)
    {
        $this->authorize('viewCourses', $mahasiswa);

        return response()->json([
            'data' => $this->service->courses($mahasiswa),
        ]);
    }

    public function approve(User $mahasiswa)
    {
        $this->authorize('create', User::class);

        if ($mahasiswa->role !== 'mahasiswa') {
            return response()->json(['message' => 'User adalah bukan mahasiswa.'], 400);
        }

        $mahasiswa->update(['status_akun' => 'aktif']);

        return response()->json(['message' => 'Akun mahasiswa berhasil disetujui.', 'data' => $mahasiswa]);
    }

    public function reject(User $mahasiswa, Request $request)
    {
        $this->authorize('create', User::class);

        if ($mahasiswa->role !== 'mahasiswa') {
            return response()->json(['message' => 'User adalah bukan mahasiswa.'], 400);
        }

        $mahasiswa->update(['status_akun' => 'ditolak']);

        return response()->json(['message' => 'Akun mahasiswa berhasil ditolak.', 'data' => $mahasiswa]);
    }
}
