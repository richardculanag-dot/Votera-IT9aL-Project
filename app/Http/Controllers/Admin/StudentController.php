<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->with('department');

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('student_id', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->orderBy('name')->paginate(20);
        $departments = Department::orderBy('name')->get();

        return view('admin.students.index', compact('students', 'departments'));
    }

    public function show(User $student)
    {
        $student->load('department', 'course', 'votes.candidate.position.election');
        return view('admin.students.show', compact('student'));
    }
}