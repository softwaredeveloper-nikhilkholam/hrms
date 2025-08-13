<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreRepaireProduct extends Model
{
    protected $fillable = [
        'repaireId', 'productId', 'reasonForRepair', 'qty', 'returnQty', 'repaireRemark', 'repairingAmount', 'status','active','updated_by'
    ];
}
