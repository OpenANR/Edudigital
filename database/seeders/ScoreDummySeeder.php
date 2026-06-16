<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Score;
use App\Models\Classroom;

class ScoreDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = Subject::all();
        $classrooms = Classroom::all();

        // Target completion rates for different classrooms to make dashboard charts interesting
        $completionRates = [
            1 => 1.0,  // XI TKP (100% complete)
            2 => 1.0,  // XI TITL (100% complete)
            3 => 1.0,  // XI TPM (100% complete)
            4 => 0.8,  // XI TKR 1 (80% complete)
            5 => 0.65, // XI TKR 2 (65% complete)
            6 => 1.0,  // XI TKR 3 (100% complete)
            7 => 1.0,  // XI RPL (100% complete)
            8 => 0.45, // XI TSM 1 (45% complete)
            9 => 0.2,  // XI TSM 2 (20% complete)
            10 => 0.0, // XI TSM 3 (0% complete)
            11 => 1.0, // XI TAB 1 (100% complete)
            12 => 0.85,// XI TAB 2 (85% complete)
            13 => 0.15,// XI LPKC (15% complete)
        ];

        // Clear existing scores
        Score::truncate();

        foreach ($classrooms as $class) {
            $students = Student::where('class_id', $class->id)->get();
            $rate = $completionRates[$class->id] ?? 0.5;

            foreach ($students as $student) {
                foreach ($subjects as $subject) {
                    // Decide whether to seed scores for this student-subject combination based on rate
                    if (mt_rand(1, 100) / 100 <= $rate) {
                        $isRegular = ($subject->kelompok_mapel === 'regular');
                        $isMath = stripos($subject->nama_mapel, 'matematika') !== false;

                        $tugas1 = rand(70, 95);
                        $tugas2 = rand(70, 95);
                        $asts = rand(65, 90);
                        $tugas4 = rand(70, 95);
                        $tugas5 = rand(70, 95);

                        if ($isRegular) {
                            $maxPG = $isMath ? 25 : 30;
                            $bobotPG = $isMath ? 2.4 : 2;
                            $countPG = rand(15, $maxPG);
                            $finalPG = $countPG * $bobotPG;

                            $validOptions = [0, 2, 4, 8];
                            $n1 = $validOptions[array_rand($validOptions)];
                            $n2 = $validOptions[array_rand($validOptions)];
                            $n3 = $validOptions[array_rand($validOptions)];
                            $n4 = $validOptions[array_rand($validOptions)];
                            $n5 = $validOptions[array_rand($validOptions)];

                            $finalES = $n1 + $n2 + $n3 + $n4 + $n5;
                            if ($finalES > 40) $finalES = 40;

                            $murniAsas = (int) round($finalPG + $finalES);
                            if ($murniAsas > 100) $murniAsas = 100;
                            
                            $perbaikan = null;
                            if ($murniAsas < 75 && rand(0, 1) === 1) {
                                $perbaikan = rand(75, 88);
                            }

                            $asas = $perbaikan !== null ? $perbaikan : $murniAsas;
                            $nilaiAkhir = (int) round(($tugas1 + $tugas2 + $asts + $tugas4 + $tugas5 + $asas) / 6);
                            $status = ($nilaiAkhir >= 75) ? 'tuntas' : 'belum';

                            Score::create([
                                'student_id' => $student->id,
                                'subject_id' => $subject->id,
                                'tugas1' => $tugas1,
                                'tugas2' => $tugas2,
                                'asts' => $asts,
                                'tugas4' => $tugas4,
                                'tugas5' => $tugas5,
                                'pg_asas' => $countPG,
                                'n1' => $n1,
                                'n2' => $n2,
                                'n3' => $n3,
                                'n4' => $n4,
                                'n5' => $n5,
                                'nilai_essai' => $finalES,
                                'murni_asas_genap' => $murniAsas,
                                'perbaikan' => $perbaikan,
                                'asas' => $asas,
                                'nilai_akhir' => $nilaiAkhir,
                                'status' => $status,
                            ]);
                        } else {
                            // Praktik
                            $murniAsas = rand(70, 95);
                            $asas = $murniAsas;
                            $nilaiAkhir = (int) round(($tugas1 + $tugas2 + $asts + $tugas4 + $tugas5 + $asas) / 6);
                            $status = ($nilaiAkhir >= 75) ? 'tuntas' : 'belum';

                            Score::create([
                                'student_id' => $student->id,
                                'subject_id' => $subject->id,
                                'tugas1' => $tugas1,
                                'tugas2' => $tugas2,
                                'asts' => $asts,
                                'tugas4' => $tugas4,
                                'tugas5' => $tugas5,
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
                            ]);
                        }
                    }
                }
            }
        }
    }
}
