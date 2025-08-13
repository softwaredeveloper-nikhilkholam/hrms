<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceJob extends Model
{
     protected $fillable = [
        'fBranchId',
        'section',
        'organisation',
        'fMonth',
        'sheetType',
        'userType',
        'status',
        'updated_by',
    ];
}
