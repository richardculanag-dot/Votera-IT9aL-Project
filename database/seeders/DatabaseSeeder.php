<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin account ──────────────────────────────
        User::create([
            'name'       => 'System Admin',
            'email'      => 'admin@votera.edu',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'student_id' => null,
        ]);

        // ── Staff account ──────────────────────────────
        User::create([
            'name'       => 'Staff Member',
            'email'      => 'staff@votera.edu',
            'password'   => Hash::make('password'),
            'role'       => 'staff',
            'student_id' => null,
        ]);

        // ── Student accounts ───────────────────────────
        User::create([
            'name'       => 'Juan dela Cruz',
            'email'      => 'student@votera.edu',
            'password'   => Hash::make('password'),
            'role'       => 'student',
            'student_id' => 's.student.001',
        ]);

        User::create([
            'name'       => 'Maria Santos',
            'email'      => 'maria@votera.edu',
            'password'   => Hash::make('password'),
            'role'       => 'student',
            'student_id' => 's.student.002',
        ]);

        // ── Positions ──────────────────────────────────
        $positions = [
            ['name' => 'Student Council President',  'description' => 'Leads the student council and represents student interests.', 'order' => 1],
            ['name' => 'Secretary of Finance',        'description' => 'Manages and oversees the student council budget.',            'order' => 2],
            ['name' => 'Social Media Liaison',        'description' => 'Manages the school\'s online presence and communications.',    'order' => 3],
            ['name' => 'Athletic Director',           'description' => 'Oversees sports programs and intramural events.',             'order' => 4],
        ];

        foreach ($positions as $p) {
            Position::create($p);
        }

        // ── Candidates ─────────────────────────────────
        $candidates = [
            // President (position 1)
            ['name' => 'Eleanor Thorne',  'position_id' => 1, 'grade_level' => 'Grade 12', 'platform' => 'Pioneering a sustainable campus initiative and fostering inclusive student governance.'],
            ['name' => 'Ryan Marcos',     'position_id' => 1, 'grade_level' => 'Grade 12', 'platform' => 'Advocating for better facilities and transparent leadership for all students.'],

            // Finance (position 2)
            ['name' => 'Marcus Sterling', 'position_id' => 2, 'grade_level' => 'Grade 11', 'platform' => 'Focusing on transparent club funding and optimizing the annual budget for extracurricular activities.'],
            ['name' => 'Lena Park',        'position_id' => 2, 'grade_level' => 'Grade 11', 'platform' => 'Bringing accountability and smart budgeting to every student-led initiative.'],

            // Social Media (position 3)
            ['name' => 'Maya Chen',       'position_id' => 3, 'grade_level' => 'Grade 10', 'platform' => 'Bridging the gap between faculty and students using engaging digital storytelling.'],
            ['name' => 'Jake Rivera',     'position_id' => 3, 'grade_level' => 'Grade 10', 'platform' => 'Creating vibrant, inclusive content that celebrates every student\'s story.'],

            // Athletic (position 4)
            ['name' => 'Julian Rossi',    'position_id' => 4, 'grade_level' => 'Grade 12', 'platform' => 'Promoting holistic physical education and introducing intramural tournaments for all skill levels.'],
            ['name' => 'Ava Torres',      'position_id' => 4, 'grade_level' => 'Grade 11', 'platform' => 'Expanding school sports programs and ensuring fair access to athletic resources.'],
        ];

        foreach ($candidates as $c) {
            Candidate::create($c);
        }
    }
}