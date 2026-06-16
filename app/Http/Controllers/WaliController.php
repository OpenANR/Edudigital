<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;

class WaliController extends Controller
{
    public function dashboard()
    {
        return view('roleView.waliKelas.dashboard');
    }

    public function viewStudent(Request $request)
    {
        $user = auth()->user();
        
        // Find all classrooms supervised by this Wali Kelas
        $classrooms = Classroom::where('user_id', $user->id)->get();
        
        $classroom = null;
        $classroomId = $request->input('classroom_id');
        $students = collect();
        $subjects = collect();
        
        if ($classrooms->isNotEmpty()) {
            if ($classroomId) {
                $classroom = $classrooms->firstWhere('id', $classroomId);
            }
            // Default to the first classroom if none matches or is specified
            if (!$classroom) {
                $classroom = $classrooms->first();
                $classroomId = $classroom->id;
            }
            
            // Retrieve students in this classroom along with their scores
            $students = Student::where('class_id', $classroom->id)
                ->with(['scores' => function($query) {
                    $query->select('student_id', 'subject_id', 'nilai_akhir');
                }])
                ->get();
                
            // Retrieve all subjects to display as table columns
            $subjects = Subject::all();
        }
        
        return view('roleView.waliKelas.viewStudent', compact('classrooms', 'classroom', 'classroomId', 'students', 'subjects'));
    }
}

