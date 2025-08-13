<?php

namespace App\Exports;
use App\EmpDet;
use App\SalarySheet;
use App\Helpers\Utility;
use App\EmpMr;
use App\Retention;
use App\TempSalarySheet;
use App\MonthlyAttendanceSummary;

use Auth;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;


use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class SalarySheetExport implements FromCollection, WithHeadings, WithColumnFormatting, WithCalculatedFormulas, WithEvents
{
    private $organisation;
    private $branch;
    private $section;
    private $salaryType;
    private $month;
    private $flag;
    private $rowCount;

    public function __construct($organisation, $branch, $section, $salaryType ,$month, $flag, $rowCount)
    {
        $this->organisation = $organisation;
        $this->branch = $branch;
        $this->section = $section;
        $this->salaryType = $salaryType;
        $this->month = $month;
        $this->flag = $flag;
        $this->rowCount = $rowCount;
    }

    public function columnFormats(): array
    {

        return [
            'G' => NumberFormat::FORMAT_NUMBER, // Salary formatted as currency
            'K' => NumberFormat::FORMAT_NUMBER, // Salary formatted as currency
            'Q' => NumberFormat::FORMAT_NUMBER, // Bonus formatted as currency
            'R' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
            'R' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
            'AA' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
            'AB' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
            'AC' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
            'AD' => NumberFormat::FORMAT_TEXT, // Total salary formatted as currency
        ];
    }

    public function collection()
    {
        $organisation=$this->organisation;
        $branch=$this->branch;
        $section=$this->section;
        $salaryType=$this->salaryType;
        $month=$this->month;
        $flag=$this->flag;
        $rowCount=$this->rowCount;
        
        if('2025-05' >= date('Y-m'))
        {
            $salarySheet = EmpMr::join('emp_dets', 'emp_mrs.empId', 'emp_dets.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('emp_dets.bankAccountNo','emp_dets.bankIFSCCode','emp_dets.branchName','emp_dets.bankName',
            'emp_dets.name','emp_dets.empCode','emp_dets.organisation as empOrganisation','designations.name as designationName',
            'emp_dets.jobJoingDate','emp_dets.jobJoingDate','departments.section','departments.name as departmentName',
            'emp_dets.salaryScale as tempSalary','contactus_land_pages.shortName','emp_mrs.*')
            ->where('emp_dets.jobJoingDate', '<=', date('Y-m-t', strtotime($month)))
            ->where('emp_mrs.totPresent', '>=', 1)
            ->where('emp_mrs.forDate', $month);
            if($salaryType != 0)
            {
                $salarySheet = $salarySheet->where('emp_mrs.type', $salaryType);
            }
        }
        else
        {
            $salarySheet = MonthlyAttendanceSummary::join('emp_dets', 'monthly_attendance_summaries.empId', 'emp_dets.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->join('organisations', 'emp_dets.organisationId', 'organisations.id')
            ->select('emp_dets.bankAccountNo','emp_dets.bankIFSCCode','emp_dets.branchName','emp_dets.bankName',
            'emp_dets.name','emp_dets.empCode','organisations.shortName as empOrganisation','designations.name as designationName',
            'emp_dets.jobJoingDate', 'departments.section','departments.name as departmentName',
            'monthly_attendance_summaries.grossSalary as tempSalary', 'contactus_land_pages.shortName','monthly_attendance_summaries.*')
            ->where('monthly_attendance_summaries.month', $month)
            ->where('emp_dets.jobJoingDate', '<=', date('Y-m-t', strtotime($month)))
            ->where('emp_dets.active', 1)
            ->where('monthly_attendance_summaries.presentDays', '>=', 1);
            if($salaryType != 0)
            {
                $salarySheet = $salarySheet->where('monthly_attendance_summaries.salaryType', $salaryType);
            }
        }

        if($branch != 0)
            $salarySheet = $salarySheet->where('emp_dets.branchId', $branch);

        if($section != 0)
        {
            if($section == 1)
                $salarySheet = $salarySheet->where('departments.section', 'Teaching');
            else
                $salarySheet = $salarySheet->where('departments.section', 'Non Teaching');

        }
        
        if($organisation != 0)
        {
            // if($organisation == 1)
            // {
                $salarySheet = $salarySheet->where('emp_dets.organisationId', $organisation);
            // }
            // elseif($organisation == 2)
            // {
            //     $salarySheet = $salarySheet->where('emp_dets.organisation', 'Snayraa');
            // }
            // elseif($organisation == 3)
            // {
            //     $salarySheet = $salarySheet->where('emp_dets.organisation', 'Tejasha');
            // }
            // elseif($organisation == 4)
            // {
            //     $salarySheet = $salarySheet->where('emp_dets.organisation', 'Akshara');
            // }
            // elseif($organisation == 5)
            // {
            //     $salarySheet = $salarySheet->where('emp_dets.organisation', 'Aaryans Edutainment');
            // }
            // elseif($organisation == 6)
            // {
            //     $salarySheet = $salarySheet->where('emp_dets.organisation', 'ADF');
            // }
            // elseif($organisation == 7)
            // {
            //     $salarySheet = $salarySheet->where('emp_dets.organisation', 'AFF');
            // }
            // elseif($organisation == 9)
            //     $salarySheet = $salarySheet->where('emp_dets.organisation', 'Aaryans Animal Bird Fish Reptiles Rescue Rehabilitation And Educational Society');
            // else
            // {
            //     $salarySheet = $salarySheet->where('emp_dets.organisation', 'YB');
            // }
        }

        $salarySheet = $salarySheet->orderBy('emp_dets.empCode')->orderBy('id', 'desc')->get();

        if($flag == 1) // Salary sheet
        {
            $util = new Utility();
            $temp=[];
            $employees=[];
            $i=1;
            $sumGrossSalary =$sumPerDay = $sumExtraWorking = $sumGrossPayableSalary = $sumAdvanceAgainstSalary = $sumOtherDeduction = $sumTDS = $sumMLWF = $sumESIC = $sumPT = $sumPF = $sumTotalDeduction = $sumNetSalary = $sumExtraWorkingSalary = $sumActualPayable = 0;
            foreach($salarySheet as $sheet)
            {
                if($salaryType == 0)
                    $sheet->grossSalary = $sheet->tempSalary;
                if('2025-05' >= date('Y-m'))
                {
                    $sheet->totPresent = $sheet->totalDays;
                }
                
                $perDay= ($sheet->grossSalary != 0)?round(($sheet->grossSalary / date('t', strtotime($month))), 2):0;
                $temp['t1']=$i++; //A
                $temp['t2']=$sheet->name; //B
                $temp['t3']=$sheet->organisation; //C
                $temp['t4']=$sheet->empCode;  //D
                $temp['t5']=$sheet->designationName; //E
                $temp['t6']=$sheet->departmentName; //F
                $temp['t7']=$sheet->section;  //G
                $temp['t8']=$sheet->shortName; //H
                $temp['t9']=($sheet->jobJoingDate != '')?(date('d-m-Y', strtotime($sheet->jobJoingDate))):'NA'; //I
                $temp['t10']=($sheet->grossSalary != 0)?$sheet->grossSalary:"0"; //J
                $temp['t11']='=J'.$i.'/L'.$i; //K
                $temp['t12']=date('t', strtotime($month)); //L
                if('2025-05' >= date('Y-m'))
                {
                    $temp['t13']=$sheet->totPresent-$sheet->extraWorking; //M
                    $temp['t14']=$sheet->totWLeave; //N
                }
                else
                {
                    $temp['t13']=$sheet->presentDays; //M
                    $temp['t14']=$sheet->totalDeductions; //N
                }
               
                // $temp['t15']=$sheet->totAbsent; //O
                $temp['t15']='=L'.$i.'-M'.$i.'-N'.$i; //O   totMonthDays - Present - Welfare
                
                $temp['t16']=$sheet->extraWorkDays; //P
                // $t17=round($temp['t13']*($sheet->grossSalary/(date('t', strtotime($sheet->forDate))))); //Q
                $temp['t17']='=M'.$i.'*K'.$i; //Q
                // $temp['t18']=($sheet->totWLeave != '0')?round($sheet->totWLeave*$perDay, 0):'0';  //R
                $temp['t18']='=N'.$i.'*K'.$i;  //R
                $temp['t19']=($sheet->advanceAgainstSalary != 0.00)?$sheet->advanceAgainstSalary:"0";//S
                $temp['t20']=($sheet->otherDeduction != 0)?$sheet->otherDeduction:"0";//T
                $temp['t21']=($sheet->TDS == '')?'0':$sheet->TDS;//U
                if('2025-05' >= date('Y-m'))
                    $temp['t22']=($sheet->MLWF == '')?'0':$sheet->MLWF; //V
                else
                    $temp['t22']=($sheet->MLWL == '')?'0':$sheet->MLWL; //V

                $temp['t23']=($sheet->ESIC == '')?'0':$sheet->ESIC;//W
                $temp['t24']=($sheet->PT == '')?'0':$sheet->PT; //X
                $temp['t25']=($sheet->PF == '')?'0':$sheet->PF; //Y
                // if($salaryType == 1)
                //     $temp['t26']= 0; //Z
                // else
                // {
                    if('2025-05' >= date('Y-m'))
                        $temp['t26']= Retention::where('empId', $sheet->empId)->where('month', $sheet->forDate)->sum('retentionAmount'); //Z
                    else
                        $temp['t26']= $sheet->retention;
                // }
                $temp['t27']='=S'.$i.'+T'.$i.'+U'.$i.'+V'.$i.'+W'.$i.'+x'.$i.'+Y'.$i.'+Z'.$i; //AA

                $temp['t28']='=P'.$i.'*K'.$i;//AB

                $temp['t29']='=Q'.$i.'-AA'.$i.'+AB'.$i;//AC
                $temp['t30']=($sheet->bankAccountNo != null)?" " . $sheet->bankAccountNo:"-"; //AD
                $temp['t31']=($sheet->bankIFSCCode != null)?$sheet->bankIFSCCode:"-"; //AE
                $temp['t32']=($sheet->bankName != null)?$sheet->bankName:"-";//AF
                $temp['t33']=($sheet->bankBranch != null)?$sheet->bankBranch:"-";//AG
                $temp['t34']=($sheet->remark != null)?$sheet->remark:"-";//AH
              
                if('2025-05' >= date('Y-m'))
                    $holdStatus = $util->getSalaryHoldStatus($sheet->empId, $sheet->forDate);
                else
                    $holdStatus = $util->getSalaryHoldStatus($sheet->empId, $sheet->month);

                $temp['t35']=($holdStatus == 0)?'Regular':'Hold'; //AI

                array_push($employees, $temp);
            }
        }
        else //bank details
        {
            $temp=[];
            $employees=[];
            $i=1;
            foreach($salarySheet as $sheet)
            {
                $perDay=$t17=$t27=$t28=0;
                $perDay= ($sheet->grossSalary != 0)?round(($sheet->grossSalary / date('t', strtotime($month))), 2):0;
                $t17=round(($sheet->totPresent-$sheet->extraWorking)*($sheet->grossSalary/(date('t', strtotime($sheet->forDate)))), 2);
                $t27=($sheet->advanceAgainstSalary+$sheet->TDS+$sheet->MLWF+$sheet->ESIC+$sheet->PT+$sheet->PF+$sheet->otherDeduction);
                $t28=($sheet->extraWorkingSalary != '0')?($sheet->extraWorking*$perDay):"0";

                $temp['t0']=$i++;
                $temp['t1']=$sheet->name;
                $temp['t2']=$sheet->empCode;
                $temp['t4']=$sheet->shortName;
                $temp['t7']=$sheet->designationName;
                $temp['t20']=$t28;
                $temp['t37']=$sheet->bankAccountNo;
                $temp['t38']=$sheet->bankIFSCCode;
                $temp['t39']=$sheet->bankName;
                $temp['t40']=$sheet->branchName;
                array_push($employees, $temp);
            }
        }

        $employees = collect($employees);
        $this->rowCount = count($employees);
        return $employees;

    }

    public function styles(Worksheet $sheet)
    {
        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();

            // Skip header row (usually row 1)
            if ($rowIndex === 1) {
                continue;
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true); // Optional optimization

            // Get the value from column AI (which is column 35)
            $cellValue = $sheet->getCell('AI' . $rowIndex)->getValue();

            if ($cellValue == 'Hold') {
                $sheet->getStyle('A' . $rowIndex . ':AI' . $rowIndex)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'F4CCCC'], // Light red/pink
                    ],
                ]);
            } else {
                $sheet->getStyle('A' . $rowIndex . ':AI' . $rowIndex)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D9D9D9'], // Light gray
                    ],
                ]);
            }
        }
    }

    public function headings(): array
    {
        $flag=$this->flag;
        if($flag == 1)
            return ["Sr No","Employee Name","Organisation","Code","Designation","Department","Category","Branch","Joining Date","Gross Salary","Per Day", "Total Days in Month","Present Days","WF","Absent Days","Extra Working","Gross Payable Salary","WL Amount","Advance against Salary","Other Deductions","TDS","MLWF","ESIC","PT","PF","Retention", "Total Deductions","Extra Work Salary","Net Salary","A/C No","IFSC CODE","Bank","Branch","Remark", "Status"];
        else   
            return ["Sr No", "Employee Name", "Code", "Branch", "Designation", "Net Amount", "A/C No", "IFSC CODE", "Bank & Branch", "Branch", "Remark"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:AI1')                                
                ->getFont()
                ->setSize(12)
                ->setBold(true)
                ->getColor()
                ->setARGB('DD4B39');

                $totalRow = $this->rowCount+2;
                $event->sheet->setCellValue('A' . $totalRow, 'Total'); // Label for Total row
                $event->sheet->setCellValue('J' . $totalRow, "=ROUND(SUM(J2:J" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('K' . $totalRow, "=ROUND(SUM(K2:K" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('Q' . $totalRow, "=ROUND(SUM(Q2:Q" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('R' . $totalRow, "=ROUND(SUM(R2:R" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('S' . $totalRow, "=ROUND(SUM(S2:S" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('T' . $totalRow, "=ROUND(SUM(T2:T" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('U' . $totalRow, "=ROUND(SUM(U2:U" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('V' . $totalRow, "=ROUND(SUM(V2:V" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('W' . $totalRow, "=ROUND(SUM(W2:W" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('X' . $totalRow, "=ROUND(SUM(X2:X" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('Y' . $totalRow, "=ROUND(SUM(Y2:Y" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('Z' . $totalRow, "=ROUND(SUM(Z2:Z" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('AA' . $totalRow, "=ROUND(SUM(AA2:AA" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('AB' . $totalRow, "=ROUND(SUM(AB2:AB" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column
                $event->sheet->setCellValue('AC' . $totalRow, "=ROUND(SUM(AC2:AC" . ($totalRow - 1) . "),0)"); // Sum Total Salary Column



                $cells = 'A1:AD'.($this->rowCount+2);
                $event->sheet->getStyle($cells)->getAlignment()->setHorizontal('left');

                $event->sheet->getDelegate()->freezePane('A2');
                $event->sheet->getDelegate()->freezePane('B1');
                $event->sheet->getDelegate()->freezePane('C1');
                $event->sheet->getDelegate()->freezePane('D1');
                $event->sheet->getDelegate()->freezePane('E1');

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                $event->sheet->getStyle('A1:AI1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
                
                $cells = 'A1:AI'.($this->rowCount+2);
                $event->sheet->getStyle($cells)->applyFromArray($styleArray);

                $cells1 = 'B1:I'.($this->rowCount+2);
                $cells2 = 'J1:AC'.($this->rowCount+2);
                $event->sheet->getStyle($cells1)->getAlignment()->setHorizontal('left');
                $event->sheet->getStyle($cells2)->getAlignment()->setHorizontal('center');
               

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('G')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('H')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('I')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('J')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('K')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('L')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('M')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('N')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('O')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('P')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Q')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('R')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('S')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('T')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('U')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('V')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('W')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('X')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Y')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Z')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AA')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AB')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AC')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AD')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AE')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AF')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AG')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AH')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AI')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('G')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('H')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('I')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('J')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('K')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('L')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('M')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('N')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('O')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('P')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('Q')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('R')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('S')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('T')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('U')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('V')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('W')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('X')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('Y')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('Z')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AA')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AB')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AC')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AD')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AE')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AF')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AG')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AH')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AI')->getFont()->setSize(14);

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getColumnDimension('J')->setAutoSize(true);
                $event->sheet->getColumnDimension('K')->setAutoSize(true);
                $event->sheet->getColumnDimension('L')->setAutoSize(true);
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getColumnDimension('N')->setAutoSize(true);
                $event->sheet->getColumnDimension('O')->setAutoSize(true);
                $event->sheet->getColumnDimension('P')->setAutoSize(true);
                $event->sheet->getColumnDimension('Q')->setAutoSize(true);
                $event->sheet->getColumnDimension('R')->setAutoSize(true);
                $event->sheet->getColumnDimension('S')->setAutoSize(true);
                $event->sheet->getColumnDimension('T')->setAutoSize(true);
                $event->sheet->getColumnDimension('U')->setAutoSize(true);
                $event->sheet->getColumnDimension('V')->setAutoSize(true);
                $event->sheet->getColumnDimension('W')->setAutoSize(true);
                $event->sheet->getColumnDimension('X')->setAutoSize(true);
                $event->sheet->getColumnDimension('Y')->setAutoSize(true);
                $event->sheet->getColumnDimension('Z')->setAutoSize(true);
                $event->sheet->getColumnDimension('AA')->setAutoSize(true);
                $event->sheet->getColumnDimension('AB')->setAutoSize(true);
                $event->sheet->getColumnDimension('AC')->setAutoSize(true);
                $event->sheet->getColumnDimension('AD')->setAutoSize(true);
                $event->sheet->getColumnDimension('AE')->setAutoSize(true);
                $event->sheet->getColumnDimension('AF')->setAutoSize(true);
                $event->sheet->getColumnDimension('AG')->setAutoSize(true);
                $event->sheet->getColumnDimension('AH')->setAutoSize(true);
                $event->sheet->getColumnDimension('AI')->setAutoSize(true);
            },
        ];
    }

    
}

