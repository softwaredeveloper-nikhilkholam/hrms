<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpDetHistory extends Model
{
    protected $casts = [
        'bankAccountNo' => 'string',
    ];

}
