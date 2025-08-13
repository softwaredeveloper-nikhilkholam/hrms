<?php

namespace App\Imports;

use App\TempSalarySheet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalarySheetImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new TempSalarySheet(['code'=>$row['code'],
        'salary'=>$row['salary']]);


        // return new TempSalarySheet(['name'=>$row['name'],
        //     'empCode'=>$row['code'],
        //     'organisation'=>$row['orgnatisation'],
        //     'biobranch'=>$row['biomachine'],
        //     'section'=>$row['category'],
        //     'pf'=>$row['designation'],
        //     'designation'=>$row['pf'],
        //     'joingDate'=>$row['joiningdate'],
        //     'grossSalary'=>$row['grosssalary'],
        //     'perDayRs'=>$row['perday'],
        //     'totalDays'=>$row['totaldays'],
        //     'presentWithWO'=>$row['presentincludingwo'],
        //     'attendanceGriveanceForm'=>$row['attendsancegform'],
        //     'additionalDutyAvailed'=>$row['additionduty'],
        //     'extraWorkingHours'=>$row['extraworkinghr'],
        //     'additionalDutyPendingPayable'=>$row['additiondutypending'],
        //     'totalPresent'=>$row['totalpresent'],
        //     'absent'=>$row['absent'],
        //     'percentageDays'=>$row['presentdays'],
        //     'basicPayableSalary'=>$row['basicpayblers'],
        //     'basicNotPayableSalary'=>$row['basicnotpayblesalary'],
        //     'applicableRateOf'=>$row['applicablerateof'],
        //     'salaryPayable'=>$row['salarypayble'],
        //     'EWH'=>$row['ewh'],
        //     'ADP'=>$row['adp'],
        //     'due'=>$row['due'],
        //     'adv'=>$row['adv'],
        //     'debit'=>$row['debit'],
        //     'retention'=>$row['retention'],
        //     'TDS'=>$row['tds'],
        //     'MLWF'=>$row['mlwf'],
        //     'ESIC'=>$row['esic'],
        //     'PT'=>$row['pt'],
        //     'SNAYRAAPF'=>$row['snyrapf'],
        //     'TEJASHAPF'=>$row['tejsapf'],
        //     'netPayable'=>$row['netpayble'],
        //     'accountNo'=>$row['acno'],
        //     'IFSC'=>$row['ifsccode'],
        //     'bankName'=>$row['bankbranch'],
        //     'branchName'=>$row['branch'],
        //     'remarks'=>$row['remark']          
        // ]);


    }
}
