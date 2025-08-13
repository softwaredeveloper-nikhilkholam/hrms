<?php

namespace App\Imports;

use App\TempRetention;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RetentionImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new TempRetention([
            'name'=>$row['name'],                  
            'designation'=>$row['designation'],                  
            'code'=>$row['code'],                 
            'amount'=>$row['amount'],                  
            'remark'=>$row['remark']             
        ]);
    }
}
