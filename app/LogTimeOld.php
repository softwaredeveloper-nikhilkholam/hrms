<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogTimeOld extends Model
{
    public $timestamps = false;
    protected $fillable = ['EmployeeCode','LogDateTime','DeviceSerialNumber'];   
}
