<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function researches()
    {
        return $this->hasMany(ResearchMember::class);
    }

    public function studies()
    {
        return $this->hasMany(StudyMember::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
