<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreRepaire extends Model
{
    protected $fillable = [
        'vendorId', 'description', 'totalAmount', 'PONumber', 'forDate', 'status', 'updated_by'
    ];
}
