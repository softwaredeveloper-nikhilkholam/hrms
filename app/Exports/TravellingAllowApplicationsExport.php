<?php

namespace App\Exports;
use App\EmpApplication;
use App\Department;
use App\Designation;
use App\Branch;
use App\EmpDet;
use App\User;
use DB;
use Auth;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TravellingAllowApplicationsExport implements FromCollection, WithHeadings, WithEvents
{
    private $month;
    private $type;
    private $rowCount;

    public function __construct($month, $type, $rowCount)
    {
        $this->month = $month;
        $this->type = $type;
        $this->rowCount = $rowCount;
    }

    public function collection()
    {
        $month=$this->month;
        $type=$this->type;

        $userType = Auth::user()->userType;
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        if($userType == '61' || $userType == '51' || $userType == '00' || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
        {
            $empIds = EmpDet::pluck('id');
        }
        elseif($userType == '101')
        {
            $empIds = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('departments.name', 'Security Department')
            ->pluck('emp_dets.id');
        }
        else
        {
            if($userType == '21')
            {
                $empIds = EmpDet::where('reportingId', $empId)->pluck('id');
            }
            elseif($userType == '11')
            {
                $empIds1 = EmpDet::where('reportingId', $empId)->pluck('id');
                $empIds2 = EmpDet::whereIn('reportingId', $empIds1)->pluck('id');
                $collection = collect($empIds1);
                $merged = $collection->merge($empIds2);
                $empIds = $merged->all(); 
            }
            else
            {
                $empIds = EmpDet::where('id', $empId)->pluck('id');
            }
        }

        if($type == 1)
        {
            $applications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId')
            ->where('type', 4)
            ->where('active', 1);
            if($userType == '61')
            {
                $applications = $applications->where('status2', 1);
            }
            elseif($userType == '51')
            {
                $applications = $applications->where('status1', 1);
            }
            else
            {
                $applications = $applications->whereIn('empId', $empIds);
            }

            $applications = $applications->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->whereIn('empId', $empIds)
            ->groupBy('empId')
            ->orderBy('empId')
            ->get();

            $temp=$apps=[];
            $i=1;
            foreach($applications as $application)
            {
                $empDet = EmpDet::select('departmentId', 'designationId', 'name', 'firmType', 'empCode')->where('id', $application->empId)->first();
                $temp['sNo'] = $i++;
                $temp['empCode'] = $empDet->empCode;
                $temp['empName'] = $empDet->name;
                $temp['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
                $temp['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
                
                $temp['totalCt'] = EmpApplication::where('type', 4)
                ->where('empId', $application->empId)
                ->where('active', 1)
                ->where('startDate', '>=', $fromDate)
                ->where('startDate', '<=', $toDate)
                ->count();

                $temp['totalCt'] = ($temp['totalCt'] == 0)?'0':$temp['totalCt'];

                $temp['reportAuthCt'] = EmpApplication::where('type', 4)
                ->where('empId', $application->empId)
                ->where('active', 1)
                ->where('status1', 0)
                ->where('startDate', '>=', $fromDate)
                ->where('startDate', '<=', $toDate)
                ->count();

                $temp['reportAuthCt'] = ($temp['reportAuthCt'] == 0)?'0':$temp['reportAuthCt'];

                $temp['hrPendingCt'] = EmpApplication::where('type', 4)
                ->where('empId', $application->empId)
                ->where('active', 1)
                ->where('status1', 1)
                ->where('status2', 0)
                ->where('startDate', '>=', $fromDate)
                ->where('startDate', '<=', $toDate)
                ->count();

                $temp['hrPendingCt'] = ($temp['hrPendingCt'] == 0)?'0':$temp['hrPendingCt'];

                $temp['accountPendingCt'] = EmpApplication::where('type', 4)
                ->where('empId', $application->empId)
                ->where('active', 1)
                ->where('status1', 1)
                ->where('status2', 1)
                ->where('startDate', '>=', $fromDate)
                ->where('startDate', '<=', $toDate)->count();

                $temp['accountPendingCt'] = ($temp['accountPendingCt'] == 0)?'0':$temp['accountPendingCt'];

                array_push($apps, $temp);
                
            }

            $agfApplications = collect($apps);
            $agfApplications = $agfApplications->sortBy('sNo')->values();
            $this->rowCount = count($agfApplications);

            return $agfApplications;

            // if($userType == '51')
            //     $applications = $apps->sortByDesc('hrPendingCt')->values();
            // elseif($userType == '21' || $userType == '11')
            //     $applications = $apps->sortByDesc('reportAuthCt')->values();
            // else
            //     $applications = $apps->sortByDesc('accountPendingCt')->values();
        }
        else
        {
            $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('emp_applications.startDate as sNo','emp_applications.startDate','emp_dets.empCode', 
             'emp_dets.name', 'departments.name as deptName', 'designations.name as desiName',
            'emp_applications.reason','emp_applications.description',
            'emp_applications.status1','emp_applications.updatedAt1','emp_applications.approvedBy1',
            'emp_applications.status2','emp_applications.updatedAt2','emp_applications.approvedBy2',
            'emp_applications.status','emp_applications.updatedAt3','emp_applications.approvedBy',
            'emp_applications.rejectReason')
            ->whereIn('emp_applications.empId', $empIds)
            ->where('emp_applications.startDate', '>=', $fromDate)
            ->where('emp_applications.startDate', '<=', $toDate)
            ->where('emp_applications.type', 1)
            ->where('emp_applications.active', 1);
            if($userType == '61')
            {
                $applications = $applications->where('emp_applications.status2', 1);
            }
            elseif($userType == '51')
            {
                $applications = $applications->where('emp_applications.status1', 1);
            }
            else
            {
                $applications = $applications->whereIn('emp_applications.empId', $empIds);
            }

            $applications = $applications->orderBy('emp_applications.id')
            ->get();
            $i=1;
            foreach($applications as $application)
            {
                $application['sNo']=$i++;
                $application['startDate']=date('d-m-Y', strtotime($application->startDate));
                
                $application['status1']=($application->status1 == 0)?'Pending':(($application->status1 == 1)?'Approved':'Rejected');
                $application['updatedAt1']=($application->updatedAt1 != '')?date('d-m-Y h:i A', strtotime($application->updatedAt1)):'-';
                $application['approvedBy1']=($application->approvedBy1 == '')?'-':$application->approvedBy1;

                $application['status2']=($application->status2 == 0)?'Pending':(($application->status2 == 1)?'Approved':'Rejected');
                $application['updatedAt2']=($application->updatedAt2 != null)?date('d-m-Y h:i A', strtotime($application->updatedAt2)):'-';
                $application['approvedBy2']=($application->approvedBy2 == '')?'-':$application->approvedBy2;
               
                $application['status']=($application->status == 0)?'Pending':(($application->status == 1)?'Approved':'Rejected');
                $application['updatedAt3']=($application->updatedAt3 != null)?date('d-m-Y h:i A', strtotime($application->updatedAt3)):'-';
                $application['approvedBy']=($application->approvedBy == '')?'-':$application->approvedBy;
                
                if($application->rejectReason == '')
                    $application['rejectReason']='-';

            }

        }

        return $agfApplications = collect($applications);
    }

    public function headings(): array
    {
        $type=$this->type;
        if($type == 1)
            return ["Sr. No.", "Employee Code", "Employee Name","Department", "Designation","Total","Reporting Authority", "HR Department", "Accounts Department"];
        else
            return ["Sr. No.", "Date", "Emp Code", "Name", "Department", "Designation","Reason","Description","Reporting Authority Status","Reporting Auth. UpdatedAt","Reporting Authority UpdatedBy", "HR Department","HR Department UpdatedAt","HR Dept. UpdatedBy", "Accounts Dept.","Accounts Dept. UpdatedAT","Accounts Dept. Updated By","Reason"];

    }

    public function registerEvents(): array
    {
        if($this->type == 1)
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

                    $event->sheet->getDelegate()->freezePane('A2');

                    $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);

                    $styleArray = [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ],
                    ];

                    $event->sheet->getStyle('A1:I1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
                    
                    $cells = 'A1:I'.($this->rowCount+1);
                    $event->sheet->getStyle($cells)->applyFromArray($styleArray);

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
        else
        {
            return [
                AfterSheet::class => function(AfterSheet $event) {

                    $event->sheet->getDelegate()
                    ->getStyle('A1:R1')                                
                    ->getFont()
                    ->setSize(12)
                    ->setBold(true)
                    ->getColor()
                    ->setARGB('DD4B39');

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

                    $event->sheet->getStyle('A1:R1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
                    
                    $cells = 'A1:R'.($this->rowCount+1);
                    $event->sheet->getStyle($cells)->applyFromArray($styleArray);

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

                },
            ];
        }
    }
}


