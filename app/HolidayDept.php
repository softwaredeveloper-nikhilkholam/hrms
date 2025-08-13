<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HolidayDept extends Model
{
    protected $fillable = [
        'holidayId',
        'forDate',
        'empId',
        'empCode',
        'branchId',
        'departmentId',
        'designationId',
        'paymentType',
        'holiday',
        'active',
        'updated_by',
        // add any other fillable fields here
    ];
}
