<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitProcessStatus extends Model
{
    protected $fillable = [
        'empId',
        'empCode',
        'reportingAuthId',
        'reportingAuth',
        'storeDept',
        'itDept',
        'erpDept',
        'accountDept',
        'hrDept',
        'reportingAuthDate',
        'storeDeptDate',
        'itDeptDate',
        'erpDeptDate',
        'accountDeptDate',
        'hrDeptDate',
        'finalPermission',
        'authorityUpdatedAt',
        'processType',
        'lastWorkingDate',
        'applyDate',
        'description',
        'status',
        'expectedLastDate',
        'reqExitDate',
        'fileName',
        'active',
        'updated_by',
    ];
}
