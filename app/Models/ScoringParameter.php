<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringParameter extends Model
{
    protected $fillable = [
        'criterion_id',
        'min_value',
        'max_value',
        'score'
    ];

    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}
