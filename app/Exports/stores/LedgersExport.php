<?php
namespace App\Exports\stores;

use Carbon\Carbon;
use App\Models\StoreProductLedger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class LedgersExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $ledgers;
    protected $openingStock;

    public function __construct($ledgers, $openingStock, $openingStockDate, $productName)
    {
        $this->ledgers = $ledgers;
        $this->openingStock = $openingStock;
        $this->openingStockDate = $openingStockDate;
        $this->productName = $productName;
    }

    /**
     * Export the ledger data
     */
    public function collection()
    {
        return $this->ledgers;

    }

    /**
     * Define column headings
     */
    public function headings(): array
    {
        return [
            ["Opening Stock: " , $this->openingStock],
            ["Opening Stock Date: " , $this->openingStockDate],
            ["Product Name: " , $this->productName],
            [""], // Empty row
            ["", "ID", "For Date", "Type", "Inward Qty", "Outward Qty","Return Qty", "Opening Stock", "Closing Stock"] // Actual column headings
        ];

    }

    public function map($ledger): array
    {
        return [
            "",
            $ledger->id,
            date('d-m-Y', strtotime($ledger->forDate)),
            $ledger->type == 1 ? 'Inward' : 'Outward',
            $ledger->inwardQty,
            $ledger->outwardQty,
            $ledger->returnQty,
            $ledger->openingStock,
            $ledger->closingStock,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:I1')                                
                ->getFont()
                ->setSize(12)
                ->setBold(true)
                ->getColor()
                ->setARGB('DD4B39');

                $event->sheet->getDelegate()
                ->getStyle('A2:I2')                                
                ->getFont()
                ->setSize(12)
                ->setBold(true)
                ->getColor()
                ->setARGB('DD4B39');

                $event->sheet->getDelegate()
                ->getStyle('A3:I3')                                
                ->getFont()
                ->setSize(12)
                ->setBold(true)
                ->getColor()
                ->setARGB('DD4B39');

                $event->sheet->getDelegate()
                ->getStyle('A5:I5')                                
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
                $event->sheet->getDelegate()->getStyle('I')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('G')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('H')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('I')->getFont()->setSize(14);

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
            },
        ];
    }
}
