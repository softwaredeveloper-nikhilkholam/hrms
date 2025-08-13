<?php

namespace App\Exports;
use App\Retention;
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

class RetentionExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{
    private $branchId;

    public function __construct($branchId)
    {
        $this->branchId = $branchId;
        $this->rowCount = 0;
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
        $branchId=$this->branchId;
        $rowCount=$this->rowCount;

        $histories = Retention::join('emp_dets', 'retentions.empId', 'emp_dets.id')
        ->select('retentions.id','emp_dets.empCode', 'emp_dets.name',  'retentions.month', 'retentions.retentionAmount')
        ->where('branchId', $branchId)
        ->get();

        $temp=$test=[];$i=1;
        $total=0;
        foreach($histories as $row)
        {
            $row['id'] = $i++;
            $row['empCode'] = $row->empCode;
            $row['name'] = $row->name;
            $row['month'] = date('M-Y', strtotime($row->month));
            $row['retentionAmount'] = $row->retentionAmount;
            $total = $total + $row->retentionAmount;
        }

        // $temp['id']=$temp['empCode']=$temp['name']='';
        // $temp['month'] = "Total";
        // $temp['retentionAmount'] = $total;
       
        // array_push($histories, $temp);

        $histories = collect($histories);
        $this->rowCount = count($histories);
        return $histories;
    }

    public function headings(): array
    {
        return ["No","Emp Code","Name", "Month", "Retention Amount"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:E1')                                
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

                $event->sheet->getStyle('A1:E1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
                
                $cells = 'A1:E'.($this->rowCount+1);
                $event->sheet->getStyle($cells)->applyFromArray($styleArray);

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setSize(14);

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
            },
        ];
    }

}

