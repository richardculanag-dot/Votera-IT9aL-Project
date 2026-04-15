<?php
// FILE: app/Models/Election.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Election extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'status', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // ── Relationships ──────────────────────────────────────
    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helpers ────────────────────────────────────────────
    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isEnded(): bool
    {
        return $this->status === 'ended';
    }

    public function totalVoters(): int
    {
        return User::where('role', 'student')->count();
    }

    public function totalVotesCast(): int
    {
        return $this->votes()->distinct('user_id')->count('user_id');
    }

    public function turnoutPercent(): float
    {
        $total = $this->totalVoters();
        if ($total === 0) return 0;
        return round(($this->totalVotesCast() / $total) * 100, 1);
    }

    // ── Scopes ─────────────────────────────────────────────
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'ongoing']);
    }
}