<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    protected $fillable = ['code', 'name', 'type', 'weight'];

    public function parameters()
    {
        return $this->hasMany(ScoringParameter::class);
    }
}
