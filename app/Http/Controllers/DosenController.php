<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDosenRequest;
use App\Http\Requests\UpdateDosenRequest;
use App\Models\User;
use App\Services\DosenService;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function __construct(private DosenService $service) {}

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

    public function store(StoreDosenRequest $request)
    {
        $this->authorize('create', User::class);

        $dosen = $this->service->create($request->validated());

        return response()->json(['message' => 'Dosen berhasil dibuat.', 'data' => $dosen], 201);
    }

    public function edit(User $dosen)
    {
        $this->authorize('update', $dosen);

        return response()->json(['data' => $dosen]);
    }

    public function update(UpdateDosenRequest $request, User $dosen)
    {
        $this->authorize('update', $dosen);

        $updated = $this->service->update($dosen, $request->validated());

        return response()->json(['message' => 'Dosen berhasil diperbarui.', 'data' => $updated]);
    }

    public function destroy(User $dosen)
    {
        $this->authorize('delete', $dosen);

        $this->service->delete($dosen);

        return response()->json(['message' => 'Dosen berhasil dihapus.']);
    }

    public function approve(User $dosen)
    {
        $this->authorize('create', User::class);

        if ($dosen->role !== 'dosen') {
            return response()->json(['message' => 'User adalah bukan dosen.'], 400);
        }

        $dosen->update(['status_akun' => 'aktif']);

        return response()->json(['message' => 'Akun dosen berhasil disetujui.', 'data' => $dosen]);
    }

    public function reject(User $dosen, Request $request)
    {
        $this->authorize('create', User::class);

        if ($dosen->role !== 'dosen') {
            return response()->json(['message' => 'User adalah bukan dosen.'], 400);
        }

        $dosen->update(['status_akun' => 'ditolak']);

        return response()->json(['message' => 'Akun dosen berhasil ditolak.', 'data' => $dosen]);
    }
}
