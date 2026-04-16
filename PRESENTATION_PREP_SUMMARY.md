# Presentation Prep Summary

## 1. What This System Is For

This project is a Laravel-based voting management system designed for school elections. Its purpose is to let administrators set up elections, positions, and candidates; let students vote only in the elections they are allowed to join; and give staff or admins a live overview of turnout and election activity.

At a high level, the system solves these problems:
- organizing elections by department
- separating access by role
- preventing duplicate or invalid voting
- showing near real-time dashboard updates through API polling
- keeping an audit trail of important actions

The three main user roles are:
- `admin`: full control over elections, staff, students, votes, results, and audit logs
- `staff`: limited management and monitoring access, mainly reusing election and results screens
- `student`: can view eligible elections, cast a ballot, review history, and update profile

## 2. Core Business Flow

The main business flow is:

`User -> Election -> Position -> Candidate -> Vote`

How the flow works in practice:
1. An admin creates an election.
2. The system automatically creates default positions such as President, Vice President, Secretary, Treasurer, Auditor, Business Manager, and Senator.
3. Admin or staff add candidates under each position.
4. A student opens an election ballot for their department.
5. The system validates that the election is open, the student is eligible, and every position has candidates.
6. The student selects one candidate per position.
7. Votes are stored per `user`, `candidate`, `position`, and `election`.
8. The action is recorded in `AuditLog`.

This is a good presentation point: the design is not just storing votes. It also enforces election readiness, eligibility, and accountability.

## 3. Main Models and What They Represent

### `app/Models/User.php`
- Central authentication model
- Stores role information such as `admin`, `staff`, or `student`
- Connects users to `department`, `course`, and `votes`
- Includes helper methods like `isAdmin()`, `isStaff()`, `isStudent()`, `hasVotedInElection()`, and `canVoteIn()`

Why it matters:
The `User` model is where access control and voter eligibility begin.

### `app/Models/Election.php`
- Represents an election event
- Stores title, description, department, dates, status, lock state, and creator
- Has relationships to `positions`, `votes`, `department`, and `creator`
- Provides business helpers such as `isOngoing()`, `isOpenForVoting()`, `totalVotesCast()`, and `turnoutPercent()`

Why it matters:
This model controls the lifecycle of voting. It decides whether an election is pending, ongoing, ended, or locked.

### `app/Models/Position.php`
- Represents an office inside an election, such as President or Treasurer
- Belongs to an election
- Has many candidates and votes

Why it matters:
It structures the ballot into offices rather than treating the election as one flat list.

### `app/Models/Candidate.php`
- Represents a candidate under a position
- Stores name, image, platform, grade level, and partylist
- Provides an `image_url` accessor for rendering candidate photos or fallback avatars

Why it matters:
It gives the voting UI the candidate profile information students actually choose from.

### `app/Models/Vote.php`
- Stores the actual vote record
- Links together the `user`, `candidate`, `position`, and `election`

Why it matters:
This is the final output of the voting transaction, and it is denormalized enough to make reporting easier.

### `app/Models/Department.php` and `app/Models/Course.php`
- Organize the academic structure of the school
- Departments scope elections and students
- Courses further categorize students

Why they matter:
This is how the system limits elections to the correct student population.

### `app/Models/AuditLog.php`
- Stores a record of important actions
- Includes a reusable static helper `record()`

Why it matters:
It supports transparency, monitoring, and accountability.

## 4. Key Relationships You Should Know

The most important relationships are:
- one `Department` can have many `Elections`
- one `Department` can have many `Courses`
- one `Department` can have many student `Users`
- one `Election` has many `Positions`
- one `Position` has many `Candidates`
- one `Vote` belongs to one `User`, one `Candidate`, one `Position`, and one `Election`
- one `Election` belongs to one `Department`
- one `Election` belongs to the user who created it

If you need a simple speaking line:
"The database is designed so that students are matched to department-based elections, each election contains positions, each position has candidates, and each submitted vote connects the student to a candidate selection inside a specific election."

## 5. Controllers and Their Responsibilities

### Admin-side controllers

#### `app/Http/Controllers/Admin/DashboardController.php`
- Builds the admin dashboard
- Computes totals such as students, candidates, positions, active elections, current election turnout, and chart data
- Loads recent audit logs

#### `app/Http/Controllers/Admin/ElectionController.php`
- Main controller for election CRUD
- Creates elections and auto-generates default positions
- Updates and soft deletes elections
- Toggles election status between `pending`, `ongoing`, and `ended`
- Prevents an election from starting if positions are missing or a position has no candidate

This is one of the most important controllers because it enforces election readiness, not just basic CRUD.

#### `app/Http/Controllers/Admin/PositionController.php`
- Manages positions inside a specific election

#### `app/Http/Controllers/Admin/CandidateController.php`
- Manages candidates under election positions
- Handles candidate image upload and update flows

#### `app/Http/Controllers/Admin/StudentController.php`
- Shows student lists and student details for admins

#### `app/Http/Controllers/Admin/VoteController.php`
- Shows vote records for monitoring and review

#### `app/Http/Controllers/Admin/ResultsController.php`
- Builds election result summaries and rankings

#### `app/Http/Controllers/Admin/AuditLogController.php`
- Displays logs of important system activity

### Staff-side controllers

#### `app/Http/Controllers/Staff/DashboardController.php`
- Builds the staff dashboard
- Shows totals and voting status

A useful detail for presentation:
Staff does not have a completely separate management system. In `routes/web.php`, staff reuses several admin controllers for elections, positions, candidates, and results.

### Student-side controller

#### `app/Http/Controllers/Student/VoteController.php`
- `dashboard()`: student dashboard
- `profile()`: student profile page
- `updateProfile()`: update name and profile image
- `elections()`: list elections for the student's department
- `index(Election $election)`: show the ballot page
- `store(Request $request, Election $election)`: validate and save the ballot
- `history()`: show previously cast votes
- `success()`: confirmation screen after voting

This is the most important student controller because it contains the main voting rules:
- student must belong to the same department as the election
- election must be open and not locked
- positions must exist
- every position must have a candidate
- one choice is required per position
- invalid candidate selections are rejected
- duplicate voting is prevented
- writes happen inside a database transaction

That last point is important in presentation:
The vote submission is transactional, which helps protect data consistency.

## 6. Routes and How Navigation Is Organized

The project uses three main route files:
- `routes/web.php`
- `routes/auth.php`
- `routes/api.php`

### `routes/web.php`

This is the main application route file.

The root route `/` checks authentication and redirects users by role:
- admin -> `admin.dashboard`
- staff -> `staff.dashboard`
- student -> `student.dashboard`

The web routes are grouped by middleware and prefix:

#### Admin routes
- prefix: `/admin`
- middleware: `auth` and `role:admin`
- examples:
  - `admin.dashboard`
  - `admin.staff.*`
  - `admin.students.*`
  - `admin.votes.index`
  - `admin.elections.*`
  - `admin.elections.positions.*`
  - `admin.elections.positions.candidates.*`
  - `admin.results`
  - `admin.audit`

#### Staff routes
- prefix: `/staff`
- middleware: `auth` and `role:staff`
- examples:
  - `staff.dashboard`
  - `staff.elections.index`
  - `staff.elections.show`
  - `staff.elections.positions.*`
  - `staff.elections.positions.candidates.*`
  - `staff.results`

Important design note:
Staff routes reuse admin controllers, which reduces duplicated logic.

#### Student routes
- prefix: `/student`
- middleware: `auth` and `role:student`
- examples:
  - `student.dashboard`
  - `student.profile`
  - `student.profile.update`
  - `student.elections`
  - `student.vote`
  - `student.vote.store`
  - `student.history`
  - `student.vote.success`

There is also a compatibility route for old `/vote` URLs that redirects students to `student.elections`.

### `routes/auth.php`
- Handles login, registration, password reset, verification, and logout

### `routes/api.php`
- Exposes live JSON endpoints used by dashboard refresh features

## 7. Views and Frontend Structure

The UI is built with Blade templates.

### Shared layouts
- `resources/views/layouts/app.blade.php`: shared layout for admin and staff
- `resources/views/layouts/student.blade.php`: student-focused layout

### Main admin views
- `resources/views/Admin/dashboard.blade.php`
- `resources/views/Admin/elections/index.blade.php`
- `resources/views/Admin/elections/create.blade.php`
- `resources/views/Admin/elections/show.blade.php`
- `resources/views/Admin/elections/edit.blade.php`
- `resources/views/Admin/positions/index.blade.php`
- `resources/views/Admin/positions/create.blade.php`
- `resources/views/Admin/positions/edit.blade.php`
- `resources/views/Admin/candidates/index.blade.php`
- `resources/views/Admin/candidates/create.blade.php`
- `resources/views/Admin/candidates/edit.blade.php`
- `resources/views/Admin/results.blade.php`
- `resources/views/Admin/votes/index.blade.php`
- `resources/views/Admin/staff/index.blade.php`
- `resources/views/Admin/staff/create.blade.php`
- `resources/views/Admin/staff/edit.blade.php`
- `resources/views/Admin/students/index.blade.php`
- `resources/views/Admin/audit.blade.php`

### Main staff view
- `resources/views/Staff/dashboard.blade.php`

### Main student views
- `resources/views/Student/dashboard.blade.php`
- `resources/views/Student/elections.blade.php`
- `resources/views/Student/vote.blade.php`
- `resources/views/Student/history.blade.php`
- `resources/views/Student/profile.blade.php`
- `resources/views/Student/success.blade.php`

How views connect to controllers:
- admin dashboard comes from `Admin\DashboardController@index`
- staff dashboard comes from `Staff\DashboardController@index`
- student pages mostly come from `Student\VoteController`

Good speaking point:
The views are separated by user role, which makes the interface simpler for each user type.

## 8. Migrations and Database Design Story

The migration files are in `database/migrations`.

The schema evolved over time, and that is something useful to mention in a presentation.

### Important migration groups

#### Users and roles
- `2026_03_30_054689_create_users_table.php`
- `2026_03_30_055649_add_role_to_users_table.php`
- `2026_04_15_000001_fix_users_table.php`
- `2026_04_16_000003_add_department_course_to_users_table.php`
- `2026_04_17_000002_add_image_to_users_table.php`

These migrations show that the user table started as a normal auth table and then evolved to support roles, student identity, department/course assignment, and profile images.

#### Departments and courses
- `2026_04_16_000001_create_departments_table.php`
- `2026_04_16_000002_create_courses_table.php`

These provide the academic structure used for election eligibility.

#### Elections
- `2026_04_15_000002_create_elections_table.php`
- `2026_04_16_000004_add_department_and_lock_to_elections_table.php`

This is an important design upgrade because it adds:
- department scoping
- `is_locked`
- `lock_reason`
- `locked_at`

That means elections are not only active or inactive. They can also be explicitly locked.

#### Positions
- `2026_03_30_055727_create_positions_table.php`
- `2026_04_15_000003_add_election_id_to_positions_table.php`
- `2026_04_16_000007_enforce_election_scoping_on_positions_table.php`

These show that positions were improved to become election-specific instead of generic.

#### Candidates
- `2026_03_30_055755_create_candidates_table.php`
- `2026_04_17_000001_add_partylist_to_candidates_table.php`

These support candidate records and partylist information.

#### Votes
- `2026_03_30_055817_create_votes_table.php`
- `2026_04_15_000004_add_election_id_to_votes_table.php`

Important detail:
The original vote table enforced one vote per student per position through a unique key on `user_id` and `position_id`. Later, `election_id` was added to make election reporting clearer and faster.

#### Logs and eligibility
- `2026_04_15_000005_create_audit_logs_table.php`
- `2026_04_16_000005_create_voter_logs_table.php`
- `2026_04_16_000006_create_election_student_eligibility_table.php`

These support accountability and more advanced control over who is allowed to vote.

#### Other/legacy support
- `2026_03_30_055847_create_voting_settings_table.php`
- `2026_04_17_000000_add_soft_deletes_to_all_tables.php`

Useful interpretation:
The database suggests the project evolved from a more global voting setup into a cleaner election-based architecture.

## 9. AJAX / Live Refresh and API Endpoints

One of the notable features of this system is that some dashboards update automatically without reloading the page.

This is done through:
- JavaScript `fetch()`
- `setInterval(...)`
- JSON endpoints from `routes/api.php`

### API endpoints

Defined in `routes/api.php`:
- `GET /api/election/current`
- `GET /api/election/{electionId}/stats`
- `GET /api/election/{electionId}/results`
- `GET /api/dashboard/stats`

These are handled by `app/Http/Controllers/Api/LiveStatsController.php`.

### What each API method does

#### `currentElection()`
- Returns the current ongoing and unlocked election
- Used by student-facing screens to detect whether voting is still live

#### `dashboardStats()`
- Returns high-level dashboard data such as:
  - total students
  - total votes cast
  - turnout percentage
  - number of active elections
  - current election information
  - chart labels and chart data
- Used by admin and staff dashboards

#### `electionStats($electionId)`
- Returns stats for a specific election, including recent vote activity

#### `liveResults($electionId)`
- Returns result breakdowns by position and candidate

### Where live refresh happens in the views

#### `resources/views/Admin/dashboard.blade.php`
- calls `fetch('/api/dashboard/stats')`
- refreshes every 10 seconds
- updates stat cards, turnout text, and Chart.js graph

#### `resources/views/Staff/dashboard.blade.php`
- calls `fetch('/api/dashboard/stats')`
- refreshes every 10 seconds
- updates totals and visible voting status

#### `resources/views/Student/dashboard.blade.php`
- calls `fetch('/api/election/current')`
- refreshes every 10 seconds
- updates the live election count and available live election card

#### `resources/views/Student/vote.blade.php`
- calls `fetch('/api/election/current')`
- refreshes every 5 seconds
- disables form inputs and submit button if voting is no longer open

Why this is a good feature to mention:
It makes the system feel live and responsive, especially for dashboards and election status monitoring.

## 10. Strong Talking Points for Presentation

Here are good lines you can use during reporting:

- "The system follows Laravel's MVC structure, so business logic, routing, UI, and database design are clearly separated."
- "The election flow is department-based, which helps ensure only the correct student group can participate."
- "Vote submission is validated carefully and wrapped in a database transaction to preserve integrity."
- "The admin side is not only CRUD; it also enforces election readiness before voting can begin."
- "The dashboards support near real-time refresh through API polling, so the UI can reflect current turnout and election status."
- "Audit logging improves transparency by recording major election and voting actions."

## 11. Possible Weaknesses or Future Improvements

If your instructor asks what can still be improved, these are reasonable answers:

- Some naming uses mixed case in views such as `Admin` versus `admin` and `Student` versus `student`, which may work on Windows but can cause issues on Linux servers.
- Some files look legacy or less active, such as `ProductController` and `VotingControlController`.
- The architecture mixes a newer election-based design with some older global voting ideas, especially on the staff side.
- Some API endpoints like per-election stats and live results exist even though not every one is clearly used in the current UI.

## 12. Short Script You Can Say

"This system is a Laravel-based voting platform for school elections. It has three roles: admin, staff, and student. Admin manages elections, positions, candidates, users, results, and audit logs. Staff has a more limited monitoring and management role. Students can view elections available to their department, cast ballots, and review their vote history.

The core data flow is user, election, position, candidate, then vote. An election belongs to a department, each election contains positions, each position contains candidates, and each vote records which student selected which candidate for a position in a specific election.

From the MVC perspective, routes are grouped by role, controllers handle the business rules, Blade views provide separate interfaces for admin, staff, and students, and migrations show how the database evolved from a simple voting setup into a department-scoped election system with locking and audit support.

One of the stronger features is the live refresh mechanism. Admin and staff dashboards poll API endpoints for updated stats, and the student ballot page keeps checking whether the election is still open. This helps keep the interface updated without a full page reload."

## 13. Fast Q and A Reviewer

### What is the main purpose of the model layer?
The models represent the system's data and relationships, and they also hold business helpers like election status checks, turnout calculations, and voting eligibility rules.

### Why are controllers important here?
Controllers connect requests to business logic. They validate inputs, enforce role permissions, load models, and return the correct views or JSON responses.

### Why are routes grouped by role?
Because each user type has different permissions and screens. Grouping routes by role makes access control easier to manage and understand.

### Why are migrations important in the presentation?
They show the database design and also tell the story of how the system evolved, especially the shift to department-scoped elections and live election status control.

### What is the purpose of the API refresh flow?
It allows the UI to stay updated in near real time, especially for turnout, current election status, and dashboard summaries.
