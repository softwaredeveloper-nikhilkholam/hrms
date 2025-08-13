<?php

namespace App\Exports;
use App\Retention;
use App\Helpers\Utility;
use App\EmpDet;

use Auth;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExportNewJoinee implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{
    private $startDate;
    private $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->rowCount = 0;
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER
        ];
    }

    public function collection()
    {
        $util = new Utility();
        $startDate=$this->startDate;
        $endDate=$this->endDate;
        $rowCount=$this->rowCount;

        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.name', 'emp_dets.empCode','emp_dets.jobJoingDate','contactus_land_pages.branchName as branch',
        'designations.name as designationName','emp_dets.salaryScale', 
        'emp_dets.bankName',
        'emp_dets.bankAccountNo','emp_dets.bankIFSCCode','emp_dets.branchName as bankBranchName')
        ->where('emp_dets.active', 1)
        ->where('emp_dets.jobJoingDate', '>=', $startDate)
        ->where('emp_dets.jobJoingDate', '<=', $endDate)
        ->get();
        $i=1;
        // $temp=$test=[];
        // $total=0;
        foreach($employees as $row)
        {
            $row['id'] = $i++;
        //     $row['empCode'] = $row->empCode;
        //     $row['name'] = $row->name;
            $row['jobJoingDate'] = date('d-m-Y', strtotime($row->jobJoingDate));
        //     $row['retentionAmount'] = $row->retentionAmount;
        //     $total = $total + $row->retentionAmount;
        }

        // $temp['id']=$temp['empCode']=$temp['name']='';
        // $temp['month'] = "Total";
        // $temp['retentionAmount'] = $total;
       
        // array_push($histories, $temp);

        $employees = collect($employees);
        $this->rowCount = count($employees);
        return $employees;
    }

    public function headings(): array
    {
        return ["No","Employee Name","Employee Code", "Joning Date","Branch", "Designation", "Salary", "Bank Name", "Account No", "IFSC Code", "Bank Branch"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:K1')                                
                ->getFont()
                ->setSize(12)
                ->setBold(true)
                ->getColor()
                ->setARGB('DD4B39');

                $event->sheet->getDelegate()->freezePane('A2');

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                $event->sheet->getStyle('A1:K1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
                
                $cells = 'A1:K'.($this->rowCount+1);
                $event->sheet->getStyle($cells)->applyFromArray($styleArray);

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
            },
        ];
    }

}

