<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NdcHistory extends Model
{
    protected $fillable = ['name','empCode', 'department','designation', 'DOJ','exitDate', 'NDCStatus', 'remarks', 'type','status','updated_by'];   
    
}
