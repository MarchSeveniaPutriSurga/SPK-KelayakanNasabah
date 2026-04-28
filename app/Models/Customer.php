<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'identifier', 'phone'];

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}
