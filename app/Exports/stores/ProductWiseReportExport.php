<?php
namespace App\Exports\stores;

use App\StoreOutward;
use App\StoreOutwardProdList;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductWiseReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $forMonth;
    protected $productId;


    // Constructor to pass filters
    public function __construct($forMonth, $productId)
    {
        $this->forMonth = $forMonth;
        $this->productId = $productId;
    }

    // Get data for export
    public function collection()
    {
        $forMonth = $this->forMonth ?? date('Y-m'); // Default to current month if empty
        $productId = $this->productId;

        // Calculate start and end date for the selected month
        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));

        if (!$productId) {
            // Query when no specific product is selected
            $reports = StoreOutward::join('store_requisitions', 'store_outwards.requisitionId', '=', 'store_requisitions.id')
                ->join('users', 'store_requisitions.userId', '=', 'users.id')
                ->select(
                    'store_outwards.forDate',
                    'store_requisitions.status',
                    'store_outwards.branchName',
                    'store_outwards.receiptNo',
                    'users.name'
                )
                ->whereBetween('store_outwards.forDate', [$startDate, $endDate])
                ->get();
        } else {
            // Query when a specific product is selected
            $reports = StoreOutwardProdList::join('store_outwards', 'store_outward_prod_lists.outwardId', '=', 'store_outwards.id')
                ->join('store_requisitions', 'store_outwards.requisitionId', '=', 'store_requisitions.id')
                ->join('users', 'store_requisitions.userId', '=', 'users.id')
                ->select(
                    'store_outwards.forDate',
                    'store_requisitions.status',
                    'store_outwards.branchName',
                    'store_outwards.receiptNo',
                    'users.name'
                )
                ->where('store_outward_prod_lists.actualProductId', $productId)
                ->whereBetween('store_outwards.forDate', [$startDate, $endDate])
                ->get();
        }
        return $reports;
    }

    public function headings(): array
    {
        return [
            'Date', 'Branch', 'Employee', 'Outward No', 'Status'
        ];
    }

    public function map($product): array
    {
        return [
           date('d-m-Y',strtotime($product->forDate)),
            $product->branchName,
            $product->name,
            $product->receiptNo,
            ($product->status == 0)?'Pending':(($product->status == 1)?'Outward Generated':(($product->status == 2)?'Rejected':'InProgress'))
            // 0 - Pending, 1 - Outward Generated, 2 - Rejected, 3 - Hold, 4-Cancel, 5-InProgress	
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
