<?php

namespace App\Exports;
use App\Designation;
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

class DesignationExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{
    private $active;
    private $rowCount;

    public function __construct($active, $rowCount)
    {
        $this->active = $active;
        $this->rowCount = $rowCount;
    }

    public function columnFormats(): array
    {
        return [
            'AC' => NumberFormat::FORMAT_NUMBER
        ];
    }

    public function collection()
    {
        $util = new Utility();
        $active=$this->active;
        $rowCount=$this->rowCount;

        $designations = Designation::join('departments', 'designations.departmentId', 'departments.id')
        ->select('departments.id','departments.name as departmentName', 'designations.name')
        ->where('designations.active', $active)
        ->get();

        $temp=$test=[];
        $i=1;
        foreach($designations as $designation)
        {
            $designation['id'] = $i++;
            $designation['departmentName'] = $designation->departmentName;
            $designation['name'] = $designation->name;
        }

        $designations = collect($designations);
        $this->rowCount = count($designations);
        return $designations;
    }

    public function headings(): array
    {
        return ["No","Department Name","Designation Name"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:C1')                                
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

                $event->sheet->getStyle('A1:C1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
                
                $cells = 'A1:C'.($this->rowCount+1);
                $event->sheet->getStyle($cells)->applyFromArray($styleArray);

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
            },
        ];
    }

}

