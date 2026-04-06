<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position_id',
        'image',
        'platform',
        'grade_level',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        // Return a default placeholder avatar
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1a1a1a&color=fff&size=200';
    }
}