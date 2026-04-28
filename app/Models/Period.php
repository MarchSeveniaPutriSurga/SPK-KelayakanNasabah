<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = ['month', 'year', 'label', 'is_active', 'quota_lolos'];

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
