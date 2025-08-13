<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpMr extends Model
{
    protected $casts = [
        'bankAccountNo' => 'string',
    ];
}
