<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Score;
use App\Models\Department;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('roleView.admin.dashboard');
    }

    public function manageDepartment()
    {
        $departments = Department::all();
        return view('roleView.admin.kelolaJurusan', compact('departments'));
    }

    public function manageStudent()
    {
        $student = Student::with(['departments', 'classrooms'])->get();
        return view('roleView.admin.kelolaSiswa', compact('student'));
    }

    public function manageScore(Request $request)
    {
        $classrooms = Classroom::all();
        $subjects = Subject::all();

        $classroomId = $request->input('classroom_id');
        $subjectId = $request->input('subject_id');

        $students = collect();

        if ($classroomId && $classroomId !== '#' && $subjectId && $subjectId !== '#') {
            $students = Student::where('class_id', $classroomId)->with(['scores' => function($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            }])->get();
        }

        return view('roleView.admin.inputNilai', compact('classrooms', 'subjects', 'classroomId', 'subjectId', 'students'));
    }

    public function saveScore(Request $request)
    {
        $subjectId = $request->input('subject_id');
        $scoresData = $request->input('scores');

        if (!$subjectId || $subjectId === '#' || !Subject::where('id', $subjectId)->exists()) {
            return redirect()->back()->with('error', 'Mata pelajaran tidak valid!');
        }

        if(!$scoresData){
            return redirect()->back()->with('error', 'Tidak ada data nilai yang dikirim !');
        }

        foreach ($scoresData as $studentId => $grades){

            $t1 = $grades['tugas1'] ?? 0;
            $t2 = $grades['tugas2'] ?? 0;
            $asts = $grades['asts'] ?? 0;
            $t4 = $grades['tugas4'] ?? 0;
            $t5 = $grades['tugas5'] ?? 0;
            $asas = $grades['asas'] ?? 0;

            $rataTugas = ($t1 + $t2 + $t4 + $t5) / 4;
            $nilaiAkhir = ($rataTugas * 0.4) + ($asts * 0.3) + ($asas * 0.3);
            $nilaiAkhirBulat = round($nilaiAkhir);

            $statusKetuntasan = ($nilaiAkhirBulat >= 75 ) ? 'tuntas' : 'belum';

            Score::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                ],
                [
                    'tugas1' => $t1,
                    'tugas2' => $t2,
                    'asts' => $asts,
                    'tugas4' => $t4,
                    'tugas5' => $t5,
                    'asas' => $asas,
                    'nilai_akhir' => $nilaiAkhirBulat,
                    'status' => $statusKetuntasan,
                ]
            );
        }
        return redirect()->back()->with('success', 'Seluruh data nilai berhasil diperbarui.');
    }
}
