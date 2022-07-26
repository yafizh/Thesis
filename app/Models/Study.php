<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function members()
    {
        return $this->hasMany(StudyMember::class);
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function research()
    {
        return $this->belongsTo(Research::class);
    }
}
