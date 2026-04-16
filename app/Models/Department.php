<?php
// FILE: app/Models/Department.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function elections()
    {
        return $this->hasMany(Election::class);
    }

    public function students()
    {
        return $this->hasMany(User::class)->where('role', 'student');
    }
}