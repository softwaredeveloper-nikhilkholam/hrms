<?php
namespace App\Exports\stores;

use App\StoreProduct;
use App\StoreProductLedger;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class EODProductReportExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $productId;
    protected $forDate;
    protected $pageNumber;


    // Constructor to pass filters
    public function __construct($productId, $forDate, $pageNumber)
    {
        $this->productId = $productId;
        $this->pageNumber = $pageNumber;
        $this->forDate = $forDate ?: date('Y-m-d'); // Default to today
    }

    // Get data for export
    public function collection()
    {
        // Fetch Products with Pagination
        $productsQuery = StoreProduct::select('id', 'name', 'productCode', 'openingStockForDate', 'openingStock', 'stock')
            ->where('openingStockForDate', '<=', $this->forDate);

        if ($this->productId) {
            $productsQuery = $productsQuery->where('id', $this->productId);
        }

        // Paginate Products (using page number)
        $products = $productsQuery->orderBy('name')->paginate(10, ['*'], 'page', $this->pageNumber);

        // Get Product IDs
        $productIds = $products->pluck('id')->toArray();

        // Get Stock Data in Bulk
        $ledgerData = StoreProductLedger::whereIn('productId', $productIds)
            ->where('forDate', '<=', date('Y-m-d', strtotime('-1 day', strtotime($this->forDate))))
            ->groupBy('productId')
            ->selectRaw('productId, SUM((inwardQty + returnQty) - outwardQty) AS total')
            ->pluck('total', 'productId');

        $todayStockData = StoreProductLedger::whereIn('productId', $productIds)
            ->where('forDate', $this->forDate)
            ->groupBy('productId')
            ->selectRaw('productId, SUM((inwardQty + returnQty) - outwardQty) AS total')
            ->pluck('total', 'productId');

        // Transform Data with Stock Values
        $products->getCollection()->transform(function ($product) use ($ledgerData, $todayStockData) {
            $previousStock = $ledgerData[$product->id] ?? 0;
            $todayStock = $todayStockData[$product->id] ?? 0;

            $product->newOpeningStock = (($previousStock + $product->openingStock)==0)?'0':($previousStock + $product->openingStock);
            $product->newClosingStock = ($todayStock == 0)?'0':$todayStock;

            return $product;
        });

        return $products->getCollection();
    }

    public function headings(): array
    {
        return [
            'Product ID', 'Product Name', 'Product Code', 'Opening Stock', 'Closing Stock', 'Count'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->productCode,
            $product->newOpeningStock,
            $product->newClosingStock,
           ((($product->newClosingStock +  $product->newOpeningStock) - $product->newClosingStock) == 0)?'0':(($product->newClosingStock +  $product->newOpeningStock) - $product->newClosingStock)
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
