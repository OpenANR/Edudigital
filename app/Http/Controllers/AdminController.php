<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Score;
use App\Models\Department;
use App\Models\Settings;

class AdminController extends Controller
{
    public function dashboard()
    {
        $subjectsCount = Subject::count();
        $classrooms = Classroom::all();
        $classStats = [];
        $totalCompletedClasses = 0;

        foreach ($classrooms as $class) {
            $students = Student::where('class_id', $class->id)->get();
            $studentsCount = $students->count();
            $expected = $studentsCount * $subjectsCount;
            
            if ($expected > 0) {
                $studentIds = $students->pluck('id');
                $actual = Score::whereIn('student_id', $studentIds)->count();
                $percentage = round(($actual / $expected) * 100, 1);
            } else {
                $actual = 0;
                $percentage = 0;
            }

            // Cap at 100 just in case
            if ($percentage > 100) {
                $percentage = 100;
            }

            $isComplete = $percentage >= 100;
            if ($isComplete) {
                $totalCompletedClasses++;
            }

            $classStats[] = [
                'id' => $class->id,
                'name' => $class->nama_kelas,
                'students_count' => $studentsCount,
                'actual_scores' => $actual,
                'expected_scores' => $expected,
                'percentage' => $percentage,
                'is_complete' => $isComplete
            ];
        }

        $totalClasses = $classrooms->count();

        return view('roleView.admin.dashboard', compact('classStats', 'totalCompletedClasses', 'totalClasses'));
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

        $selectedSubject = null;
        if($subjectId){
            $selectedSubject = Subject::find($subjectId);
        }

        // Fetch lock settings
        $settings = Settings::all()->pluck('nilai_pengaturan', 'kunci_pengaturan');

        return view('roleView.admin.inputNilai', compact('classrooms', 'subjects', 'classroomId', 'subjectId', 'students', 'selectedSubject', 'settings'));
    }

    public function saveSettings(Request $request)
    {
        $keys = ['buka_tugas_asts', 'tutup_tugas_asts', 'buka_asas', 'tutup_asas'];

        foreach ($keys as $key) {
            $val = $request->input($key);
            if ($val) {
                Settings::updateOrCreate(
                    ['kunci_pengaturan' => $key],
                    ['nilai_pengaturan' => \Carbon\Carbon::parse($val)]
                );
            } else {
                Settings::where('kunci_pengaturan', $key)->delete();
            }
        }

        return redirect()->back()->with('success', 'Pengaturan akses penilaian berhasil disimpan.');
    }

    public function saveScore(Request $request)
    {
        $subjectId = $request->input('subject_id');
        $scoresData = $request->input('scores');
        $mode = $request->input('mode', 'FastTrack');

        if (!$subjectId || $subjectId === '#' || !Subject::where('id', $subjectId)->exists()) {
            return redirect()->back()->with('error', 'Mata pelajaran tidak valid!');
        }

        if(!$scoresData){
            return redirect()->back()->with('error', 'Tidak ada data nilai yang dikirim !');
        }

        $subject = Subject::find($subjectId);
        $isRegular = ($subject->kelompok_mapel === 'regular');
        $isMath = stripos($subject->nama_mapel, 'matematika') !== false;

        foreach ($scoresData as $studentId => $grades){
            $t1 = isset($grades['tugas1']) && $grades['tugas1'] !== '' ? max(0, (int) $grades['tugas1']) : 0;
            $t2 = isset($grades['tugas2']) && $grades['tugas2'] !== '' ? max(0, (int) $grades['tugas2']) : 0;
            $asts = isset($grades['asts']) && $grades['asts'] !== '' ? max(0, (int) $grades['asts']) : 0;
            $t4 = isset($grades['tugas4']) && $grades['tugas4'] !== '' ? max(0, (int) $grades['tugas4']) : 0;
            $t5 = isset($grades['tugas5']) && $grades['tugas5'] !== '' ? max(0, (int) $grades['tugas5']) : 0;

            if ($isRegular) {
                $maxPG = $isMath ? 25 : 30;
                $bobotPG = $isMath ? 2.4 : 2;

                $pgInputRaw = isset($grades['pg_asas']) ? trim($grades['pg_asas']) : '';
                $pgInput = strtolower($pgInputRaw);
                $finalPG = 0;

                if ($pgInput === 'benar semua') {
                    $finalPG = $maxPG * $bobotPG;
                } elseif ($pgInput === 'salah semua' || $pgInput === '') {
                    $finalPG = 0;
                } elseif ($mode === 'FastTrack') {
                    $countPG = (int) $pgInput;
                    if ($countPG > $maxPG) $countPG = $maxPG;
                    if ($countPG < 0) $countPG = 0;
                    $finalPG = $countPG * $bobotPG;
                } else {
                    $parts = array_filter(explode(',', $pgInputRaw), function($val) {
                        return trim($val) !== '';
                    });
                    $countPG = count($parts);
                    if ($countPG > $maxPG) $countPG = $maxPG;

                    if ($mode === 'Benar') {
                        $finalPG = $countPG * $bobotPG;
                    } elseif ($mode === 'Salah') {
                        $finalPG = ($maxPG - $countPG) * $bobotPG;
                    }
                }

                $n1 = isset($grades['n1']) && $grades['n1'] !== '' ? (int) $grades['n1'] : 0;
                $n2 = isset($grades['n2']) && $grades['n2'] !== '' ? (int) $grades['n2'] : 0;
                $n3 = isset($grades['n3']) && $grades['n3'] !== '' ? (int) $grades['n3'] : 0;
                $n4 = isset($grades['n4']) && $grades['n4'] !== '' ? (int) $grades['n4'] : 0;
                $n5 = isset($grades['n5']) && $grades['n5'] !== '' ? (int) $grades['n5'] : 0;

                $finalES = $n1 + $n2 + $n3 + $n4 + $n5;
                if ($finalES > 40) $finalES = 40;

                $murniAsas = (int) round($finalPG + $finalES);
                if ($murniAsas > 100) $murniAsas = 100;
                if ($murniAsas < 0) $murniAsas = 0;

                $perbaikan = isset($grades['perbaikan']) && $grades['perbaikan'] !== '' ? max(0, (int) $grades['perbaikan']) : null;
                $asas = $perbaikan !== null ? $perbaikan : $murniAsas;

                $nilaiAkhir = (int) round(($t1 + $t2 + $asts + $t4 + $t5 + $asas) / 6);
                $status = ($nilaiAkhir >= 75) ? 'tuntas' : 'belum';

                Score::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'tugas1' => isset($grades['tugas1']) && $grades['tugas1'] !== '' ? $grades['tugas1'] : null,
                        'tugas2' => isset($grades['tugas2']) && $grades['tugas2'] !== '' ? $grades['tugas2'] : null,
                        'asts' => isset($grades['asts']) && $grades['asts'] !== '' ? $grades['asts'] : null,
                        'tugas4' => isset($grades['tugas4']) && $grades['tugas4'] !== '' ? $grades['tugas4'] : null,
                        'tugas5' => isset($grades['tugas5']) && $grades['tugas5'] !== '' ? $grades['tugas5'] : null,
                        'pg_asas' => $pgInputRaw !== '' ? $pgInputRaw : null,
                        'n1' => isset($grades['n1']) ? (int) $grades['n1'] : null,
                        'n2' => isset($grades['n2']) ? (int) $grades['n2'] : null,
                        'n3' => isset($grades['n3']) ? (int) $grades['n3'] : null,
                        'n4' => isset($grades['n4']) ? (int) $grades['n4'] : null,
                        'n5' => isset($grades['n5']) ? (int) $grades['n5'] : null,
                        'nilai_essai' => $finalES,
                        'murni_asas_genap' => $murniAsas,
                        'perbaikan' => $perbaikan,
                        'asas' => $asas,
                        'nilai_akhir' => $nilaiAkhir,
                        'status' => $status,
                    ]
                );
            } else {
                // Praktik
                $murniAsas = isset($grades['murni_asas_genap']) && $grades['murni_asas_genap'] !== '' ? max(0, (int) $grades['murni_asas_genap']) : 0;
                $asas = $murniAsas;

                $nilaiAkhir = (int) round(($t1 + $t2 + $asts + $t4 + $t5 + $asas) / 6);
                $status = ($nilaiAkhir >= 75) ? 'tuntas' : 'belum';

                Score::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'tugas1' => isset($grades['tugas1']) && $grades['tugas1'] !== '' ? $grades['tugas1'] : null,
                        'tugas2' => isset($grades['tugas2']) && $grades['tugas2'] !== '' ? $grades['tugas2'] : null,
                        'asts' => isset($grades['asts']) && $grades['asts'] !== '' ? $grades['asts'] : null,
                        'tugas4' => isset($grades['tugas4']) && $grades['tugas4'] !== '' ? $grades['tugas4'] : null,
                        'tugas5' => isset($grades['tugas5']) && $grades['tugas5'] !== '' ? $grades['tugas5'] : null,
                        'pg_asas' => null,
                        'n1' => null,
                        'n2' => null,
                        'n3' => null,
                        'n4' => null,
                        'n5' => null,
                        'nilai_essai' => null,
                        'murni_asas_genap' => $murniAsas,
                        'perbaikan' => null,
                        'asas' => $asas,
                        'nilai_akhir' => $nilaiAkhir,
                        'status' => $status,
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Seluruh data nilai berhasil diperbarui.');
    }
}
