<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\StoreProductLedger;

class StoreProduct extends Model
{
    public $timestamps = true;
    
    public function stockLedgers()
    {
        return $this->hasMany(StoreProductLedger::class, 'productId');
    }
}
