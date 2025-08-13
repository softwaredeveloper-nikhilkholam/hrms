<?php

namespace App\Exports\stores;
use App\StoreVendor;
use Auth;
use DB;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithEvents
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

        $temps =  StoreProduct::join('store_categories', 'store_products.categoryId', 'store_categories.id')
        ->join('store_sub_categories', 'store_products.subCategoryId', 'store_sub_categories.id')
        ->join('store_units', 'store_products.unitId', 'store_units.id')
        ->join('store_halls', 'store_products.hallId', 'store_halls.id')
        ->join('store_racks', 'store_products.rackId', 'store_racks.id')
        ->join('store_shels', 'store_products.shelfId', 'store_shels.id')
        ->select('store_categories.id as srNo', "store_products.name",'store_categories.name as categoryName', 
        'store_sub_categories.name as subCategoryName', 'store_products.size', 'store_products.color','store_halls.name as hallName',
         'store_racks.name as rackName', 'store_shels.name as shelfName','store_products.stock','store_products.productRate',
         'store_products.id as valuation')
        ->where('store_products.active', $active);
        if($search != "-")
            $temps = $temps->where('store_products.name', 'like', '%' . $search . '%');

        $temps = $temps->orderBy('store_products.name')
        ->get();
        $i=1;
        $temProduct=$products=[];
        foreach($temps as $product)
        {
            $temProduct['srNo'] =  $i++;
            $temProduct['Product'] =  $product->name;
            $temProduct['Category'] =  $product->categoryName;
            $temProduct['Sub_Category'] =  $product->subCategoryName;
            $temProduct['Size'] =  $product->size;
            $temProduct['Color'] =  $product->color;
            $temProduct['Hall'] =  $product->hallName;
            $temProduct['Rack'] =  $product->rackName;
            $temProduct['Shelf'] =  $product->shelfName;
            $temProduct['Stock'] =  $product->stock;
            $temProduct['valuation'] =  round($product->stock*$product->productRate);

            array_push($products, $temProduct);
        }
        $products = collect($products);
        $this->rowCount = count($products);
        return $products;
    }

    public function headings(): array
    {
        return ["No.","Category","Name", "Type Of Company", "Address", "PANNO", "Whatsapp No", "Landline No", "Contact Person 1", "Contact Person No 1", "Contact Person 2",  "Email", "Material Provider", "AccountNo", "IFSCCode", "bankBranch", "GSTNo" , "outstandingRs", "rating", "AddedBy", "Last Updated"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:V1')                                
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
                $event->sheet->getDelegate()->getStyle('J')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('K')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('L')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('M')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('N')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('O')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('P')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Q')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('R')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('S')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('T')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('U')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('V')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('G')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('H')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('I')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('J')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('K')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('L')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('M')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('N')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('O')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('P')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('Q')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('R')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('S')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('T')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('U')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('V')->getFont()->setSize(14);

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getColumnDimension('J')->setAutoSize(true);
                $event->sheet->getColumnDimension('K')->setAutoSize(true);
                $event->sheet->getColumnDimension('L')->setAutoSize(true);
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getColumnDimension('N')->setAutoSize(true);
                $event->sheet->getColumnDimension('O')->setAutoSize(true);
                $event->sheet->getColumnDimension('P')->setAutoSize(true);
                $event->sheet->getColumnDimension('Q')->setAutoSize(true);
                $event->sheet->getColumnDimension('R')->setAutoSize(true);
                $event->sheet->getColumnDimension('S')->setAutoSize(true);
                $event->sheet->getColumnDimension('T')->setAutoSize(true);
                $event->sheet->getColumnDimension('U')->setAutoSize(true);
                $event->sheet->getColumnDimension('V')->setAutoSize(true);
            },
        ];
    }

}

