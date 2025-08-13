<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempMrUpload extends Model
{
    protected $fillable = ['empCode','PT', 'PF', 'ESIC', 'MLWL'];
  
}
