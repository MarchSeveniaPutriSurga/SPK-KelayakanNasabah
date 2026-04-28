<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'period_id',
        'customer_id',
        'criterion_id',
        'real_value',
        'score'
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function criterion()
    {
        return $this->belongsTo(Criterion::class);
    }
}
