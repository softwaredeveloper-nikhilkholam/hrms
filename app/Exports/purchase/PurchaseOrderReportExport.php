<?php
namespace App\Exports\purchase;

use App\StorePurchaseOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class PurchaseOrderReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents
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
        $forMonth= $this->forMonth;
        if($forMonth == '')
            $forMonth = date('Y-m');
       
        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));

        $reports = StorePurchaseOrder::join('store_quotations', 'store_purchase_orders.quotationId', 'store_quotations.id')
        ->join('contactus_land_pages', 'store_quotations.shippingBranchId', 'contactus_land_pages.id')
        ->join('store_vendors', 'store_quotations.vendorId', 'store_vendors.id')
        ->join('users', 'store_quotations.raisedBy', 'users.id')
        ->select('store_purchase_orders.generatedDate', 'contactus_land_pages.branchName', 'users.name as raisedBy', 
        'store_vendors.name as vendorName', 'store_purchase_orders.poNumber','store_purchase_orders.poAmount','store_purchase_orders.paidAmount')
        ->where('store_quotations.status', 1)
        ->whereBetween('store_purchase_orders.generatedDate', [$startDate, $endDate])
        ->where('store_quotations.quotStatus', 'Approved')
        ->orderBy('store_quotations.created_at')
        ->get();

        return $reports;
    }

    public function headings(): array
    {
        return [
            'Date', 'Branch', 'Employee', 'Vendor Name', 'Payment Status', 'PO Number'
        ];
    }

    public function map($report): array
    {
        return [
           date('d-m-Y',strtotime($report->generatedDate)),
            $report->branchName,
            $report->raisedBy,
            $report->vendorName,
            (($report->poAmount - $report->paidAmount) == 0)?'Paid':(($report->paidAmount == 0)?'Unpaid':'Partially'),
            $report->poNumber
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
