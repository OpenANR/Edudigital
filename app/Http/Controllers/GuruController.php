<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Score;
use App\Models\TeachingSchedule;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuruController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $subjectsCount = Subject::count();
        $classrooms = Classroom::all();
        $classStats = [];
        $totalCompletedClasses = 0;
        $teacherClassesCount = 0;
        $teacherCompletedClassesCount = 0;

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

            if ($percentage > 100) {
                $percentage = 100;
            }

            $isComplete = $percentage >= 100;
            if ($isComplete) {
                $totalCompletedClasses++;
            }

            $isTeacherClass = $class->user_id == $user->id;
            if ($isTeacherClass) {
                $teacherClassesCount++;
                if ($isComplete) {
                    $teacherCompletedClassesCount++;
                }
            }

            $classStats[] = [
                'id' => $class->id,
                'name' => $class->nama_kelas,
                'students_count' => $studentsCount,
                'actual_scores' => $actual,
                'expected_scores' => $expected,
                'percentage' => $percentage,
                'is_complete' => $isComplete,
                'is_teacher_class' => $isTeacherClass
            ];
        }

        $totalClasses = $classrooms->count();

        return view('roleView.guru.dashboard', compact(
            'classStats', 'totalCompletedClasses', 'totalClasses',
            'teacherClassesCount', 'teacherCompletedClassesCount'
        ));
    }

    public function manageScore(Request $request)
    {
        $user = Auth::user();

        // Retrieve classrooms where user_id matches the teacher's ID
        $classrooms = Classroom::where('user_id', $user->id)->get();

        // Retrieve all subjects
        $subjects = Subject::all();

        $classroomId = $request->input('classroom_id');
        $subjectId = $request->input('subject_id');

        $students = collect();
        $selectedSubject = null;

        // Fetch settings and check if open
        $settings = Settings::all()->pluck('nilai_pengaturan', 'kunci_pengaturan');
        $now = Carbon::now();

        $isTugasOpen = true;
        if (isset($settings['buka_tugas_asts']) && isset($settings['tutup_tugas_asts'])) {
            $buka = Carbon::parse($settings['buka_tugas_asts']);
            $tutup = Carbon::parse($settings['tutup_tugas_asts']);
            $isTugasOpen = $now->between($buka, $tutup);
        }

        $isAsasOpen = true;
        if (isset($settings['buka_asas']) && isset($settings['tutup_asas'])) {
            $buka = Carbon::parse($settings['buka_asas']);
            $tutup = Carbon::parse($settings['tutup_asas']);
            $isAsasOpen = $now->between($buka, $tutup);
        }

        if ($classroomId && $classroomId !== '#' && $subjectId && $subjectId !== '#') {
            // Validate that this classroom belongs to this teacher
            $isValidClassroom = $classrooms->where('id', $classroomId)->isNotEmpty();

            if ($isValidClassroom) {
                $students = Student::where('class_id', $classroomId)->with(['scores' => function($query) use ($subjectId) {
                    $query->where('subject_id', $subjectId);
                }])->get();

                $selectedSubject = Subject::find($subjectId);
            } else {
                return redirect()->route('guru.manageScore')->with('error', 'Anda tidak mengampu kelas ini!');
            }
        }

        return view('roleView.guru.inputNilai', compact(
            'classrooms', 'subjects', 'classroomId', 'subjectId', 'students', 'selectedSubject', 
            'isTugasOpen', 'isAsasOpen'
        ));
    }

    public function saveScore(Request $request)
    {
        $user = Auth::user();
        $subjectId = $request->input('subject_id');
        $scoresData = $request->input('scores');
        $mode = $request->input('mode', 'FastTrack');

        if (!$subjectId || $subjectId === '#' || !Subject::where('id', $subjectId)->exists()) {
            return redirect()->back()->with('error', 'Mata pelajaran tidak valid!');
        }

        if (!$scoresData) {
            return redirect()->back()->with('error', 'Tidak ada data nilai yang dikirim !');
        }

        // Validate that the classroom of these students is supervised by this teacher
        $firstStudentId = key($scoresData);
        $student = Student::find($firstStudentId);
        if (!$student) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan!');
        }

        $classroomId = $student->class_id;
        $isValidClassroom = Classroom::where('id', $classroomId)
            ->where('user_id', $user->id)
            ->exists();

        if (!$isValidClassroom) {
            return redirect()->back()->with('error', 'Anda tidak berwenang menginput nilai untuk kelas ini!');
        }

        // Fetch settings and check if open
        $settings = Settings::all()->pluck('nilai_pengaturan', 'kunci_pengaturan');
        $now = Carbon::now();

        $isTugasOpen = true;
        if (isset($settings['buka_tugas_asts']) && isset($settings['tutup_tugas_asts'])) {
            $buka = Carbon::parse($settings['buka_tugas_asts']);
            $tutup = Carbon::parse($settings['tutup_tugas_asts']);
            $isTugasOpen = $now->between($buka, $tutup);
        }

        $isAsasOpen = true;
        if (isset($settings['buka_asas']) && isset($settings['tutup_asas'])) {
            $buka = Carbon::parse($settings['buka_asas']);
            $tutup = Carbon::parse($settings['tutup_asas']);
            $isAsasOpen = $now->between($buka, $tutup);
        }

        // If both are locked, reject save
        if (!$isTugasOpen && !$isAsasOpen) {
            return redirect()->back()->with('error', 'Akses pengisian nilai ditutup! Anda tidak dapat memperbarui nilai.');
        }

        $subject = Subject::find($subjectId);
        $isRegular = ($subject->kelompok_mapel === 'regular');
        $isMath = stripos($subject->nama_mapel, 'matematika') !== false;

        foreach ($scoresData as $studentId => $grades) {
            // Retrieve or initialize the score record
            $scoreRecord = Score::firstOrNew([
                'student_id' => $studentId,
                'subject_id' => $subjectId,
            ]);

            // Set variables to existing db values or new input values based on lock status
            $t1 = $scoreRecord->tugas1 ?? 0;
            $t2 = $scoreRecord->tugas2 ?? 0;
            $asts = $scoreRecord->asts ?? 0;
            $t4 = $scoreRecord->tugas4 ?? 0;
            $t5 = $scoreRecord->tugas5 ?? 0;

            if ($isTugasOpen) {
                $t1 = isset($grades['tugas1']) && $grades['tugas1'] !== '' ? max(0, (int) $grades['tugas1']) : 0;
                $t2 = isset($grades['tugas2']) && $grades['tugas2'] !== '' ? max(0, (int) $grades['tugas2']) : 0;
                $asts = isset($grades['asts']) && $grades['asts'] !== '' ? max(0, (int) $grades['asts']) : 0;
                $t4 = isset($grades['tugas4']) && $grades['tugas4'] !== '' ? max(0, (int) $grades['tugas4']) : 0;
                $t5 = isset($grades['tugas5']) && $grades['tugas5'] !== '' ? max(0, (int) $grades['tugas5']) : 0;

                $scoreRecord->tugas1 = isset($grades['tugas1']) && $grades['tugas1'] !== '' ? $grades['tugas1'] : null;
                $scoreRecord->tugas2 = isset($grades['tugas2']) && $grades['tugas2'] !== '' ? $grades['tugas2'] : null;
                $scoreRecord->asts = isset($grades['asts']) && $grades['asts'] !== '' ? $grades['asts'] : null;
                $scoreRecord->tugas4 = isset($grades['tugas4']) && $grades['tugas4'] !== '' ? $grades['tugas4'] : null;
                $scoreRecord->tugas5 = isset($grades['tugas5']) && $grades['tugas5'] !== '' ? $grades['tugas5'] : null;
            }

            if ($isRegular) {
                $maxPG = $isMath ? 25 : 30;
                $bobotPG = $isMath ? 2.4 : 2;

                $finalPG = $scoreRecord->pg_asas !== null && is_numeric($scoreRecord->pg_asas) ? (int) $scoreRecord->pg_asas * $bobotPG : 0;
                // If it's a string like 'benar semua', handle it
                if ($scoreRecord->pg_asas !== null && !is_numeric($scoreRecord->pg_asas)) {
                    $pgLower = strtolower($scoreRecord->pg_asas);
                    if ($pgLower === 'benar semua') {
                        $finalPG = $maxPG * $bobotPG;
                    }
                }
                $finalES = $scoreRecord->nilai_essai ?? 0;
                $murniAsas = $scoreRecord->murni_asas_genap ?? 0;
                $perbaikan = $scoreRecord->perbaikan;

                if ($isAsasOpen) {
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

                    $scoreRecord->pg_asas = $pgInputRaw !== '' ? $pgInputRaw : null;
                    $scoreRecord->n1 = isset($grades['n1']) ? (int) $grades['n1'] : null;
                    $scoreRecord->n2 = isset($grades['n2']) ? (int) $grades['n2'] : null;
                    $scoreRecord->n3 = isset($grades['n3']) ? (int) $grades['n3'] : null;
                    $scoreRecord->n4 = isset($grades['n4']) ? (int) $grades['n4'] : null;
                    $scoreRecord->n5 = isset($grades['n5']) ? (int) $grades['n5'] : null;
                    $scoreRecord->nilai_essai = $finalES;
                    $scoreRecord->murni_asas_genap = $murniAsas;
                    $scoreRecord->perbaikan = $perbaikan;
                }

                $asas = $perbaikan !== null ? $perbaikan : $murniAsas;
                $scoreRecord->asas = $asas;

                $nilaiAkhir = (int) round(($t1 + $t2 + $asts + $t4 + $t5 + $asas) / 6);
                $status = ($nilaiAkhir >= 75) ? 'tuntas' : 'belum';

                $scoreRecord->nilai_akhir = $nilaiAkhir;
                $scoreRecord->status = $status;
                $scoreRecord->save();
            } else {
                // Praktik
                $murniAsas = $scoreRecord->murni_asas_genap ?? 0;

                if ($isAsasOpen) {
                    $murniAsas = isset($grades['murni_asas_genap']) && $grades['murni_asas_genap'] !== '' ? max(0, (int) $grades['murni_asas_genap']) : 0;

                    $scoreRecord->pg_asas = null;
                    $scoreRecord->n1 = null;
                    $scoreRecord->n2 = null;
                    $scoreRecord->n3 = null;
                    $scoreRecord->n4 = null;
                    $scoreRecord->n5 = null;
                    $scoreRecord->nilai_essai = null;
                    $scoreRecord->murni_asas_genap = $murniAsas;
                    $scoreRecord->perbaikan = null;
                }

                $asas = $murniAsas;
                $scoreRecord->asas = $asas;

                $nilaiAkhir = (int) round(($t1 + $t2 + $asts + $t4 + $t5 + $asas) / 6);
                $status = ($nilaiAkhir >= 75) ? 'tuntas' : 'belum';

                $scoreRecord->nilai_akhir = $nilaiAkhir;
                $scoreRecord->status = $status;
                $scoreRecord->save();
            }
        }

        return redirect()->back()->with('success', 'Seluruh data nilai berhasil diperbarui.');
    }
}
