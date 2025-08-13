<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignTaskMaster extends Model
{
    protected $fillable = [
        'taskId',
        'empId',
        'status',
        'updated_by',
        // Add any other fields that you might set via create() or update()
    ];
}
