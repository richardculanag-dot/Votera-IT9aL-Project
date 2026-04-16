<?php
// FILE: app/Models/Election.php — replace existing

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Election extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description',
        'department_id',
        'start_date', 'end_date',
        'status',            // pending | ongoing | ended
        'is_locked',
        'lock_reason',
        'locked_at',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'locked_at'  => 'datetime',
        'is_locked'  => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────
    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function voterLogs()
    {
        return $this->hasMany(VoterLog::class);
    }

    public function eligibilities()
    {
        return $this->hasMany(ElectionStudentEligibility::class);
    }

    // ── Status helpers ─────────────────────────────────
    public function isOngoing(): bool { return $this->status === 'ongoing'; }
    public function isPending(): bool { return $this->status === 'pending'; }
    public function isEnded(): bool   { return $this->status === 'ended'; }

    public function isOpenForVoting(): bool
    {
        return $this->isOngoing() && ! $this->is_locked;
    }

    // ── Stats ──────────────────────────────────────────
    public function totalVoters(): int
    {
        return User::where('role', 'student')
                   ->where('department_id', $this->department_id)
                   ->count();
    }

    public function totalVotesCast(): int
    {
        return $this->votes()->distinct('user_id')->count('user_id');
    }

    public function turnoutPercent(): float
    {
        $total = $this->totalVoters();
        if ($total === 0) return 0.0;
        return round(($this->totalVotesCast() / $total) * 100, 1);
    }

    // ── Scopes ─────────────────────────────────────────
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeForDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeOpenForVoting($query)
    {
        return $query->where('status', 'ongoing')->where('is_locked', false);
    }
}