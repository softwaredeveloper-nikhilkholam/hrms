<?php

namespace App\Exports;

use App\EmpMr;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinalAttendanceExport implements FromCollection, WithHeadings, WithEvents
{
    private $month;
    private $branchId;

    public function __construct($month, $branchId)
    {
        $this->month = $month;
        $this->branchId = $branchId;
    }
    
    public function collection()
    {
        $month=$this->month;
        $branchId=$this->branchId;
        
        $attendance = EmpMr::join('emp_dets','emp_mrs.empId', 'emp_dets.id')
        ->join('departments','emp_dets.departmentId', 'departments.id')
        ->join('designations','emp_dets.designationId', 'designations.id')
        ->select('emp_dets.empCode', 'emp_dets.name', 'departments.name as departmentName',
        'designations.name as designationName', 'emp_mrs.totPresent', 'emp_mrs.totAbsent',
        'emp_mrs.extraWorking', 'emp_mrs.totalDays')
        ->where('emp_mrs.forDate', $month)
        ->where('emp_mrs.branchId', $branchId)
        ->get();

        return collect($attendance);
    }

    public function headings(): array
    {
        return ["Employee Code", "Employee Name","Department", "Designation", "Present Days", "Absent Days", "Extra Working", "Pay Days"];
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

            },
        ];
    }
}
