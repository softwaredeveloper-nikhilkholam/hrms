<?php

namespace App\Imports;

use App\TempMrUpload;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MRReportImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new TempMrUpload([
            'empCode'=>$row['empcode'],
            'PT'=>$row['pt'],
            'PF'=>$row['pf'],
            'ESIC'=>$row['esic'],
            'MLWL'=>$row['mlwl']
        ]);
    }
}
