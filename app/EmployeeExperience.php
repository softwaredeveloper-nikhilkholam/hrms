<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeExperience extends Model
{
     protected $fillable = [
        'empId',
        'experName',
        'experDesignation',
        'experFromDuration',
        'experToDuration',
        'experLastSalary',
        'experJobDesc',
        'experReasonLeaving',
        'experReportingAuth',
        'experReportingDesignation',
        'experCompanyCont',
        'updated_by',
    ];
}
