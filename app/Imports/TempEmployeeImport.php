<?php

namespace App\Imports;

use App\TempEmpDet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TempEmployeeImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new TempEmpDet([
            'empcode'=>$row['empcode'],
            'branch'=>$row['branch'],
            'department'=>$row['department'],
            'desingation'=>$row['desingation'],
        ]);
            // $tempEmpDet=new TempEmpDet;
            // // $tempEmpDet->code=$row['code'];
            // $tempEmpDet->role=$row['role'];
            // $tempEmpDet->name=$row['name'];
            // $tempEmpDet->phoneno=$row['phoneno'];
            // $tempEmpDet->whatsappno=$row['whatsappno'];
            // $tempEmpDet->birthdate=$row['birthdate'];
            // $tempEmpDet->gender=$row['gender'];
            // $tempEmpDet->cast=$row['cast'];
            // $tempEmpDet->type=$row['type'];
            // $tempEmpDet->branch=$row['branch'];
            // $tempEmpDet->department=$row['department'];
            // $tempEmpDet->designation=$row['designation'];
            // $tempEmpDet->jobjoingdate=$row['jobjoingdate'];
            // $tempEmpDet->officestarttime=$row['officestarttime'];
            // $tempEmpDet->officeendtime=$row['officeendtime'];
            // $tempEmpDet->maritalstatus=$row['maritalstatus'];
            // $tempEmpDet->salary=$row['salary'];
            // $tempEmpDet->email=$row['email'];
            // $tempEmpDet->teachingsubject=$row['teachingsubject'];
            // $tempEmpDet->reference=$row['reference'];
            // $tempEmpDet->bankifsccode=$row['bankifsccode'];
            // $tempEmpDet->bankname=$row['bankname'];
            // $tempEmpDet->bankaccountno=$row['bankaccountno'];
            // $tempEmpDet->qualification=$row['qualification'];
            // $tempEmpDet->aadharno=$row['aadharno'];
            // $tempEmpDet->pancardno=$row['pancardno'];
            // $tempEmpDet->instagramid=$row['instagramid'];
            // $tempEmpDet->twitterid=$row['twitterid'];
            // $tempEmpDet->facebookid=$row['facebookid'];
            // $tempEmpDet->presentaddress=$row['presentaddress'];
            // $tempEmpDet->presentstate=$row['presentstate'];
            // $tempEmpDet->presentcityname=$row['presentcityname'];
            // $tempEmpDet->presentpincode=$row['presentpincode'];
            // $tempEmpDet->permanentaddress=$row['permanentaddress'];
            // $tempEmpDet->permanentstatename=$row['permanentstatename'];
            // $tempEmpDet->permanentcityname=$row['permanentcityname'];
            // $tempEmpDet->permanentpincode=$row['permanentpincode'];
            // $tempEmpDet->experiencecomname=$row['experiencecomname'];
            // $tempEmpDet->expdesignation=$row['expdesignation'];
            // $tempEmpDet->explastsalary=$row['explastsalary'];
            // $tempEmpDet->expduration=$row['expduration'];
            // $tempEmpDet->expjobdescription=$row['expjobdescription'];
            // $tempEmpDet->expreasonleaving=$row['expreasonleaving'];
            // $tempEmpDet->expcompanyphone=$row['expcompanyphone'];
            // $tempEmpDet->save();
    }
}
