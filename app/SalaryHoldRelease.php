<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryHoldRelease extends Model
{

    protected $fillable = [
        'empId', 
        'empCode', 
        'status', 
        'remark', 
        'referenceBy', 
        'forMonth', 
        'userType', 
        'hrStatus', 
        'hrUpdatedAt', 
        'accountStatus', 
        'accountUpdatedAt', 
        'authorityStatus', 
        'authorityUpdatedAt',
        'updated_by'
    ];
}
