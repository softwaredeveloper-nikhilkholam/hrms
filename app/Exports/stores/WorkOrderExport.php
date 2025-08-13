<?php

namespace App\Exports\stores;
use App\StoreWorkOrder;
use App\Helpers\Utility;
use Auth;
use DB;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WorkOrderExport implements FromCollection, WithHeadings, WithEvents
{
    private $vendorId;
    private $raisedBy;
    private $status;
    private $rowCount;

    public function __construct($vendorId, $raisedBy, $status)
    {
        $this->vendorId = $vendorId;
        $this->raisedBy = $raisedBy;
        $this->status = $status;
    }

    public function collection()
    {
        $vendorId=$this->vendorId;     
        $raisedBy=$this->raisedBy;     
        $status=$this->status;     

        $orders=StoreWorkOrder::join('store_vendors', 'store_work_orders.vendorId', 'store_vendors.id')
        ->join('contactus_land_pages', 'store_work_orders.branchId', 'contactus_land_pages.id')
        ->join('type_of_companies', 'store_work_orders.typeOfCompany', 'type_of_companies.id')
        ->join('users', 'store_work_orders.raisedBy', 'users.id')
        ->select('store_work_orders.id', 'store_work_orders.commWONo', 'store_vendors.name as vendorName', 
        'store_work_orders.WOFor','store_work_orders.finalRs',
        'users.name as username','store_work_orders.alreadyPaid', 'store_work_orders.forDate');

        if($vendorId != 0)
            $orders = $orders->where('store_vendors.id', $vendorId);

        if($raisedBy != 0)
            $orders = $orders->where('store_vendors.raisedBy', $raisedBy);

        $orders = $orders->orderBy('store_work_orders.created_at')
        ->where('store_work_orders.WOStatus', $status)
        ->get();
        $util=new Utility(); 

        $i=1;
        foreach($orders as $order)
        {
            $order['id'] =  $i++;
            $order->finalRs =  $util->numberFormat($order->finalRs);
            $order->alreadyPaid =  ($order->alreadyPaid == 1)?'Yes':'No';
            $order->forDate =  date('d-m-Y', strtotime($order->forDate));
        }

        $orders = collect($orders);
        $this->rowCount = count($orders);
        return $orders;
    }

    public function headings(): array
    {
        return ["No.","WO GP No","Vendor Details", "Requisition For", "Final Rs.", "Raised By", "Already Paid", "Generated At"];
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

