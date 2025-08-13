<?php

namespace App\Imports;

use App\TempRecruitement;
use App\NdcHistory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RecruitmentImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        // employee details
        return new TempRecruitement([
            'name'=>$row['name'],
            'post'=>$row['post'],
            'mobileNo'=>$row['mobile'],
            'email'=>$row['email'],
            'address'=>$row['address'],
            'forDate'=>$row['fordate'],
            'qualification'=>$row['qualification'],
            'prevCompany'=>$row['prevcompany'],
            'prevJobDes'=>$row['prevjobdes'],
            'experience'=>$row['experience']
        ]);

        // return new NdcHistory([
        //     'name'=>$row['name'],
        //     'empCode'=>$row['empcode'],
        //     'department'=>$row['department'],
        //     'designation'=>$row['designation'],
        //     'DOJ'=>$row['doj'],
        //     'exitDate'=>$row['exitdate'],
        //     'NDCStatus'=>$row['ndcstatus'],
        //     'remarks'=>$row['remarks'],
        //     'updated_by'=>$row['updatedby'],
        //     'type'=>$row['type'],
        //     'status'=>$row['status']
        // ]);
    }
}
