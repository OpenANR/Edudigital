<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
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
}
