<?php

namespace App\Exports;
use App\Appointment;
use App\Helpers\Utility;

use Auth;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AppointmentExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{

    public function __construct()
    {
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
        
        $appointments = Appointment::join('emp_dets', 'appointments.empId', 'emp_dets.id')
        ->join('users', 'appointments.appointwith', 'users.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('appointments.id','appointments.forDate', 'appointments.priority','emp_dets.name',  'departments.name as departmentName',  'appointments.reason', 
        'emp_dets.name', 'appointments.status', 'appointments.meetingStatus', 'users.name as appointWith')
        ->where('appointments.active', 1)
        ->orderBy('appointments.status')
        ->get();
        $i=1;
        foreach($appointments as $appoint)
        {
            $appoint['id']=$i++;
            $appoint['forDate']=date('d-m-Y', strtotime($appoint->forDate));
            $appoint['status']=($appoint->status == 1)?"Pending":(($appoint->status == 2)?"Approved":(($appoint->status == 3)?"Postpone":"Rejected"));
        }
        $appointments = collect($appointments);
        $this->appointments = count($appointments);
        return $appointments;
    }

    public function headings(): array
    {
        return ["No","Date","Priority", "Employee Name","Department Name", "Agenda", "Status", "Meeting Update", 'Appointment With'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:H1')                                
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
                
                $cells = 'A1:H'.($this->rowCount+1);
                $event->sheet->getStyle($cells)->applyFromArray($styleArray);

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('G')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('H')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('G')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('H')->getFont()->setSize(14);

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
            },
        ];
    }

}

