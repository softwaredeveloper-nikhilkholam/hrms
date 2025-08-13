<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeLetter extends Model
{
    protected $fillable = [
        'empId',
        'designationId',
        'branchId',
        'organisation',
        'fromDate',
        'toDate',
        'salary',
        'aPeriod',
        'forDate',
        'letterType',
        'updated_by',
    ];

}
