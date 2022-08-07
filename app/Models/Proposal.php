<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function research()
    {
        return $this->hasOne(Research::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
}
