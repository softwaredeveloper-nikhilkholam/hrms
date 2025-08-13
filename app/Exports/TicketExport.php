<?php

namespace App\Exports;

use App\Ticket;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TicketExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{
    protected $year;
    protected $type;
    protected $rowCount = 0;

    public function __construct($year = null, $type = null)
    {
        $this->year = $year;
        $this->type = $type;
    }

    public function collection()
    {
        $userType = Auth::user()->userType;

        $ticketsQuery = Ticket::join('emp_dets', 'tickets.empId', 'emp_dets.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select(
                'emp_dets.empCode',
                'emp_dets.name',
                'designations.name as designationName',
                'tickets.issueType',
                'tickets.issue',
                'tickets.status',
                'tickets.updated_at'
            );

        if ($userType == '61') {
            $ticketsQuery = $ticketsQuery->whereIn('tickets.issue', ['FORM 16', 'SALARY CERTIFICATE']);
        }

        if ($this->year) {
            $ticketsQuery = $ticketsQuery->whereYear('tickets.created_at', $this->year);
        }

        if ($this->type == '1') 
            $ticketsQuery = $ticketsQuery->whereIn('tickets.status', [1,4,5]);
        else
            $ticketsQuery = $ticketsQuery->whereIn('tickets.status', [2,3]);

        $tickets = $ticketsQuery->orderBy('tickets.created_at', 'desc')
            ->orderBy('tickets.status', 'desc')
            ->get();

        $exportData = collect();
        $i = 1;

        foreach ($tickets as $ticket) {
            // Map issueType
            switch ($ticket->issueType) {
                case 1:
                    $issueType = "Salary issue";
                    break;
                case 2:
                    $issueType = "Changes to be done at HR END";
                    break;
                case 3:
                    $issueType = "REQUESTES";
                    break;
                default:
                    $issueType = "EXIT FORMALITIES INITATION";
            }

            // Map status
            switch ($ticket->status) {
                case 1:
                    $status = "Pending";
                    break;
                case 2:
                    $status = "Closed";
                    break;
                case 4:
                    $status = "In Progress";
                    break;
                case 5:
                        $status = "Hold";
                        break;
                default:
                    $status = "Rejected";
            }

            $exportData->push([
                'No' => $i++,
                'Emp Code' => $ticket->empCode,
                'Emp Name' => $ticket->name,
                'Designation' => $ticket->designationName,
                'Issue Type' => $issueType,
                'Issue' => $ticket->issue,
                'Status' => $status,
                'Updated At' => $ticket->updated_at ? $ticket->updated_at->format('Y-m-d H:i:s') : '',
            ]);
        }

        $this->rowCount = $exportData->count();

        return $exportData;
    }

    public function headings(): array
    {
        return ["No", "Emp Code", "Emp Name", "Designation", "Issue Type", "Issue", "Status", "Updated At"];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Style the header row
                $sheet->getStyle('A1:H1')
                    ->getFont()
                    ->setSize(12)
                    ->setBold(true)
                    ->getColor()
                    ->setARGB('DD4B39');

                // Freeze the first row
                $sheet->freezePane('A2');

                // Set header row height
                $sheet->getRowDimension(1)->setRowHeight(40);

                // Apply border to all data cells
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                $range = 'A1:H' . ($this->rowCount + 1);
                $sheet->getStyle($range)->applyFromArray($styleArray);

                // Header fill color
                $sheet->getStyle('A1:H1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D9D9D9');

                // Set font and size for all columns
                foreach (range('A', 'H') as $col) {
                    $sheet->getStyle($col)->getFont()->setName('Times New Roman')->setSize(14);
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
