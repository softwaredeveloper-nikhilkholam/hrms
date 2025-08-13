<?php

namespace App\Imports;

use App\Demo1;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmpsImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new Demo1([
            'name'     => $row['name'],
            'phoneNo'    => $row['phone'], 
            'address' => $row['address'],
        ]);
    }
}
