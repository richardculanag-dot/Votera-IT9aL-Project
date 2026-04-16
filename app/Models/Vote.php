<?php
// FILE: app/Models/Vote.php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'candidate_id', 'position_id', 'election_id',
    ];

    public function user()      { return $this->belongsTo(User::class); }
    public function candidate() { return $this->belongsTo(Candidate::class); }
    public function position()  { return $this->belongsTo(Position::class); }
    public function election()  { return $this->belongsTo(Election::class); }
}