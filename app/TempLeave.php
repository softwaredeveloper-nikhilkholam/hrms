<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempLeave extends Model
{
    protected $fillable = ['empCode','startDate', 'endDate'];
  
}
