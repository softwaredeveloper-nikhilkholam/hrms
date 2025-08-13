<?php

namespace App\Exports;
use App\ExitProcessStatus;
use App\Helpers\Utility;

use Auth;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StandardResignationExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{
    public function __construct($type)
    {
        $this->type = $type;
        $this->rowCount = 0;
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER
        ];
    }
    public function collection()
    {
        $util = new Utility();
        $empId = Auth::user()->empId;
        $userType = Auth::user()->userType;
      
        $resins = collect();

        if ($this->type == 1) 
        {
            $empId = Auth::user()->empId;
            $userType = Auth::user()->userType;
            $userId = Auth::user()->id;

            $resins = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
                ->join('departments', 'emp_dets.departmentId', 'departments.id')
                ->join('designations', 'emp_dets.designationId', 'designations.id')
                ->join('users', 'exit_process_statuses.reportingAuthId', 'users.id')
                ->select('emp_dets.name', 'users.name as reportingAuthorityName', 'exit_process_statuses.*', 
                'departments.name as departmentName','departments.section','designations.name as designationName')
                ->where('exit_process_statuses.active', 1)
                ->where('exit_process_statuses.status', 0);

            if($empId == '1914')
            {
                $resins = $resins->get();
            }
            else
            {
                $departmentColumns = [
                    '51' => 'hrDept',
                    '61' => 'accountDept',
                    '71' => 'itDept',
                    '81' => 'erpDept',
                    '91' => 'storeDept',
                    '401' => 'finalPermission',
                    '501' => 'finalPermission',
                    '201' => 'finalPermission',
                ];

                if (array_key_exists($userType, $departmentColumns)) {
                    $column = $departmentColumns[$userType];
                    if($userType == '51')
                        $resins = $resins->whereIn("exit_process_statuses.$column", [0, 1, 2])->orderByRaw("FIELD(exit_process_statuses.$column, 1, 0, 2)");
                    else
                        $resins = $resins->where("exit_process_statuses.$column", 1);

                    // Extra filtering for Teaching/Non Teaching
                    
                    if (in_array($userType, ['401', '501', '201'])) {
                        $resins = $resins->where('departments.section', $userType == '501' ? 'Non Teaching' : 'Teaching');
                    }

                    $resins = $resins->orderBy('exit_process_statuses.applyDate')->get();
                } else {
                    // Non-departmental roles, possibly Reporting Authority
                    if (!empty($empId)) {
                        $resins = $resins->where('emp_dets.reportingId', $empId);
                    }

                    $resins = $resins->orderBy('exit_process_statuses.reportingAuth')
                        ->orderBy('exit_process_statuses.created_at', 'desc')
                        ->get();
                }
            }
            // $resins = $this->getProcessedResignations($userType, $empId);
        } else {
            $empId = Auth::user()->empId;
            $userType = Auth::user()->userType;
            if ($userType == '51' || $userType == '61' || $userType == '71' || $userType == '81' || $userType == '91' || $userType == '401' || $userType == '501' || $userType == '201') {
                $resins = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
                    ->join('departments', 'emp_dets.departmentId', 'departments.id')
                    ->join('users', 'exit_process_statuses.reportingAuthId', 'users.id')
                    ->select('emp_dets.name', 'users.name as reportingAuthorityName', 'exit_process_statuses.*','departments.section')
                    ->whereIn('exit_process_statuses.status', [1,2,3]);

                $departmentColumns = [
                    '51' => 'hrDept',
                    '61' => 'accountDept',
                    '71' => 'itDept',
                    '81' => 'erpDept',
                    '91' => 'storeDept',
                    '401' => 'finalPermission',
                    '501' => 'finalPermission',
                    '201' => 'finalPermission',
                ];

                if (array_key_exists($userType, $departmentColumns)) {
                    $column = $departmentColumns[$userType];

                    if($userType == 61)
                        $resins = $resins->whereIn('exit_process_statuses.accountDept', [2,3]);

                    if($userType == 71)
                        $resins = $resins->whereIn('exit_process_statuses.itDept', [2,3]);

                    if($userType == 81)
                        $resins = $resins->whereIn('exit_process_statuses.erpDept', [2,3]);

                    if($userType == 91)
                        $resins = $resins->whereIn('exit_process_statuses.storeDept', [2,3]);

                    if($userType == 401 || $userType == 501 || $userType == 201)
                    {
                        if($userType == 501)
                            $resins = $resins->where('departments.section', 'Non Teaching');
                        else
                            $resins = $resins->where('departments.section', 'Teaching');
                    }
                    $resins = $resins->orderBy('exit_process_statuses.hrDept')
                    ->orderBy('exit_process_statuses.created_at', 'desc')
                    ->get();
                }
            } else {
                $resins = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
                    ->join('departments', 'emp_dets.departmentId', 'departments.id')
                    ->join('users', 'exit_process_statuses.reportingAuthId', 'users.id')
                    ->select('emp_dets.name', 'users.name as reportingAuthorityName', 'exit_process_statuses.*','departments.section');
                if ($empId != '')
                    $resins = $resins->where('emp_dets.reportingId', $empId);

                $resins = $resins->orderBy('exit_process_statuses.reportingAuth')
                    ->orderBy('exit_process_statuses.created_at', 'desc')
                    ->get();
            }
        }

        $this->rowCount = count($resins);
        $tempData = $resignations = [];
        $i=1;
        foreach($resins as $key => $resin)
        {
            $tempData['no'] = $i++;
            $tempData['raisedAt'] = date('d-m-Y', strtotime($resin->applyDate));
            $tempData['type'] = "Standard Process";
            $tempData['empCode'] = $resin->empCode;
            $tempData['name'] = $resin->name;
            $tempData['repoName'] = $resin->reportingAuthorityName;
            $tempData['reportingAuth'] = $this->getStatusLabel($resin->reportingAuth);
            $tempData['storeDept'] = $this->getStatusLabel($resin->storeDept);
            $tempData['itDept'] = $this->getStatusLabel($resin->itDept);
            $tempData['erpDept'] = $this->getStatusLabel($resin->erpDept);
            $tempData['hrDept'] = $this->getStatusLabel($resin->hrDept);
            $tempData['mdCoo'] = $this->getStatusLabel($resin->finalPermission);
            $tempData['accountDept'] = $this->getStatusLabel($resin->accountDept);
            $tempData['status'] = $this->getStatusLabel($resin->status);
            $tempData['updatedAt'] = date('d-m-Y H:i', strtotime($resin->updated_at));
            $tempData['updatedBy'] = $resin->updated_by;
            array_push($resignations, $tempData);
        }

        return collect($resignations);
    }

    private function getStatusLabel($value)
    {
        if ($value == 0) {
            return 'Pending';
        } elseif ($value == 1) {
            return 'In Progress';
        } elseif ($value == 2) {
            return 'Completed';
        } else {
            return 'Rejected';
        }
    }

    public function headings(): array
    {
        return ["No","Resign Date","Type","Emp Code","Name","Reporting Person","Reporting Authority","Store Dept","IT Dept","ERP Dept","HR Dept","MD/CEO/COO","Accounts Dept","Status","Updated At","Updated By"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()
                    ->getStyle('A1:P1')
                    ->getFont()->setSize(12)->setBold(true)->getColor()->setARGB('DD4B39');

                $event->sheet->getDelegate()->freezePane('A2');
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                $event->sheet->getStyle('A1:P1')->getFill()->applyFromArray([
                    'fillType' => 'solid',
                    'color' => ['rgb' => 'D9D9D9'],
                ]);

                $cells = 'A1:P'.($this->rowCount + 1);
                $event->sheet->getStyle($cells)->applyFromArray($styleArray);

                foreach (range('A', 'P') as $col) {
                    $event->sheet->getDelegate()->getStyle($col)->getFont()->setName('Times New Roman')->setSize(14);
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
