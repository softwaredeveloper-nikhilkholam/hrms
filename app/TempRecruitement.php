<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempRecruitement extends Model
{
    protected $fillable = ['name','post', 'mobileNo','email', 'address','forDate', 'qualification', 'prevCompany', 'prevJobDes', 'experience'];   
}
