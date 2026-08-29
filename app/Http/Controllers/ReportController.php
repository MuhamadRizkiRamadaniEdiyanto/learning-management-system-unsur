<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function jumlahMahasiswaPerMatkul()
    {
        $this->authorize('manage', \App\Models\User::class);

        return response()->json(['data' => $this->service->jumlahMahasiswaPerMatkul()]);
    }

    public function rekapNilaiPerMatkul(Course $course)
    {
        $this->authorize('manage', \App\Models\User::class);

        return response()->json(['data' => $this->service->rekapNilaiPerMatkul($course->id)]);
    }

    public function rekapPengumpulanTugas(Assignment $assignment)
    {
        $this->authorize('manage', \App\Models\User::class);

        return response()->json(['data' => $this->service->rekapPengumpulanTugas($assignment->id)]);
    }

    public function bebanMengajarDosen()
    {
        $this->authorize('manage', \App\Models\User::class);

        return response()->json(['data' => $this->service->bebanMengajarDosen()]);
    }

    public function export(Request $request, string $jenis)
    {
        $this->authorize('manage', \App\Models\User::class);

        $data = match ($jenis) {
            'mahasiswa-per-matkul' => $this->service->jumlahMahasiswaPerMatkul(),
            'nilai-per-matkul' => $this->service->rekapNilaiPerMatkul($request->query('course_id')),
            'pengumpulan-tugas' => $this->service->rekapPengumpulanTugas($request->query('assignment_id')),
            'beban-mengajar' => $this->service->bebanMengajarDosen(),
            default => abort(404, 'Jenis laporan tidak valid.'),
        };

        $csv = "jenis,detail\n";
        $csv .= json_encode($data, JSON_THROW_ON_ERROR);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $jenis . '.csv"',
        ]);
    }
}
