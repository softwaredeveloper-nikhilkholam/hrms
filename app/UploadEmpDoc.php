<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadEmpDoc extends Model
{
    protected $fillable = [
        'empId',
        'empCode',
        'fileName',
        'type',
        'updated_by',
    ];
}
