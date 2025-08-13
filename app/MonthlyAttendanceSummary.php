<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use HasFactory;
class MonthlyAttendanceSummary extends Model
{
    protected $fillable = [
        'empId',
        'empCode',
        'branchId',
        'month',
        'organisation',
        'presentDays',
        'WLeaveDays',
        'absentDays',
        'weeklyLeaveDays',
        'extraWorkDays',
        'totalDeductions',
        'retention',
        'WF',
        'PT',
        'PF',
        'ESIC',
        'MLWL',
        'HRA',
        'transportAllowance',
        'otherAllowance',
        'payableDays',
        'grossSalary',
        'prevGrossSalary',
        'advanceAgainstSalary',
        'otherDeduction',
        'bankAccountNo',
        'bankIFSCCode',
        'bankName',
        'salaryStatus',
        'salaryType',
        'isManuallyEdited',
    ];
}
