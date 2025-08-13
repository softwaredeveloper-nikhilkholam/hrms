<?php

namespace App\Exports\stores;
use App\StoreCategory;
use Auth;
use DB;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoryExport implements FromCollection, WithHeadings, WithEvents
{
    private $active;
    private $rowCount;

    public function __construct($active)
    {
        $this->active = $active;
    }

    public function collection()
    {
        $active=$this->active;     
        $temps=[];

        $temps = StoreCategory::whereActive($active)->orderBy('name')->get();
        $i=1;
        $tempData=$categories=[];
        foreach($temps as $row)
        {
            $tempData['srNo'] =  $i++;
            $tempData['category'] =  $row->name;
            $tempData['addedAt'] =  date('d-m-Y H:i', strtotime($row->created_at));
            $tempData['addedBy'] =  $row->updated_by;

            array_push($categories, $tempData);
        }
        $categories = collect($categories);
        $this->rowCount = count($categories);
        return $categories;
    }

    public function headings(): array
    {
        return ["No.","Category", "Added At", "Updated By"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:D1')                                
                ->getFont()
                ->setSize(12)
                ->setBold(true)
                ->getColor()
                ->setARGB('DD4B39');


                $event->sheet->getDelegate()->getStyle('A')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
            },
        ];
    }
}

