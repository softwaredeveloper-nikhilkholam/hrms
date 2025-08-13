<?php

namespace App\Exports;

use App\EmpDet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContractReportExport implements FromCollection, WithHeadings, WithEvents
{
    public function __construct()
    {
    }

    public function collection()
    {
        // $expiryDate = date('Y-m-d', strtotime('+5 days'));
        $employees1  = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id','emp_dets.empCode','emp_dets.name','departments.name as departmentName',
        'designations.name as designationName','emp_dets.contractStartDate','emp_dets.contractEndDate')
        ->where('emp_dets.active', 1)
        ->whereNull('emp_dets.contractEndDate')
        ->orderBy('emp_dets.contractEndDate')
        ->get();


        $employees2  = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.id','emp_dets.empCode','emp_dets.name','departments.name as departmentName',
        'designations.name as designationName','emp_dets.contractStartDate','emp_dets.contractEndDate')
        // ->where('emp_dets.contractEndDate', '<=', $expiryDate)
        ->where('emp_dets.active', 1)
        ->whereNotNull('emp_dets.contractEndDate')
        ->orderBy('emp_dets.contractEndDate')
        ->get();

        $employees1 = Collect($employees1);
        $employees2 = Collect($employees2);
        $employees = $employees1->merge($employees2);
        $i=1;
        foreach($employees as $row)
        {
            $row['id'] = $i++;
            $row->empCode = 'AWS'.$row->empCode;
            $row->contractStartDate = ($row->contractStartDate == '' || $row->contractStartDate == '0' || $row->contractStartDate == 'null')?'-':date('d-m-Y', strtotime($row->contractStartDate));
            $row->contractEndDate= ($row->contractEndDate == '' || $row->contractEndDate == '0' || $row->contractEndDate == 'null')?'-':date('d-m-Y', strtotime($row->contractEndDate));
        }

        return $employees;
    }

    public function headings(): array
    {
        return ["Sr. No.", "Emp Code", "Employee Name","Department", "Designation", "Contract Start Date", "Contract End Date"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:AL1')                                
                                ->getFont()
                                ->setSize(12)
                                ->setBold(true)
                                ->getColor()
                                ->setARGB('DD4B39');

                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(10);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(30);

            },
        ];
    }
}


