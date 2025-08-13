<?php

namespace App\Exports\stores;

use App\StoreQuotationPayment;
use App\TypeOfCompany;
use Auth;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\Exportable;

class PaymentExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting, WithStyles
{
    use Exportable;

    private $status;
    private $forMonth;
    private $typeOfCompanyId;
    private $rowCount = 0;

    public function __construct($forMonth, $status = null, $typeOfCompanyId = null)
    {
        $this->status = $status;
        $this->forMonth = $forMonth;
        $this->typeOfCompanyId = $typeOfCompanyId;
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_TEXT,           // Account Number
            'H' => NumberFormat::FORMAT_NUMBER_00,      // Amount
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Payment Date
            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Updated At
        ];
    }

    public function collection()
    {
        $status = $this->status;
        $forMonth = $this->forMonth;
        $typeOfCompanyId = $this->typeOfCompanyId;

        $user = Auth::user();
        $userType = $user->userType;

        $startDate = Carbon::parse($forMonth)->startOfMonth()->toDateString();
        $endDate = Carbon::parse($forMonth)->endOfMonth()->toDateString();

        $paymentsQuery = StoreQuotationPayment::join('store_quotations', 'store_quotation_payments.quotationId', '=', 'store_quotations.id')
            ->join('store_vendors', 'store_quotations.vendorId', '=', 'store_vendors.id')
            ->join('type_of_companies', 'store_quotations.typeOfCompany', '=', 'type_of_companies.id')
            ->join('contactus_land_pages', 'store_quotations.shippingBranchId', '=', 'contactus_land_pages.id')
            ->select(
                'store_quotation_payments.poNumber',
                'type_of_companies.shortName as typeOfCompanyName',
                'contactus_land_pages.branchName',
                'store_vendors.name as vendorName',
                'store_vendors.accountNo',
                'store_vendors.IFSCCode',
                'store_quotation_payments.percent',
                'store_quotation_payments.amount',
                'store_quotations.quotationFor',
                'store_quotation_payments.forDate',
                'store_quotation_payments.updated_at'
            )->where('store_quotation_payments.status', $status);

        if ($status == 1) {
            if ($userType == '801') {
                $paymentsQuery = $paymentsQuery->where('store_quotations.raisedBy', $user->id);
            } else {
                if ($user->typeOfCompany != '') {
                    $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                    $paymentsQuery = $paymentsQuery->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
                }
            }
        } else {
            $paymentsQuery = $paymentsQuery->whereBetween('store_quotation_payments.forDate', [$startDate, $endDate]);

            if ($userType == '801') {
                $paymentsQuery = $paymentsQuery->where('store_quotations.raisedBy', $user->id);
            } else {
                $typeOfCompany = explode(',', $user->typeOfCompany ?? []);
                $paymentsQuery = $paymentsQuery->whereIn('store_quotations.typeOfCompany', $typeOfCompany);
            }

            if ($typeOfCompanyId != '0') {
                $paymentsQuery = $paymentsQuery->where('store_quotations.typeOfCompany', $typeOfCompanyId);
            }
        }

        $payments = $paymentsQuery->orderBy('store_quotation_payments.forDate')->get();
        $this->rowCount = $payments->count();

        $serial = 1;
        return $payments->map(function ($item) use (&$serial) {
            return [
                $serial++,
                $item->poNumber,
                $item->typeOfCompanyName,
                $item->branchName,
                $item->vendorName,
                "'" . (string) $item->accountNo, // ✅ Fixed
                $item->IFSCCode,
                $item->percent,
                $item->amount,
                $item->quotationFor,
                Carbon::parse($item->forDate)->format('d-m-Y'),
                Carbon::parse($item->updated_at)->format('d-m-Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            "Sr. No.",
            "PO No",
            "Type of Company",
            "Branch",
            "Vendor Name",
            "Account Number",
            "IFSC Code",
            "Percent %",
            "Amount",
            "Quotation For",
            "Payment Date",
            "Updated At"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
        $sheet->getParent()->getDefaultStyle()->getFont()->setSize(14);

        return [
            'A' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'C' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'D' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],
            'E' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],
            'F' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]], // ✅ Account Number
            'G' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'H' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT]],
            'I' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],  // ✅ Quotation For
            'J' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],  // ✅ Payment Date
            'K' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $headerStyle = [
                    'font' => [
                        'size' => 12,
                        'bold' => true,
                        'color' => ['argb' => 'DD4B39'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D9D9D9'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->getStyle('A1:K1')->applyFromArray($headerStyle);
                $event->sheet->getDelegate()->freezePane('A2');
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);

                $allBordersStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];

                $range = 'A1:K' . ($this->rowCount + 1);
                $event->sheet->getDelegate()->getStyle($range)->applyFromArray($allBordersStyle);

                foreach (range('A', 'K') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
