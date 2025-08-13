<?php

namespace App\Exports\stores;

use App\FuelVehicle;
use App\FuelFilledEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FuelExport implements FromCollection, WithHeadings, WithEvents
{
    private $id;
    private $rowCount;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        $id = $this->id;
        $data = [];

        $vehicles = FuelVehicle::join('store_products', 'fuel_vehicles.vehicleId', 'store_products.id')
            ->select('fuel_vehicles.*', 'store_products.name as busNo')
            ->where('fuel_vehicles.fuelEntryId', $id)
            ->where('fuel_vehicles.active', 1)
            ->orderBy('fuel_vehicles.created_at', 'desc')
            ->get();

        $i = 1;
        foreach ($vehicles as $row) {
            $km = $row->newKM - $row->oldKM;
            $amount = $row->ltr * $row->fuelRate;

            $data[] = [
                'SrNo' => $i++,
                'Bus No' => $row->busNo,
                'Old KM' => $row->oldKM,
                'New KM' => $row->newKM,
                'KM' => $km,
                'Litres' => $row->ltr,
                'Amount' => $amount,
                'Average' => $row->average
            ];
        }

        $this->rowCount = count($data);

        return collect($data);
    }

    public function headings(): array
    {
        return ["Sr.No.", "Bus No", "Old KM", "New KM", "KM Travelled", "Litres Filled", "Amount", "Average"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insert 2 rows at the top for title and info
                $sheet->insertNewRowBefore(1, 2);

                // === Title Row (Row 1) ===
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', 'Aarayans World School Fuel Entry Details');
                $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // === Info Row (Row 2) ===
                $fuelEntry = \App\FuelFilledEntry::join('contactus_land_pages', 'fuel_filled_entries.branchId','contactus_land_pages.id')
                ->select('fuel_filled_entries.*', 'contactus_land_pages.branchName as branchName', 'contactus_land_pages.zoneName')
                ->where('fuel_filled_entries.id', $this->id)
                ->first();

                $fuelRate = $fuelEntry->petrolRate != 0 ? $fuelEntry->petrolRate : $fuelEntry->dieselRate;
                $zoneName = $fuelEntry->zoneName ?? 'N/A';
                $infoText = 'Date: ' . date('d-m-Y', strtotime($fuelEntry->forDate)) .
                            '   |   Fuel Rate: ₹' . $fuelRate .
                            '   |   Zone: ' . $zoneName;

                $sheet->mergeCells('A2:H2');
                $sheet->setCellValue('A2', $infoText);
                $sheet->getStyle('A2')->getFont()->setSize(12);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // === Header Row (Row 3) ===
                $sheet->getStyle('A3:H3')->getFont()->setBold(true)->setSize(12)->getColor()->setARGB('DD4B39');

                // === Data Styling (From Row 4 onward) ===
                foreach (range('A', 'H') as $col) {
                    $sheet->getStyle("{$col}4:{$col}" . ($this->rowCount + 3))
                        ->getFont()->setName('Times New Roman')->setSize(14);
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // === Table Borders (Headers + Data) ===
                $borderRange = 'A3:H' . ($this->rowCount + 3);
                $sheet->getStyle($borderRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
            }
        ];
    }

    

}
