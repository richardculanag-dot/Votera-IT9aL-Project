<?php
// FILE: app/Models/User.php — replace existing

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'student_id',
        'department_id', 'course_id', 'image',
    ];

    protected $appends = ['image_url'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Role helpers ───────────────────────────────────
    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isStaff(): bool   { return $this->role === 'staff'; }
    public function isStudent(): bool { return $this->role === 'student'; }
    public function isAdminOrStaff(): bool { return in_array($this->role, ['admin', 'staff']); }

    // ── Relationships ──────────────────────────────────
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function eligibilities()
    {
        return $this->hasMany(ElectionStudentEligibility::class, 'student_id');
    }

    public function voterLogs()
    {
        return $this->hasMany(VoterLog::class, 'student_id');
    }

    // ── Helpers ────────────────────────────────────────
    /**
     * Check if this student has already voted in a given election.
     */
    public function hasVotedInElection(int $electionId): bool
    {
        return $this->votes()->where('election_id', $electionId)->exists();
    }

    /**
     * Get elections this student is eligible for (same department).
     */
    public function eligibleElections()
    {
        return Election::where('department_id', $this->department_id)
                       ->where('status', 'ongoing')
                       ->where('is_locked', false)
                       ->get();
    }

    /**
     * Check eligibility for a specific election.
     * Primary check: same department. Can be overridden by explicit eligibility record.
     */
    public function canVoteIn(Election $election): bool
    {
        // Check explicit disqualification first
        $record = ElectionStudentEligibility::where('election_id', $election->id)
                                            ->where('student_id', $this->id)
                                            ->first();
        if ($record) {
            return (bool) $record->allowed;
        }

        // Default: department match
        return $this->department_id === $election->department_id;
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
             . '&background=1a1a1a&color=fff&size=200';
    }
}