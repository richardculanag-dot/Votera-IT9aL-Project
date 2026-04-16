<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'staff')->with('department');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $staff = $query->orderBy('name')->paginate(20);

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.staff.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password'),
            'role' => 'staff',
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff account created.');
    }

    public function edit(User $staff)
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.staff.edit', compact('staff', 'departments'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
        ]);

        if ($request->password) {
            $staff->update(['password' => bcrypt($request->password)]);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff account updated.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff account removed.');
    }
}