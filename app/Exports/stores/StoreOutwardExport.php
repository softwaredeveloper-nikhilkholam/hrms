<?php
namespace App\Exports\stores;

use App\StoreOutward;
use App\StoreProductLedger;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class OutwardReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $forMonth;


    // Constructor to pass filters
    public function __construct($forMonth)
    {
        $this->forMonth = $forMonth;
    }

    // Get data for export
    public function collection()
    {
        $startDate = date('Y-m-01', strtotime($this->forMonth)); // First day of the month
        $endDate = date('Y-m-t', strtotime($this->forMonth));   // Last day of the month
        
        $reports = StoreOutward::join('store_requisitions', 'store_outwards.requisitionId', '=', 'store_requisitions.id')
        ->join('users', 'store_requisitions.userId', '=', 'users.id')
        ->select(
            'store_outwards.forDate',
            'store_outwards.status',
            'store_outwards.branchName',
            'store_outwards.receiptNo',
            'users.name'
        )
        ->whereBetween('store_outwards.forDate', [$startDate, $endDate])
        ->get();

        return $reports->getCollection();
    }

    public function headings(): array
    {
        return [
            'Date', 'Branch', 'Employee', 'Outward No', 'Status'
        ];
    }

    public function map($reports): array
    {
        return [
            $reports->forDate,
            $reports->branchName,
            $reports->name
            $reports->receiptNo,
            $reports->status,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:F1')                                
                ->getFont()
                ->setSize(12)
                ->setBold(true)
                ->getColor()
                ->setARGB('DD4B39');


                $event->sheet->getDelegate()->getStyle('A')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setSize(14);

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
            },
        ];
    }
}
