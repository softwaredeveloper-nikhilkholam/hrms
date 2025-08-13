<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use HasFactory;

class EmpChangeDay extends Model
{
    protected $table = 'emp_change_days';
    protected $fillable = [
        'empId',
        'oldPresentDays',
        'oldAbsentDays',
        'oldWLDays',
        'oldExtraDays',
        'oldTotalDays',
        'newPresentDays',
        'newAbsentDays',
        'newWLDays',
        'newExtraDays',
        'newPayableDays', // Assumed a column for this, adjust if needed
        'remark',
        'month',
        'referenceBy',
        'holdRelasestatus',
        'status',
        'updated_by',
    ];
}
