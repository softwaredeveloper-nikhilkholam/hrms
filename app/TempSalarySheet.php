<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempSalarySheet extends Model
{
    // protected $fillable = ["id", "name", "empCode", "organisation", "pf", "biobranch", "section", "designation", "joingDate", "grossSalary", "perDayRs", "totalDays", "presentWithWO", "attendanceGriveanceForm", "additionalDutyAvailed", "extraWorkingHours", "additionalDutyPendingPayable", "totalPresent", "absent", "percentageDays", "basicPayableSalary", "basicNotPayableSalary", "applicableRateOf", "salaryPayable", "EWH", "ADP", "due", "adv", "debit", "retention", "TDS", "MLWF", "ESIC", "PT", "SNAYRAAPF", "TEJASHAPF", "netPayable", "accountNo", "IFSC", "bankName", "branchName", "remarks"];
    // protected $fillable = ["id", "code", "salary"];
    // protected $fillable = ["id", "departmentname", "designation", "newdesignation"];
    protected $fillable = ["id", "code", "salary"];
}
