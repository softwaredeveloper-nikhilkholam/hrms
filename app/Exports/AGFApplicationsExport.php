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

class AGFApplicationsExport implements FromCollection, WithHeadings, WithEvents
{
    private $month;
    private $type;
    private $rowCount;

    public function __construct($month, $type, $rowCount, $branchId, $departmentId, $designationId)
    {
        $this->branchId = $branchId;
        $this->departmentId = $departmentId;
        $this->designationId = $designationId;
        $this->month = $month;
        $this->type = $type;
        $this->rowCount = $rowCount;
    }

    public function collection()
    {
        $branchId=$this->branchId;
        $departmentId=$this->departmentId;
        $designationId=$this->designationId;
        $month=$this->month;
        $type=$this->type;

        $userType = Auth::user()->userType;
        $empId = Auth::user()->empId;
        $fromDate = date('Y-m-01', strtotime($month));
        $toDate = date('Y-m-t', strtotime($month));

        if($type == 1)
        {
            $user = Auth::user(); 
            $userType = $user->userType; 
            if($user->empId != '')
            {
                if($userType == '11')
                {
                    $users1 = EmpDet::where('reportingId', $user->empId)->where('active', 1)->pluck('id');
                    $users2 = EmpDet::whereIn('reportingId', $users1)->where('active', 1)->pluck('id');

                    $collection = collect($users1);
                    $merged = $collection->merge($users2);
                    $users = $merged->all();
                }

                if($userType == '21' || $userType == '11')
                {
                    $users = EmpDet::where('reportingId', $user->empId)->where('active', 1)->pluck('id');
                }
            }


            if($userType == '61' || $userType == '51' || $userType == '00' || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
            {
                $empIds = EmpDet::where('active', 1);
                if($branchId != 0)
                    $empIds=$empIds->where('branchId', $branchId);

                if($departmentId != 0)
                    $empIds=$empIds->where('departmentId', $departmentId);

                $empIds=$empIds->pluck('id');
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

            $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('emp_dets.empCode', 'emp_dets.name', 'departments.name as departmentName',
            'contactus_land_pages.branchName','emp_applications.*')
            ->where('emp_applications.startDate', '>=', $fromDate)
            ->where('emp_applications.startDate', '<=', $toDate)
            ->where('emp_applications.type', 1);
            if(empty($users))
                $applications=$applications->whereIn('emp_dets.id', $empIds);
            else
                $applications=$applications->whereIn('emp_dets.id', $users);


            if($branchId != 0)
                $applications=$applications->where('emp_dets.branchId', $branchId);

            $applications=$applications->get(); 

            $temp=$apps=[];
            $i=1;
            foreach($applications as $application)
            {
                $temp['sNo'] = $i++;
                $temp['date'] = date('d-m-Y', strtotime($application->startDate));
                $temp['branch'] = $application->branchName;
                $temp['empName'] = $application->empCode.' - '.$application->name;
                $temp['issue'] = $application->reason;
                $temp['description'] = $application->description;
                $temp['dayStatus'] = $application->dayStatus;
                $temp['reportingAuth'] = (($application->status1 == 1)?'Approved':(($application->status1 == 0)?'Pending':'Rejected')).' [ '.(($application->updatedAt1 == '')?'-':date('d-m-Y H:i', strtotime($application->updatedAt1))).' ] [ '.(($application->approvedBy1 == '')?'-':$application->approvedBy1).' ]';
                $temp['hr'] = (($application->status2 == '1')?'Approved':(($application->status2 == '0')?'Pending':'Rejected')).' [ '.(($application->updatedAt2 == '')?'-':date('d-m-Y H:i', strtotime($application->updatedAt2))).' ] [ '.(($application->approvedBy2 == '')?'-':$application->approvedBy2).' ]';
                $temp['account'] = ($application->status == '1')?'Approved':(($application->status == '0')?'Pending':'Rejected').' [ '.(($application->updatedAt3 == '')?'-':date('d-m-Y H:i', strtotime($application->updatedAt3))).' ] [ '.(($application->approvedBy == '')?'-':$application->approvedBy).' ]';
                $temp['reason'] = $application->rejectReason;
                
                array_push($apps, $temp);
                
            }

            $applications = collect($apps);

            $this->rowCount = count($applications);
            return $applications;

        }
        else
        {

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

            $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('emp_applications.startDate as sNo','emp_applications.startDate','contactus_land_pages.branchName','emp_dets.empCode', 
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

            if($branchId != 0)
            {
                $applications = $applications->where('emp_dets.branchId', $branchId);
            }

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
            return ["Sr. No.", "Date", "Branch", "Employee Name", "Issue In Brief", "Description", "Day Status","Reporting Authority", "HR Department", "Accounts Department", "Reason"];
        else
            return ["Sr. No.", "Date", "Branch", "Emp Code", "Name", "Department", "Designation","Reason","Description","Reporting Authority Status","Reporting Auth. UpdatedAt","Reporting Authority UpdatedBy", "HR Department","HR Department UpdatedAt","HR Dept. UpdatedBy", "Accounts Dept.","Accounts Dept. UpdatedAT","Accounts Dept. Updated By","Reason"];

    }

    public function registerEvents(): array
    {
        if($this->type == 1)
        {
            return [
                AfterSheet::class => function(AfterSheet $event) {

                    $event->sheet->getDelegate()
                    ->getStyle('A1:K1')                                
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
                    $event->sheet->getDelegate()->getStyle('J')->getFont()->setName('Times New Roman');
                    $event->sheet->getDelegate()->getStyle('K')->getFont()->setName('Times New Roman');

                    $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('E')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('F')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('G')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('H')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('J')->getFont()->setSize(14);
                    $event->sheet->getDelegate()->getStyle('K')->getFont()->setSize(14);

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

