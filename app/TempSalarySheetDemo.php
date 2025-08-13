<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempSalarySheetDemo extends Model
{
    protected $fillable = ["code", "salary", "perday", "totaldays", "totpresent", "wf", "absentdays", "extraworking", "grosspayable", "wlamount", "advanceagainstsalary", "otherdeduction", "tds", "mlwf", "esic", "pt", "pf", "retention", "totaldeduction", "extraworksalary", "netsalary", "accountnumber", "ifsc", "bank", "branch", "remark", "status"];
}
