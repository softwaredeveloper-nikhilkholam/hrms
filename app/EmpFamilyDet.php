<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpFamilyDet extends Model
{
     protected $fillable = [
        'empId',
        'name',
        'relation',
        'occupation',
        'contactNo',
        'updated_by',
    ];
}
