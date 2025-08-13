<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class TempEmployee extends Model
{ 
    protected $casts = [
        'form_data' => 'array',
    ];
    
    protected $fillable = ['form_data'];   

}
