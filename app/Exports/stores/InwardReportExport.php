<?php
namespace App\Exports\stores;

use App\Inward;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class InwardReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents
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
        $forMonth = $this->forMonth;
        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));

        $reports = Inward::join('store_purchase_orders', 'inwards.purchaseOrderId', '=', 'store_purchase_orders.id')
        ->join('store_quotations', 'store_purchase_orders.quotationId', '=', 'store_quotations.id')
        ->join('contactus_land_pages', 'store_quotations.shippingBranchId', '=', 'contactus_land_pages.id')
        ->select('inwards.forDate', 'inwards.poNumber', 'inwards.reqNo', 'contactus_land_pages.branchName', 'inwards.active')
        ->whereBetween('inwards.forDate', [$startDate, $endDate])
        ->orderBy('inwards.forDate')
        ->get();

        return $reports;
    }

    public function headings(): array
    {
        return [
            'Date', 'Branch', 'PO Number', 'Requisition No', 'Status'
        ];
    }

    public function map($reports): array
    {
        return [
           date('d-m-Y',strtotime($reports->forDate)),
            $reports->poNumber,
            $reports->reqNo,
            $reports->branchName,
            ($reports->active == 0)?'Approved':'Rejected'
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
