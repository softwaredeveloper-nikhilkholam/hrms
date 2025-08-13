<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuelFilledEntry extends Model
{
    protected $fillable = [
        'forDate', 'vendorId', 'branchId', 'dieselRate','petrolRate','petrolRate','dieselRate','totalVehicle','totalDiesel','totalPetrol','totalPetrolRs','totalDieselRs','status' // Add your other fields here
    ];

}
