<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempAssetProduct extends Model
{
    // protected $fillable = ['mainlocation','productjourney','ventures','branch','department','locationofdepartment','productname','prodqty','productcategory','productsubcategory','locationoftheproduct','company','color','size','specificationoftheproduct','typeofproduct','productcode','purchasedate','instatllationdate','qty','expiry','photo'];   
    // protected $fillable = ['organisation','code','accountno','ifsc'];   
    protected $fillable = ['code','oldsalary','newsalary'];   
}
