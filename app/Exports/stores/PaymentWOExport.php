<?php

namespace App\Exports\stores;

use App\StoreWorkOrderPayment;
use Auth;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PaymentWOExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting, WithStyles
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    private $status;
    private $forMonth;
    private $typeOfCompanyId;
    private $rowCount;

    public function __construct($forMonth, $status, $typeOfCompanyId)
    {
        $this->status = $status;
        $this->forMonth = $forMonth;
        $this->typeOfCompanyId = $typeOfCompanyId;
    }

    public function collection()
    {
        $status = $this->status;
        $forMonth = $this->forMonth;
        $typeOfCompanyId = $this->typeOfCompanyId;

        $startDate = date('Y-m-01', strtotime($forMonth));
        $endDate = date('Y-m-t', strtotime($forMonth));
        $user = Auth::user();
        $userType = $user->userType;

        $paymentsQuery = StoreWorkOrderPayment::join('store_work_orders', 'store_work_order_payments.orderId', '=', 'store_work_orders.id')
            ->join('store_vendors', 'store_work_orders.vendorId', '=', 'store_vendors.id')
            ->join('type_of_companies', 'store_work_orders.typeOfCompany', '=', 'type_of_companies.id')
            ->join('contactus_land_pages', 'store_work_orders.branchId', '=', 'contactus_land_pages.id')
            ->select(
                'store_work_order_payments.*',
                'store_vendors.name as vendorName',
                'contactus_land_pages.branchName',
                'store_vendors.accountNo',
                'store_vendors.IFSCCode',
                'type_of_companies.shortName as typeOfCompanyName',
                'store_work_orders.billNo',
                'store_work_orders.WOFor',
                'store_work_orders.finalRs'
            )
            ->where('store_work_order_payments.status', $status)
            ->where('store_work_order_payments.active', 1);

        if ($status != 1) {
            $paymentsQuery = $paymentsQuery->whereBetween('store_work_order_payments.forDate', [$startDate, $endDate]);
        }

        if ($userType == '801') {
            $paymentsQuery->where('store_work_orders.raisedBy', $user->id);
        } else {
            if (!empty($user->typeOfCompany)) {
                $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                $paymentsQuery->whereIn('store_work_orders.typeOfCompany', $typeOfCompany);
            }
        }

        if ($status != 1 && $typeOfCompanyId != '0') {
            $paymentsQuery->where('store_work_orders.typeOfCompany', $typeOfCompanyId);
        }

        $payments = $paymentsQuery->orderBy('store_work_order_payments.forDate')->get();
        $this->rowCount = $payments->count();

        $serial = 1;
        return $payments->map(function ($item) use (&$serial) {
            return [
                $serial++,
                $item->poNumber,
                $item->typeOfCompanyName,
                $item->branchName,
                $item->vendorName,
                "'" . $item->accountNo,
                $item->IFSCCode,
                $item->amount,
                $item->percent . '%',
                $item->WOFor,
                \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(new \DateTime($item->forDate)),
                $item->updated_at ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($item->updated_at) : null,
            ];
        });
    }

    public function headings(): array
    {
        return [
            "No.", "WO No", "Type of Company", "Branch Name", "Vendor Name", "Account Number", "IFSC Code", "Amount", "Percentage", "WO For", "Payment Date", "Updated At"
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_TEXT,              // Account Number
            'H' => NumberFormat::FORMAT_NUMBER_00,         // Amount
            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,     // Payment Date
            'L' => NumberFormat::FORMAT_DATE_DDMMYYYY,     // Updated At
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A' => ['alignment' => ['horizontal' => 'center']],
            'B' => ['alignment' => ['horizontal' => 'center']],
            'C' => ['alignment' => ['horizontal' => 'center']],
            'D' => ['alignment' => ['horizontal' => 'center']],
            'E' => ['alignment' => ['horizontal' => 'left']],    // Vendor Name
            'F' => ['alignment' => ['horizontal' => 'left']],    // ✅ Account Number
            'G' => ['alignment' => ['horizontal' => 'center']],
            'H' => ['alignment' => ['horizontal' => 'right']],   // Amount
            'I' => ['alignment' => ['horizontal' => 'center']],
            'J' => ['alignment' => ['horizontal' => 'left']],    // ✅ WO For
            'K' => ['alignment' => ['horizontal' => 'center']],  // Payment Date
            'L' => ['alignment' => ['horizontal' => 'center']],  // Updated At
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header styling
                $sheet->getStyle('A1:L1')->getFont()->setSize(12)->setBold(true)->getColor()->setARGB('DD4B39');
                $sheet->getStyle('A1:L1')->getFill()->applyFromArray([
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => 'D9D9D9'],
                ]);
                $sheet->getRowDimension('1')->setRowHeight(40);
                $sheet->freezePane('A2');

                // Apply borders
                $sheet->getStyle('A1:L' . ($this->rowCount + 1))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Set column font and auto width
                foreach (range('A', 'L') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                    $sheet->getStyle($col)->getFont()->setName('Times New Roman');
                }

                // Set font size for data rows
                $sheet->getStyle('A2:L' . ($this->rowCount + 1))->getFont()->setSize(14);

                // [Optional] Conditional row background colors — only works if a 'status' column is present.
                // Currently skipped as no such column is exported.
            },
        ];
    }
}
