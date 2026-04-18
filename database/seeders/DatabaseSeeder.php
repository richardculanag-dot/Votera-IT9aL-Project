<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use App\Models\Election;
use App\Models\User;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => 'admin@votera.edu'], [
            'name'       => 'System Admin',
            'email'      => 'admin@votera.edu',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'student_id' => null,
        ]);

        User::updateOrCreate(['email' => 'staff@votera.edu'], [
            'name'       => 'Staff Member',
            'email'      => 'staff@votera.edu',
            'password'   => Hash::make('password'),
            'role'       => 'staff',
            'student_id' => null,
        ]);

        $departments = [
            'CCE' => [
                'name' => 'College of Computing Education',
                'courses' => ['BS Information Technology', 'BS Computer Science', 'BS Data Science'],
            ],
            'CEE' => [
                'name' => 'College of Engineering Education',
                'courses' => ['BS Civil Engineering', 'BS Electrical Engineering', 'BS Mechanical Engineering'],
            ],
            'CCJE' => [
                'name' => 'College of Criminal Justice Education',
                'courses' => ['BS Criminology', 'BS Criminal Justice', 'BS Forensic Science'],
            ],
            'CTE' => [
                'name' => 'College of Teacher Education',
                'courses' => ['BS Elementary Education', 'BS Secondary Education', 'BS Special Education'],
            ],
            'CASE' => [
                'name' => 'College of Arts and Social Sciences Education',
                'courses' => ['BA Communication', 'BA Psychology', 'BS Social Work'],
            ],
        ];

        $coursesByDepartment = [];
        foreach ($departments as $code => $data) {
            $department = Department::updateOrCreate(['code' => $code], [
                'name' => $data['name'],
            ]);

            $coursesByDepartment[$code] = [];
            foreach ($data['courses'] as $courseName) {
                $course = Course::updateOrCreate(
                    ['department_id' => $department->id, 'name' => $courseName],
                    ['department_id' => $department->id, 'name' => $courseName]
                );
                $coursesByDepartment[$code][] = $course;
            }
        }

        $studentTemplates = [
            ['name' => 'Juan Dela Cruz', 'student_id' => 'CCE-2026-001', 'dept' => 'CCE', 'course_index' => 0],
            ['name' => 'Maria Santos', 'student_id' => 'CCE-2026-002', 'dept' => 'CCE', 'course_index' => 1],
            ['name' => 'Richard Culanag', 'student_id' => 'CCE-2026-003', 'dept' => 'CCE', 'course_index' => 0],
            ['name' => 'John Smith', 'student_id' => 'CEE-2026-001', 'dept' => 'CEE', 'course_index' => 0],
            ['name' => 'Emily Chen', 'student_id' => 'CEE-2026-002', 'dept' => 'CEE', 'course_index' => 1],
            ['name' => 'Lisa Wang', 'student_id' => 'CCJE-2026-001', 'dept' => 'CCJE', 'course_index' => 0],
            ['name' => 'Robert Taylor', 'student_id' => 'CCJE-2026-002', 'dept' => 'CCJE', 'course_index' => 1],
            ['name' => 'Patricia Lee', 'student_id' => 'CTE-2026-001', 'dept' => 'CTE', 'course_index' => 0],
            ['name' => 'Christopher Davis', 'student_id' => 'CTE-2026-002', 'dept' => 'CTE', 'course_index' => 1],
            ['name' => 'Kevin Harris', 'student_id' => 'CASE-2026-001', 'dept' => 'CASE', 'course_index' => 0],
            ['name' => 'Susan Clark', 'student_id' => 'CASE-2026-002', 'dept' => 'CASE', 'course_index' => 1],
        ];

        foreach ($studentTemplates as $student) {
            $department = Department::where('code', $student['dept'])->first();
            $course = $coursesByDepartment[$student['dept']][$student['course_index']] ?? null;

            User::updateOrCreate(['email' => strtolower(str_replace(' ', '.', $student['name'])) . '@votera.edu'], [
                'name' => $student['name'],
                'password' => Hash::make('password'),
                'role' => 'student',
                'student_id' => $student['student_id'],
                'department_id' => $department?->id,
                'course_id' => $course?->id,
            ]);
        }

        $electionsData = [
            ['title' => 'CCE Student Council Election', 'dept' => 'CCE', 'status' => 'ongoing', 'description' => 'Annual student council election for CCE department.'],
            ['title' => 'CEE Student Council Election', 'dept' => 'CEE', 'status' => 'ongoing', 'description' => 'Annual student council election for CEE department.'],
            ['title' => 'CCJE Student Council Election', 'dept' => 'CCJE', 'status' => 'ongoing', 'description' => 'Annual student council election for CCJE department.'],
            ['title' => 'CTE Department Representative Election', 'dept' => 'CTE', 'status' => 'pending', 'description' => 'Election for department representatives and officers.'],
            ['title' => 'CASE Department Representative Election', 'dept' => 'CASE', 'status' => 'pending', 'description' => 'Election for department representatives and officers.'],
        ];

        foreach ($electionsData as $data) {
            $department = Department::where('code', $data['dept'])->first();
            if (!$department) continue;

            $election = Election::updateOrCreate(
                ['title' => $data['title'], 'department_id' => $department->id],
                [
                    'description' => $data['description'],
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(7)->toDateString(),
                    'status' => $data['status'],
                    'is_locked' => false,
                    'created_by' => $admin->id,
                ]
            );

            $positions = [
                ['name' => 'President', 'description' => 'Leads the department student council.', 'order' => 1],
                ['name' => 'Vice President', 'description' => 'Supports the president and leads initiatives.', 'order' => 2],
                ['name' => 'Secretary', 'description' => 'Handles documentation and communications.', 'order' => 3],
                ['name' => 'Treasurer', 'description' => 'Manages funds and financial records.', 'order' => 4],
                ['name' => 'Senator', 'description' => 'Represents students in the student council.', 'order' => 5],
            ];

            foreach ($positions as $positionData) {
                $position = Position::updateOrCreate(
                    ['election_id' => $election->id, 'name' => $positionData['name']],
                    [
                        'description' => $positionData['description'],
                        'order' => $positionData['order'],
                    ]
                );

                if ($data['status'] === 'ongoing') {
                    Candidate::updateOrCreate(
                        ['position_id' => $position->id, 'name' => $data['dept'] . ' Candidate A - ' . $position->name],
                        ['grade_level' => '3rd Year', 'platform' => 'Transparency, unity, and student welfare.']
                    );
                    Candidate::updateOrCreate(
                        ['position_id' => $position->id, 'name' => $data['dept'] . ' Candidate B - ' . $position->name],
                        ['grade_level' => '4th Year', 'platform' => 'Progress, innovation, and inclusive programs.']
                    );
                }
            }
        }
    }
}