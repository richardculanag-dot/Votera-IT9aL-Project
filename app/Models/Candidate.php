<?php
// FILE: app/Models/Candidate.php — replace existing

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'position_id', 'name', 'image', 'platform', 'grade_level', 'partylist',
    ];

    protected $appends = ['image_url'];

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
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
             . '&background=1a1a1a&color=fff&size=200';
    }
}