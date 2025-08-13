<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retention extends Model
{
    protected $fillable = [
        'empId',
        'month',
        'retentionAmount',
        'remark',
        'updated_by',
    ];
}
