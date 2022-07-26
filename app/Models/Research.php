<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function members()
    {
        return $this->hasMany(ResearchMember::class);
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
