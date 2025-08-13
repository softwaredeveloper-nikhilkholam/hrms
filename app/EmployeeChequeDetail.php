<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeChequeDetail extends Model
{
    protected $fillable = [
        'empId',           // Add this line
        'exitId',           // Add this line
        'payeeName',
        'chequeDate',
        'payeeAmount',
        'payeeBank',
        'payeeChequeNo',
        'status',
        'updated_by'
    ];
}
