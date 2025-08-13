<?php

namespace App\Imports;

use App\TempEmpDet;
use App\TempLeave;
use App\TempSalarySheet;
use App\TempAssetProduct;
use App\TempSalarySheetDemo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // return new TempLeave([
        //     'empCode'=>$row['code'],                  
        //     'startDate'=>$row['startdate'],                  
        //     'endDate'=>$row['enddate']                  
        // ]);

        // return new TempSalarySheetDemo([
        //     'code'=>$row['code'],
        //     'salary'=>$row['salary'],
        //     'perday'=>$row['perday'],
        //     'totaldays'=>$row['totaldays'],
        //     'totpresent'=>$row['totpresent'],
        //     'wf'=>$row['wf'],
        //     'absentdays'=>$row['absentdays'],
        //     'extraworking'=>$row['extraworking'],
        //     'grosspayable'=>$row['grosspayable'],
        //     'wlamount'=>$row['wlamount'],
        //     'advanceagainstsalary'=>$row['advanceagainstsalary'],
        //     'otherdeduction'=>$row['otherdeduction'],
        //     'tds'=>$row['tds'],
        //     'mlwf'=>$row['mlwf'],
        //     'esic'=>$row['esic'],
        //     'pt'=>$row['pt'],
        //     'pf'=>$row['pf'],
        //     'retention'=>$row['retention'],
        //     'totaldeduction'=>$row['totaldeduction'],
        //     'extraworksalary'=>$row['extraworksalary'],
        //     'netsalary'=>$row['netsalary'],
        //     'accountnumber'=>$row['accountnumber'],
        //     'ifsc'=>$row['ifsc'],
        //     'bank'=>$row['bank'],
        //     'branch'=>$row['branch'],
        //     'remark'=>$row['remark'],
        //     'status'=>$row['status']
        // ]);

        //employee details
        // return new TempAssetProduct([
        //     'code'=>$row['code'],                  
        //     'oldsalary'=>$row['oldsalary'],                  
        //     'hike'=>$row['hike'],                  
        //     'newsalary'=>$row['newsalary']               
        // ]);

        // return new TempAssetProduct([
        //     'organisation'=>$row['organisation'],                  
        //     'code'=>$row['code'],                  
        //     'accountno'=>$row['accountno'],                  
        //     'ifsc'=>$row['ifsc']               
        // ]);

        // return new TempAssetProduct([
        //     'mainlocation'=>$row['mainlocation'],                  
        //     'productjourney'=>$row['productjourney'],                  
        //     'ventures'=>$row['ventures'],                
        //     'branch'=>$row['branch'],                
        //     'department'=>$row['department'],                
        //     'locationofdepartment'=>$row['locationofdepartment'],                
        //     'productname'=>$row['productname'],                
        //     'productcategory'=>$row['productcategory'],                
        //     'productsubcategory'=>$row['productsubcategory'],                
        //     'locationoftheproduct'=>$row['locationoftheproduct'],                
        //     'company'=>$row['company'],                
        //     'color'=>$row['color'],                
        //     'size'=>$row['size'],                
        //     'specificationoftheproduct'=>$row['specificationoftheproduct'],                
        //     'typeofproduct'=>$row['typeofproduct'],                
        //     'productcode'=>$row['productcode'],                
        //     'purchasedate'=>$row['purchasedate'],                
        //     'instatllationdate'=>$row['instatllationdate'],                
        //     'prodqty'=>$row['qty'],                
        //     'expiry'=>$row['expiry'],                
        //     'photo'=>$row['photo']               
        // ]);
  
        // return new TempEmpDet([
        //     'empcode'=>$row['empcode'],
        //     'appliedunder'=>$row['appliedunder'],
        //     'studname'=>$row['studname'],
        //     'branch'=>$row['branch'],
        //     'std'=>$row['std'],                 
        //     'dayutiont1'=>$row['dayutiont1'],                 
        //     'bust1'=>$row['bust1'],           
        //     'workst1'=>$row['workst1'],                
        //     'dayutiont2'=>$row['dayutiont2'],               
        //     'bust2'=>$row['bust2'],           
        //     'workst2'=>$row['workst2'],               
        //     'dayutiont3'=>$row['dayutiont3'],                 
        //     'bust3'=>$row['bust3'],                
        //     'workst3'=>$row['workst3']                 
        // ]);

        // return new TempEmpDet([
        //     'name'=>$row['name'],
        //     'mobileno'=>$row['mobileno'],
        //     'address'=>$row['address']
        //     ]);
        
        // attendance details
        return new TempEmpDet([
            'employee'=>$row['employee'],
            'day1'=>$row['day1'],
            'day2'=>$row['day2'],
            'day3'=>$row['day3'],
            'day4'=>$row['day4'],
            'day5'=>$row['day5'],
            'day6'=>$row['day6'],
            'day7'=>$row['day7'],
            'day8'=>$row['day8'],
            'day9'=>$row['day9'],
            'day10'=>$row['day10'],
            'day11'=>$row['day11'],
            'day12'=>$row['day12'],
            'day13'=>$row['day13'],
            'day14'=>$row['day14'],
            'day15'=>$row['day15'],
            'day16'=>$row['day16'],
            'day17'=>$row['day17'],
            'day18'=>$row['day18'],
            'day19'=>$row['day19'],
            'day20'=>$row['day20'],
            'day21'=>$row['day21'],
            'day22'=>$row['day22'],
            'day23'=>$row['day23'],
            'day24'=>$row['day24'],
            'day25'=>$row['day25'],
            'day26'=>$row['day26'],
            'day27'=>$row['day27'],
            'day28'=>$row['day28'],
            'day29'=>$row['day29'],
            'day30'=>$row['day30']
        ]);
    }
}
