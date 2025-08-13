<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'branchCount',
        'departmentCount',
        'designationCount',
        'branchIds',
        'departmentIds',
        'designationIds',
        'holidayType',
        'name',
        'forDate',
        'status',
        'updatedStatus',
        'active',
        'updated_by',
        // add any other fillable fields here
    ];
}
