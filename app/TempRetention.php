<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempRetention extends Model
{
    protected $fillable = ["id", "name", "designation", "code", "amount", "remark"];
}
