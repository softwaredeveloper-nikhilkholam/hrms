<?php

namespace App\Exports;
use App\EmpApplication;
use App\Department;
use App\Branch;
use App\EmpDet;
use App\Designation;
use App\User;
use Auth;
use DB;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeaveApplicationsExport implements FromCollection, WithHeadings, WithEvents
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
        $rowCount=$this->rowCount;
        
        $user = Auth::user(); 
        $empId = $user->empId; 
        $userType = $user->userType; 

        if($month == '')
            $month = date('Y-m-d');
        else
            $month = date('Y-m-d', strtotime($month));
        
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));
        if($userType == '51'  || $userType == '00' || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
        {
            $empIds = EmpDet::where('active', 1)->pluck('id');
        }
        elseif($userType == '101')
        {
            $empIds = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('emp_dets.active', 1)
            ->where('departments.name', 'Security Department')
            ->pluck('emp_dets.id');
        }
        else
        {
            if($empId == '')
                $empIds = EmpDet::where('reportingId', $empId)->where('reportingType', 2)->where('active', 1)->pluck('id');
            else
            {
                if($userType == '21')
                {
                    $empIds = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                }
                elseif($userType == '11')
                {
                    $empIds1 = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                    $empIds2 = EmpDet::whereIn('reportingId', $empIds1)->where('active', 1)->pluck('id');
                    $collection = collect($empIds1);
                    $merged = $collection->merge($empIds2);
                    $empIds = $merged->all(); 
                }
            }
        }

        if($type == 1)
        {
            $applications = EmpApplication::select(DB::raw('count(id)  as totApp'), 'empId')
            ->where('type', 3)
            ->where('active', 1)
            ->where('startDate', '>=', $fromDate)
            ->where('startDate', '<=', $toDate)
            ->whereIn('empId', $empIds)
            ->groupBy('empId')
            ->orderBy('empId')
            ->get();
            $temp=$tp=[];
            $i=1;
            foreach($applications as $application)
            {
                $empDet = EmpDet::select('departmentId', 'designationId', 'name', 'firmType', 'empCode')->where('id', $application->empId)->first();
                $temp['no'] = $i++;
                $temp['empCode'] = $empDet->empCode;
                $temp['empName'] = $empDet->name;
                $temp['departmentName'] = Department::where('id', $empDet->departmentId)->value('name');
                $temp['designationName'] = Designation::where('id', $empDet->designationId)->value('name');
                
                $temp['pendingCt'] = EmpApplication::where('type', 3)
                ->where('empId', $application->empId)
                ->where('active', 1)
                ->where('status', 0)
                ->where('startDate', '>=', $fromDate)
                ->where('startDate', '<=', $toDate)
                ->count();
                $temp['pendingCt'] = ($temp['pendingCt'] == 0)?'0':$temp['pendingCt'];
                
                $temp['totalCt'] = EmpApplication::where('type', 3)
                ->where('empId', $application->empId)
                ->where('active', 1)
                ->where('startDate', '>=', $fromDate)
                ->where('startDate', '<=', $toDate)->count();

                $temp['totalCt'] = ($temp['totalCt'] == 0)?'0':$temp['totalCt'];
                array_push($tp,$temp);

            }

            $application = collect($tp);
            $this->rowCount = count($application);
            return $application;
        }
        else
        {
            $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->select('emp_applications.startDate as sNo','emp_applications.startDate','emp_dets.empCode', 
             'emp_dets.name', 'departments.name as deptName', 'designations.name as desiName',
            'emp_applications.reason','emp_applications.description','emp_applications.created_at as appliedAt',
            'emp_applications.status','emp_applications.updated_at as updatedAt','emp_applications.updated_by',
            'emp_applications.rejectReason')
            ->whereIn('emp_applications.empId', $empIds)
            ->where('emp_applications.startDate', '>=', $fromDate)
            ->where('emp_applications.startDate', '<=', $toDate)
            ->where('emp_applications.type', 3)
            ->where('emp_applications.active', 1)
            ->whereIn('emp_applications.empId', $empIds)
            ->orderBy('emp_applications.id')
            ->get();
            $i=1;
            foreach($applications as $application)
            {
                $application['updated_by']=User::where('username',$application->updated_by)->value('name');
                $application['sNo']=$i++;
                $application['startDate']=date('d-m-Y', strtotime($application->startDate));
                $application['appliedAt']=date('d-m-Y h:i A', strtotime($application->appliedAt));
                $application['updatedAt']=date('d-m-Y h:i A', strtotime($application->appliedAt));
                $application['status']=($application->status == 0)?'Pending':(($application->status == 1)?'Approved':'Rejected');
            }
            
            $this->rowCount = count($applications);
            return $applications;
        }

    }

    public function headings(): array
    {
        $type=$this->type;
        if($type == 1)
            return ["Sr. No.","Code", "Employee Name", "Department", "Designation", "Pending", "Total Applications"];
        else
            return ["Sr. No.","Date", "Code", "Employee Name", "Department", "Designation", "Reason", "Description", "Leave Filled At",  "Status","Updated At","Updated By"];

    }

    public function registerEvents(): array
    {
        if($this->type == 1)
        {
            return [
                AfterSheet::class => function(AfterSheet $event) {

                    $event->sheet->getDelegate()
                    ->getStyle('A1:G1')                                
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

                    $event->sheet->getStyle('A1:G1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
                    
                    $cells = 'A1:G'.($this->rowCount+1);
                    $event->sheet->getStyle($cells)->applyFromArray($styleArray);

                    $event->sheet->getDelegate()->getStyle('A')->getFont()->setName('Times New Roman');
                    $event->sheet->getDelegate()->getStyle('B')->getFont()->setName('Times New Roman');
                    $event->sheet->getDelegate()->getStyle('C')->getFont()->setName('Times New Roman');
                    $event->sheet->getDelegate()->getStyle('D')->getFont()->setName('Times New Roman');
                    $event->sheet->getDelegate()->getStyle('E')->getFont()->setName('Times New Roman');
                    $event->sheet->getDelegate()->getStyle('F')->getFont()->setName('Times New Roman');
                    $event->sheet->getDelegate()->getStyle('G')->getFont()->setName('Times New Roman');

                    $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('E')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('F')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('G')->getFont()->setSize(14);

                    $event->sheet->getColumnDimension('A')->setAutoSize(true);
                    $event->sheet->getColumnDimension('B')->setAutoSize(true);
                    $event->sheet->getColumnDimension('C')->setAutoSize(true);
                    $event->sheet->getColumnDimension('D')->setAutoSize(true);
                    $event->sheet->getColumnDimension('E')->setAutoSize(true);
                    $event->sheet->getColumnDimension('F')->setAutoSize(true);
                    $event->sheet->getColumnDimension('G')->setAutoSize(true);
                },
            ];
        }
        else
        {
            return [
                AfterSheet::class => function(AfterSheet $event) {

                    $event->sheet->getDelegate()
                    ->getStyle('A1:L1')                                
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

                    $event->sheet->getStyle('A1:M1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
                    
                    $cells = 'A1:L'.($this->rowCount+1);
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
                   
                },
            ];
        }
    }

}

